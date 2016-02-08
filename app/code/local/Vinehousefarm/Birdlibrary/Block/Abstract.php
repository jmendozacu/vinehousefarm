<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Abstract extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var Vinehousefarm_Birdlibrary_Model_Resource_Bird_Collection
     */
    protected $_collection;

    /**
     * @return Vinehousefarm_Birdlibrary_Model_Resource_Bird_Collection
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    /**
     * @param Vinehousefarm_Birdlibrary_Model_Resource_Bird_Collection $collection
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
    }
}