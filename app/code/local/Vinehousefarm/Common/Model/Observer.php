<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Common_Model_Observer
{
    public function customerGroupSaveBefore(Varien_Event_Observer $observer)
    {
        $model = $observer->getEvent()->getObject();

        if ($model) {
            $param = Mage::app()->getRequest()->getParam('minimal_order', 0);
            $model->setMinimalOrder($param);
        }

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function controllerActionPostdispatchCheckoutOnepageSaveBilling(Varien_Event_Observer $observer)
    {
        /* @var $controller Mage_Checkout_OnepageController */
        $controller = $observer->getEvent()->getControllerAction();
        $response = Mage::app()->getFrontController()->getResponse()->getBody(true);

        if (!isset($response['default'])) {
            return;
        }

        $data = Mage::helper('vinehousefarm_common')->getDefaultShippingMethod();
        $customerAddressId = $controller->getRequest()->getPost('shipping_address_id', false);

        if (!$customerAddressId) {
            $customerAddressId = $controller->getRequest()->getPost('billing_address_id', false);
        }

        $controller->getOnepage()->saveShipping($data, $customerAddressId);

        $response = Mage::helper('core')->jsonDecode($response['default']);

//        if ($response['goto_section'] == 'shipping_method') {
//            $response['goto_section'] = 'payment';
//            $response['update_section'] = array(
//                'name' => 'payment-method',
//                'html' => $this->_getPaymentMethodsHtml()
//            );
//
//            $controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
//        }
    }

    /**
     * @return string
     * @throws Mage_Core_Exception
     */
    protected function _getPaymentMethodsHtml()
    {
        $layout = Mage::getModel('core/layout');
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_paymentmethod');
        $layout->generateXml();
        $layout->generateBlocks();

        return $layout->getOutput();
    }
}