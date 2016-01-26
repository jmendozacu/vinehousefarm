<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Common_Block_Catalog_Product_View_Bought extends Mage_Core_Block_Template
{
    /**
     * @var int
     */
    protected $num_product = 3;

    /**
     * @var Mage_Catalog_Model_Resource_Product_Collection
     */
    protected $_collection;

    protected $_items;

    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        if (!$this->getCollection()) {

            $orderItems = Mage::getResourceModel('sales/order_item_collection')
                ->addFieldToFilter('sku', $this->getProduct()->getSku())
                ->toArray(array('order_id'));

            $orderIds = array_unique(array_map(
                function($orderItem) {
                    return $orderItem['order_id'];
                },
                $orderItems['items']
            ));

            $orderItems = Mage::getResourceModel('sales/order_item_collection')
                ->addFieldToFilter('order_id', array('in' => $orderIds))
                ->addFieldToFilter('sku', array('neq' => $this->getProduct()->getSku()))
                ->setOrder('created_at')
                ->toArray(array('product_id'));

            $productIds = array_unique(array_map(
                function($orderItem) {
                    return $orderItem['product_id'];
                },
                $orderItems['items']
            ));

            $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToFilter('entity_id', array('in' => $productIds))
                ->setPageSize($this->getNumProduct());

            $this->setCollection($collection);
        }

        return parent::_beforeToHtml();
    }

    public function getRowCount()
    {
        return ceil(count($this->getCollection()->getItems())/$this->getNumProduct());
    }

    public function setColumnCount($columns)
    {
        if (intval($columns) > 0) {
            $this->num_product = intval($columns);
        }
        return $this->num_product;
    }

    public function getItems()
    {
        if (is_null($this->_items) && $this->getCollection()) {
            $this->_items = $this->getCollection()->getItems();
        }
        return $this->_items;
    }

    public function resetItemsIterator()
    {
        $this->getItems();
        reset($this->_items);
    }

    public function getIterableItem()
    {
        $item = current($this->_items);
        next($this->_items);
        return $item;
    }

    /**
     * Retrieve current product model
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function getProduct()
    {
        if (!Mage::registry('product') && $this->getProductId()) {
            $product = Mage::getModel('catalog/product')->load($this->getProductId());
            Mage::register('product', $product);
        }
        return Mage::registry('product');
    }

    /**
     * @return int
     */
    public function getNumProduct()
    {
        return $this->num_product;
    }

    /**
     * @param int $num_product
     */
    public function setNumProduct($num_product)
    {
        $this->num_product = $num_product;
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    /**
     * @param Mage_Catalog_Model_Resource_Product_Collection
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
    }
}