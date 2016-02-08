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
 * Class Mageplace_Gallery_Block_Photo_Abstract
 *
 */
class Mageplace_Gallery_Block_Photo_Abstract extends Mageplace_Gallery_Block_Abstract
{
    /**
     * @var Mageplace_Gallery_Helper_Photo
     */
    protected $_photoHelper;

    protected function _construct()
    {
        parent::_construct();

        $this->_photoHelper  = Mage::helper('mpgallery/photo');
    }

    /**
     * @return Mageplace_Gallery_Model_Photo
     */
    public function getCurrentPhoto()
    {
        if (!$this->hasData('current_photo')) {
            $this->setData('current_photo', Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_PHOTO));
        }

        return $this->_getData('current_photo');
    }

    /**
     * @return Mageplace_Gallery_Model_Mysql4_Photo_Collection|null
     */
    public function getCurrentAlbumPhotos()
    {
        if (!$this->hasData('current_album_photo')) {
            $this->setData('current_album_photo', Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM_PHOTOS));
        }

        return $this->_getData('current_album_photo');
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getPhotoSettings()
    {
        if (!$this->hasData('photo_settings')) {
            $this->setData('photo_settings', $this->getCurrentPhoto()->getDisplaySettings());
        }

        return $this->_getData('photo_settings');
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getPhotoSizes()
    {
        if (!$this->hasData('photo_sizes')) {
            $this->setData('photo_sizes', $this->getCurrentPhoto()->getSizeSettings());
        }

        return $this->_getData('photo_sizes');
    }
}
