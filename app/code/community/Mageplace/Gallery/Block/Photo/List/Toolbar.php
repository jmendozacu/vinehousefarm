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
 * Class Mageplace_Gallery_Block_Photo_List_Toolbar
 *
 * @method Mageplace_Gallery_Block_Photo_List_Toolbar setDefaultGridPerPage
 * @method Mageplace_Gallery_Block_Photo_List_Toolbar setDefaultListPerPage
 * @method Mageplace_Gallery_Block_Photo_List_Toolbar setDefaultSimplePerPage
 * @method int|null getDefaultGridPerPage
 * @method int|null getDefaultListPerPage
 * @method int|null getDefaultSimplePerPage
 */
class Mageplace_Gallery_Block_Photo_List_Toolbar extends Mageplace_Gallery_Block_List_Toolbar
{
    public function getGalleryObjectName()
    {
        return Mageplace_Gallery_Helper_Const::PHOTO;
    }

    protected function _construct()
    {
        parent::_construct();

        $this->_orderField = $this->getSettings()->getData('photo_default_sort_by');

        $this->initAvailableOrder($this->getSettings()->getPhotoAvailableSortBy());

        if ($this->getSettings()->getPhotoDefaultSortDir()) {
            $this->_direction = $this->getSettings()->getPhotoDefaultSortDir();
        }

        $this->_availableMode = Mageplace_Gallery_Helper_Const::$DISPLAY_TYPES[$this->getSettings()->getPhotoDisplayType()];
    }
}
