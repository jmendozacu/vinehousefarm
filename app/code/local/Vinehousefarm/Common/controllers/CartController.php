<?php
/**
 * @package Vine-House-Farm
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */
class Vinehousefarm_Common_CartController extends Mage_Core_Controller_Front_Action
{
    /**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

    /**
     * Shopping cart display action
     */
    public function indexAction()
    {
        $cart = $this->_getCart();
        if ($cart->getQuote()->getItemsCount()) {
            $cart->init();
            $cart->save();

            if (!$this->_getQuote()->validateMinimumAmount()) {
                $minAmount = Mage::helper('minimumorder')->getMinimalOrder();
                $minimumAmount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())
                    ->toCurrency($minAmount);

                $warning = Mage::getStoreConfig('sales/minimum_order/description')
                    ? Mage::getStoreConfig('sales/minimum_order/description')
                    : Mage::helper('checkout')->__('Minimum order amount is %s', $minimumAmount);

                $cart->getCheckoutSession()->addNotice($warning);
            }
        }

        // Compose array of messages to add
        $messages = array();
        foreach ($cart->getQuote()->getMessages() as $message) {
            if ($message) {
                // Escape HTML entities in quote message to prevent XSS
                $message->setCode(Mage::helper('core')->escapeHtml($message->getCode()));
                $messages[] = $message;
            }
        }
        $cart->getCheckoutSession()->addUniqueMessages($messages);

        /**
         * if customer enteres shopping cart we should mark quote
         * as modified bc he can has checkout page in another window.
         */
        $this->_getSession()->setCartWasUpdated(true);

        Varien_Profiler::start(__METHOD__ . 'cart_display');
        $this
            ->loadLayout()
            ->_initLayoutMessages('checkout/session')
            ->_initLayoutMessages('catalog/session')
            ->getLayout()->getBlock('head')->setTitle($this->__('Shopping Cart'));
        $this->renderLayout();
        Varien_Profiler::stop(__METHOD__ . 'cart_display');
    }

    /**
     * Add multi product to cart.
     *
     * @throws Mage_Exception
     */
    public function multiAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_goBack();
            return;
        }

        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();

        try {

            $productNeed = array();

            if (!array_key_exists('product-id', $params)) {
                $this->_goBack();
                return;
            }

            $productNeed = $params['product-id'];

            if (!array_key_exists('product-qty', $params)) {
                $this->_goBack();
                return;
            }

            foreach ($params['product-qty'] as $key=>$qty) {

                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );

                $qty = $filter->filter($qty);

                if (!array_key_exists($key, $productNeed)) {
                    $this->_goBack();
                    return;
                }

                $product = Mage::getModel('catalog/product')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($productNeed[$key]);

                if (array_key_exists('super-id', $params)) {
                    if (array_key_exists('super-value', $params)) {

                        if (array_key_exists($key, $params['super-id']) && array_key_exists($key, $params['super-value'])) {
                            $super_attribute = array(
                                $params['super-id'][$key] => $params['super-value'][$key]
                            );

                            $product->setConfiguredAttributes($super_attribute);
                        }
                    }
                }

                if (array_key_exists('super_group', $params)) {
                    $product->setGroupedProducts($params['super_group']);
                }

                /**
                 * Check product availability
                 */
                if (!$product) {
                    $this->_goBack();
                    return;
                }

                $eventParams = array(
                    'product' => $product,
                    'qty' => $qty,
                    'request' => $this->getRequest(),
                    'response' => $this->getResponse()
                );

                Mage::dispatchEvent('checkout_cart_before_add', $eventParams);

                $cart->addProduct($product, $qty);

                Mage::dispatchEvent('checkout_cart_after_add', $eventParams);
                Mage::dispatchEvent('checkout_cart_add_product', array('product'=>$product));

                /**
                 * @todo remove wishlist observer processAddToCart
                 */
                Mage::dispatchEvent('checkout_cart_add_product_complete',
                    array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                );

                if (!$this->_getSession()->getNoCartRedirect(true)) {
                    if (!$cart->getQuote()->getHasError()) {
                        $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                        $this->_getSession()->addSuccess($message);
                    }
                }
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                $this->_goBack();
            }
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            $this->_goBack();
        }
    }

    /**
     * Set back redirect url to response
     *
     * @return Mage_Checkout_CartController
     * @throws Mage_Exception
     */
    protected function _goBack()
    {
        $returnUrl = $this->getRequest()->getParam('return_url');
        if ($returnUrl) {

            if (!$this->_isUrlInternal($returnUrl)) {
                throw new Mage_Exception('External urls redirect to "' . $returnUrl . '" denied!');
            }

            $this->_getSession()->getMessages(true);
            $this->getResponse()->setRedirect($returnUrl);
        } elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart')
            && !$this->getRequest()->getParam('in_cart')
            && $backUrl = $this->_getRefererUrl()
        ) {
            $this->getResponse()->setRedirect($backUrl);
        } else {
            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
            }
            $this->_redirect('checkout/cart');
        }
        return $this;
    }

    /**
     * Validate Form Key
     *
     * @return bool
     */
    protected function _validateFormKey()
    {
        $validated = true;
        if (Mage::getStoreConfigFlag(self::XML_CSRF_USE_FLAG_CONFIG_PATH)) {
            $validated = parent::_validateFormKey();
        }
        return $validated;
    }
}