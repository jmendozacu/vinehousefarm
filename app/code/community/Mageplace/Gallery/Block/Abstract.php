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
 * Class Mageplace_Gallery_Block_Abstract
 *
 */
class Mageplace_Gallery_Block_Abstract extends Mage_Core_Block_Template
{
    /**
     * @var Mageplace_Gallery_Helper_Config
     */
    protected $_configHelper;
    /**
     * @var Mageplace_Gallery_Helper_Url
     */
    protected $_urlHelper;
    /**
     * @var Mageplace_Gallery_Helper_Album
     */
    protected $_albumHelper;

    protected function _construct()
    {
        parent::_construct();

        $this->_configHelper = Mage::helper('mpgallery/config');
        $this->_urlHelper    = Mage::helper('mpgallery/url');
        $this->_albumHelper  = Mage::helper('mpgallery/album');
    }

    /**
     * @return Mageplace_Gallery_Model_Album
     */
    public function getCurrentAlbum()
    {
        if (!$this->hasData('current_album')) {
            $this->setData('current_album', Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM));
        }

        return $this->_getData('current_album');
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getAlbumSettings()
    {
        if (!$this->hasData('album_settings')) {
            $this->setData('album_settings', $this->getCurrentAlbum()->getDisplaySettings());
        }

        return $this->_getData('album_settings');
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getAlbumSizes()
    {
        if (!$this->hasData('album_sizes')) {
            $this->setData('album_sizes', $this->getCurrentAlbum()->getSizeSettings());
        }

        return $this->_getData('album_sizes');
    }

    /**
     * @return Mageplace_Gallery_Model_Album
     */
    public function getCurrentActiveAlbum()
    {
        if (!$this->hasData('current_active_album')) {
            $this->setData('current_active_album', Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ACTIVE_ALBUM));
        }

        return $this->_getData('current_active_album');
    }

    public function getImage($object, $type, $image)
    {
        return $this->helper('mpgallery/image')->initialize($object, $type)->resizeBySize($image);
    }

    public function IsTopAlbum()
    {
        return $this->getCurrentAlbum()->getLevel() == 2;
    }

    public function IsRootAlbum()
    {
        return $this->getCurrentAlbum()->isRoot();
    }

    public function isJqueryEnable()
    {
        return $this->_configHelper->isJqueryEnable();
    }

    public function isSlideshowAutostart()
    {
        return $this->_configHelper->isSlideshowAutostart();
    }

    public function slideshowDelay()
    {
        return (int)$this->_configHelper->slideshowDelay();
    }

    public function getCacheTags()
    {
        return array_merge(parent::getCacheTags(), $this->getCurrentAlbum()->getCacheIdTags());
    }
}
