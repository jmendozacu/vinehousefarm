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
 * Class Mageplace_Gallery_Model_Source_Displaymode
 */
class Mageplace_Gallery_Model_Source_Displaymode extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_MODE_ALBUM_AND_PHOTO, 'label' => $this->_helper()->__('Albums and photos')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO, 'label' => $this->_helper()->__('Static block, albums and photos')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM, 'label' => $this->_helper()->__('Static block and albums')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_PHOTO, 'label' => $this->_helper()->__('Static block and photos')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_MODE_ALBUM_ONLY, 'label' => $this->_helper()->__('Albums only')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_MODE_PHOTO_ONLY, 'label' => $this->_helper()->__('Photos only')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_ONLY, 'label' => $this->_helper()->__('Static block only')),
        );
    }
}