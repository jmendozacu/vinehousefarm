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
 * Class Mageplace_Gallery_Block_Customer_Photo_Abstract
 */
class Mageplace_Gallery_Block_Customer_Photo_Abstract extends Mage_Core_Block_Template
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
    /**
     * @var Mageplace_Gallery_Helper_Photo
     */
    protected $_photoHelper;

    protected function _construct()
    {
        parent::_construct();

        $this->_configHelper = Mage::helper('mpgallery/config');
        $this->_urlHelper    = Mage::helper('mpgallery/url');
        $this->_albumHelper  = Mage::helper('mpgallery/album');
        $this->_photoHelper  = Mage::helper('mpgallery/photo');
    }

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function getCustomerEmail()
    {
        if ($this->_getData('customer_email') === null) {
            $this->setData('customer_email', $this->getCustomer()->getEmail());
        }

        return $this->_getData('customer_email');
    }

    public function getImage($object, $type, $image)
    {
        return $this->helper('mpgallery/image')->initialize($object, $type)->resizeBySize($image);
    }

    public function isEnable()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn()
        && $this->_configHelper->isPhotoUploadEnable()
        && $this->_configHelper->isPhotoUploadCustomerView();
    }

    public function canView($photo)
    {
        return $this->_photoHelper->canShow($photo);
    }

    public function canEdit($photo)
    {
        return $this->_photoHelper->canEdit();
    }

    public function canDelete($photo)
    {
        return $this->_configHelper->isPhotoUploadCustomerDelete();
    }

    public function getUploadUrl()
    {
        return $this->_urlHelper->getCustomerPhotoUrl('uploadform', array('back' => base64_encode(Mage::helper('core/url')->getCurrentUrl())));
    }

    public function getEditUrl($photo)
    {
        return $this->_urlHelper->getPhotoEditUrl($photo, array('back' => base64_encode(Mage::helper('core/url')->getCurrentUrl())));
    }

    public function getViewUrl($photo)
    {
        return $this->_urlHelper->getPhotoUrl($photo);
    }

    public function getDeleteUrl($photo)
    {
        return $this->_urlHelper->getPhotoDeleteUrl($photo, array('back' => base64_encode(Mage::helper('core/url')->getCurrentUrl())));
    }
}
