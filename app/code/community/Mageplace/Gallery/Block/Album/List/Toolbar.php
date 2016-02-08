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
 * Class Mageplace_Gallery_Block_Album_List_Toolbar
 */
class Mageplace_Gallery_Block_Album_List_Toolbar extends Mageplace_Gallery_Block_List_Toolbar
{
    function getGalleryObjectName()
    {
        return Mageplace_Gallery_Helper_Const::ALBUM;
    }

    public function isLimitEnable()
    {
        return false;
    }

    protected function _construct()
    {
        parent::_construct();

        $this->_orderField = $this->getSettings()->getData('album_default_sort_by');

        $this->initAvailableOrder($this->getSettings()->getAlbumAvailableSortBy());

        if($this->getSettings()->getData('album_default_sort_dir')) {
            $this->_direction = $this->getSettings()->getData('album_default_sort_dir');
        }

        $this->_availableMode = Mageplace_Gallery_Helper_Const::$DISPLAY_TYPES[$this->getSettings()->getAlbumDisplayType()];
    }
}