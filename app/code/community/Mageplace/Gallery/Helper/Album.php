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
 * Class Mageplace_Gallery_Helper_Album
 */
class Mageplace_Gallery_Helper_Album extends Mageplace_Gallery_Helper_Item
{
    const ALBUM_IMAGE_PATH = 'album';

    protected function getItemPath()
    {
        return self::ALBUM_IMAGE_PATH;
    }

    /**
     * @param int|Mageplace_Gallery_Model_Album $album
     *
     * @return bool
     */
    public function canShow($album)
    {
        if (!$album instanceof Mageplace_Gallery_Model_Album) {
            $album = Mage::getModel('mpgallery/album')->load(intval($album));
        }

        return parent::canShow($album);
    }
}