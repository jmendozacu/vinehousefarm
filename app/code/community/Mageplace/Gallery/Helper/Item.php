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
 * Class Mageplace_Gallery_Helper_Item
 */
abstract class Mageplace_Gallery_Helper_Item extends Mageplace_Gallery_Helper_Data
{
    protected $_imageUrl;
    protected $_imageDir;

    public function __construct()
    {
        $this->_configHelper = Mage::helper('mpgallery/config');

        $suffix = $this->_configHelper->getImagePath();

        $this->_imageUrl = Mage::getBaseUrl('media')
            . (substr($suffix, 0, 1) == '/' ? substr($suffix, 1) : $suffix)
            . (substr($suffix, -1) == '/' ? '' : '/') . $this->getItemPath();

        $this->_imageDir = Mage::getBaseDir('media')
            . (substr($suffix, 0, 1) == '/' ? '' : '/') . $suffix
            . (substr($suffix, -1) == '/' ? '' : '/') . $this->getItemPath();
    }

    abstract protected function getItemPath();

    public function getBaseUrl()
    {
        return $this->_imageUrl;
    }

    public function getBaseDir()
    {
        return $this->_imageDir;
    }

    public function getImageUrl($file = null)
    {
        return $this->getBaseUrl() . $file;
    }

    public function getImageDir($file = null)
    {
        return $this->getBaseDir() . $file;
    }
}