<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Catalog_Product_View_Birds extends Vinehousefarm_Birdlibrary_Block_Abstract
{
    /**
     * @return Vinehousefarm_Birdlibrary_Model_Resource_Bird_Collection
     */
    public function getBirdCollection()
    {
        if (!$this->getCollection()) {
            $collection = Mage::getModel('birdlibrary/bird')->getCollection()
                ->addFieldToFilter('entity_id', array('in' => $this->getBirdByProduct()));

            $this->setCollection($collection);
        }

        return $this->getCollection();
    }

    /**
     * Return birds id.
     *
     * @return array
     */
    protected function getBirdByProduct()
    {
        $birds = array(0);

        $collection = Mage::getModel('birdlibrary/product')->getCollection()
            ->addFieldToFilter('product_id', $this->getProduct()->getId())
            ->setOrder('position');

        if ($collection) {
            foreach ($collection as $item) {
                $birds[$item->getBirdId()] = $item->getBirdId();
            }
        }

        return $birds;
    }

    /**
     * Retrieve current product model
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!Mage::registry('product') && $this->getProductId()) {
            $product = Mage::getModel('catalog/product')->load($this->getProductId());
            Mage::register('product', $product);
        }
        return Mage::registry('product');
    }
}