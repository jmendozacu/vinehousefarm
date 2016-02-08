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
 * Class Mageplace_Gallery_Block_Album_Abstract
 *
 */
class Mageplace_Gallery_Block_Album_Abstract extends Mage_Core_Block_Template
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

    public function IsTopAlbum()
    {
        return $this->getCurrentAlbum()->getLevel() == 2;
    }

    public function getSettings()
    {
        return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM_DISPLAY_SETTINGS);
    }

    public function getCacheTags()
    {
        return array_merge(parent::getCacheTags(), $this->getCurrentAlbum()->getCacheIdTags());
    }
}
