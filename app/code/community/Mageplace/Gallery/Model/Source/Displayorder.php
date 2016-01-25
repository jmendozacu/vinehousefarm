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
 * Class Mageplace_Gallery_Model_Source_Displayorder
 */
class Mageplace_Gallery_Model_Source_Displayorder extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_BLOCK_ALBUM_PHOTO, 'label' => $this->_helper()->__('Static block, albums, photos')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_BLOCK_PHOTO_ALBUM, 'label' => $this->_helper()->__('Static block, photos, albums')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_ALBUM_PHOTO_BLOCK, 'label' => $this->_helper()->__('Albums, photos, static block')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_ALBUM_BLOCK_PHOTO, 'label' => $this->_helper()->__('Albums, static block, photos')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_PHOTO_ALBUM_BLOCK, 'label' => $this->_helper()->__('Photos, albums, static block')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_PHOTO_BLOCK_ALBUM, 'label' => $this->_helper()->__('Photos, static block, albums')),
        );
    }

    public function toOptionArrayExclBlock()
    {
        return array(
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_ALBUM_PHOTO_BLOCK, 'label' => $this->_helper()->__('Albums, photos')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_PHOTO_ALBUM_BLOCK, 'label' => $this->_helper()->__('Photos, albums')),
        );
    }

    public function toOptionArrayExclAlbum()
    {
        return array(
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_BLOCK_PHOTO_ALBUM, 'label' => $this->_helper()->__('Static block, photos')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_PHOTO_BLOCK_ALBUM, 'label' => $this->_helper()->__('Photos, static block')),
        );
    }

    public function toOptionArrayExclPhoto()
    {
        return array(
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_BLOCK_ALBUM_PHOTO, 'label' => $this->_helper()->__('Static block, albums')),
            array('value' => Mageplace_Gallery_Model_Album::DISPLAY_POSITION_ALBUM_BLOCK_PHOTO, 'label' => $this->_helper()->__('Albums, static block')),
        );
    }
}