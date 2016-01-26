<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Model_Observer
{
    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function catalogProductSaveAfter(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;

            $product = $observer->getEvent()->getProduct();

            try {
                $links =  $this->_getRequest()->getPost('links');

                $items = array();
                parse_str($links['birds'], $items);

                if (count($items) > 0) {
                    /* @var $removeCollection Vinehousefarm_Birdlibrary_Model_Resource_Product_Collection */
                    $removeCollection = Mage::getModel('birdlibrary/product')->getCollection()
                        ->addFieldToFilter('product_id', $product->getId());
                    $removeCollection->walk('delete');

                    /* @var $newCollection Vinehousefarm_Birdlibrary_Model_Resource_Product_Collection */
                    $newCollection = Mage::getModel('birdlibrary/product')->getCollection();

                    foreach ($items as $item => $value) {
                        /* @var $modelItem Vinehousefarm_Birdlibrary_Model_Product */
                        $modelItem = Mage::getModel('birdlibrary/product');

                        $modelItem->isObjectNew(true);
                        $modelItem->setBirdId($item);
                        $modelItem->setProductId($product->getId());

                        $modelItem->save();
                    }
                }
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }

    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}