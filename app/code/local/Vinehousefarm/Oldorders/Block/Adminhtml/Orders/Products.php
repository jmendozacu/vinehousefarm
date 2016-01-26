<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */

class Vinehousefarm_Oldorders_Block_Adminhtml_Orders_Products extends Mage_Core_Block_Template
{
    protected $collection;

    public function getProducts()
    {
        if (!$this->collection) {
            $this->collection = Mage::getModel('oldorders/products')->getCollection()
                ->addFieldToFilter('order_id', Mage::app()->getRequest()->getParam('id'));
        }

        return $this->collection;
    }
}