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
 * Class Mageplace_Gallery_Block_Album_List
 *
 */
class Mageplace_Gallery_Block_Album_List extends Mageplace_Gallery_Block_List
{
    function getGalleryObjectName()
    {
        return Mageplace_Gallery_Helper_Const::ALBUM;
    }

    function initCollection()
    {
        $this->setCollection(
            Mage::getResourceModel('mpgallery/album_collection')
                ->addParentFilter($this->getCurrentAlbum()->getId())
                ->addIsActiveFilter()
                ->addStoreFilter()
                ->addCustomerGroupFilter()
        );
    }

    public function getAlbums()
    {
        return $this->getCollection();
    }
}
