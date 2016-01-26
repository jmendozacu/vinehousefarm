<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */ 
class Vinehousefarm_Productvideo_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return Vinehousefarm_Productvideo_Model_Resource_Video_Collection
     */
    public function getVideoCollection(Mage_Catalog_Model_Product $product)
    {
        return Mage::getModel('productvideo/video')->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $this->getVideoByProduct($product)));
    }

    /**
     * Return video id.
     *
     * @return array
     */
    protected function getVideoByProduct(Mage_Catalog_Model_Product $product)
    {
        $videos = '';

        if (!is_array($videos)) {
            /* @var $collection Vinehousefarm_Productvideo_Model_Resource_Product_Collection */
            $collection = Mage::getModel('productvideo/product')->getCollection()
                ->addFieldToFilter('product_id', array('in' => $product->getId()));

            foreach ($collection as $item) {
                $videos[$item->getId()] = $item->getVideoId();
            };
        }

        return $videos;
    }
}