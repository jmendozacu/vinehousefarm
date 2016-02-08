<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Productvideo_Block_Catalog_Product_View_Videos extends Vinehousefarm_Productvideo_Block_Abstract
{
    /**
     * @return Vinehousefarm_Productvideo_Model_Resource_Video_Collection
     */
    public function getVideoCollection()
    {
        if (!$this->getCollection()) {
            $collection = Mage::getModel('productvideo/video')->getCollection()
                ->addFieldToFilter('entity_id', array('in' => $this->getVideoByProduct()));

            $this->setCollection($collection);
        }

        return $this->getCollection();
    }

    /**
     * Return video id.
     *
     * @return array
     */
    protected function getVideoByProduct()
    {
        $collection = Mage::getModel('productvideo/product')->getCollection()
            ->addFieldToFilter('product_id', $this->getProduct()->getId())
            ->setOrder('position');

        if ($collection) {
            return $collection->getAllIds();
        }

        return array(0);
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
}