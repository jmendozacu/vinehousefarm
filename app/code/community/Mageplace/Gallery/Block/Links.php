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
class Mageplace_Gallery_Block_Links extends Mage_Core_Block_Template
{
    /**
     * @var Mageplace_Gallery_Helper_Url
     */
    protected $_urlHelper;

    protected $_links = array();

    protected function _construct()
    {
        parent::_construct();

        $this->_urlHelper = Mage::helper('mpgallery/url');

        $this->setTemplate('mpgallery/links.phtml');
    }

    public function addLink($link)
    {
        $this->_links[$link] = $this->_urlHelper->getUrl($link);
    }

    public function getLinks()
    {
        return $this->_links;
    }
}
