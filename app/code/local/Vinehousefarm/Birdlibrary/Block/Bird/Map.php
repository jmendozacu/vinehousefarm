<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Bird_Map extends Vinehousefarm_Birdlibrary_Block_Bird
{
    /**
     * @return string
     */
    public function getBirdMapUrl()
    {
        return $this->helper('birdlibrary')->getBirdMap($this->getBird());
    }

    /**
     * @return bool
     */
    public function canShowMap()
    {
        if ($this->getBirdMapUrl()) {
            return true;
        }

        return false;
    }
}