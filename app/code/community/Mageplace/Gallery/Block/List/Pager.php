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
 * Class Mageplace_Gallery_Block_List_Pager
 */
class Mageplace_Gallery_Block_List_Pager extends Mage_Page_Block_Html_Pager
{
    /**
     * @var Mageplace_Gallery_Helper_Url
     */
    protected $_urlHelper;

    protected function _construct()
    {
        parent::_construct();

        $this->_urlHelper = Mage::helper('mpgallery/url');
    }

    public function getCurrentAlbum()
    {
        if (!$this->hasData('current_album')) {
            $this->setData('current_album', Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM));
        }

        return $this->_getData('current_album');
    }

    public function getPagerUrl($params = array())
    {
        return $this->_urlHelper->getAlbumUrl($this->getCurrentAlbum(), $params);
    }
}
