<?php
/**
 * MagePlace Gallery Extension
 *
 * @category    Mageplace_Gallery
 * @package     Mageplace_Gallery
 * @copyright   Copyright (c) 2014 Mageplace. (http://www.mageplace.com)
 * @license     http://www.mageplace.com/disclaimer.html
 */

/**
 * Class Mageplace_Gallery_Model_Source_Lightboxphotos
 */
class Mageplace_Gallery_Model_Source_Lightboxphotos extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        $options = array(
            array('value' => Mageplace_Gallery_Helper_Const::LIGHTBOX_PHOTOS_PAGE, 'label' => $this->_helper()->__('Photos of current page')),
            array('value' => Mageplace_Gallery_Helper_Const::LIGHTBOX_PHOTOS_CATEGORY, 'label' => $this->_helper()->__('Photos of current album')),
        );

        return $options;
    }
}