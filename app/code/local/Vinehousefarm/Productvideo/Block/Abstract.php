<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Productvideo_Block_Abstract extends Mage_Core_Block_Template
{
    /**
     * @var Vinehousefarm_Productvideo_Model_Resource_Video_Collection
     */
    protected $_collection;

    /**
     * @return Vinehousefarm_Productvideo_Model_Resource_Video_Collection
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    /**
     * @param Vinehousefarm_Productvideo_Model_Resource_Video_Collection $collection
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
    }
}