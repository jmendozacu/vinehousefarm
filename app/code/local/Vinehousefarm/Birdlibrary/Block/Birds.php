<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Birds extends Vinehousefarm_Birdlibrary_Block_Abstract
{
    public function getBirdCollection()
    {
        if (!$this->getCollection()) {
            $collection = Mage::getModel('birdlibrary/bird')->getCollection();
            $this->setCollection($collection);
        }

        return $this->getCollection();
    }
}