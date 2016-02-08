<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Bird_Slider extends Vinehousefarm_Birdlibrary_Block_Bird
{
    /**
     * @var array
     */
    protected $_gallery;

    /**
     * @return array
     */
    public function getBirdGallery()
    {
        if (!$this->_gallery) {
            foreach ($this->getBird()->getGallery() as $image) {
                $result = array();

                $result['label'] = $image['label'];
                $result['image'] = $image['file'];

                $this->_gallery[$image['position']] = new Varien_Object($result);
            }

            ksort($this->_gallery);
        }

        return $this->_gallery;
    }
}