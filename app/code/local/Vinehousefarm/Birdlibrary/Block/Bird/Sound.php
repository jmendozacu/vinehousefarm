<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Bird_Sound extends Vinehousefarm_Birdlibrary_Block_Bird
{
    /**
     * @return mixed
     */
    public function getSound()
    {
        return $this->helper('birdlibrary')->getBirdSound($this->getBird());
    }
}