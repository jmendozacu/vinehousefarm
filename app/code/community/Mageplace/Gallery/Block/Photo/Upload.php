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
 * Class Mageplace_Gallery_Block_Photo_Upload
 */
class Mageplace_Gallery_Block_Photo_Upload extends Mageplace_Gallery_Block_Abstract
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if (Mage::helper('mpgallery/config')->showBreadcrumbs()) {
            if ($this->isEditMode()) {
                $crumb['edit_photo'] = array(
                    'label' => $this->__('Edit Photo'),
                    'title' => $this->__('Edit Photo'),
                );
            } else {
                $crumb['upload_photo'] = array(
                    'label' => $this->__('Upload Photo'),
                    'title' => $this->__('Upload Photo'),
                );
            }
            $this->getLayout()->createBlock('mpgallery/breadcrumbs', 'gallery_breadcrumbs', $crumb);
        }

        return $this;
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

    public function getName()
    {
        if ($this->isEditMode()) {
            return $this->getCurrentPhoto()->getAuthorName();
        }

        return Mage::getSingleton('customer/session')->isLoggedIn() ? Mage::getSingleton('customer/session')->getCustomer()->getName() : '';
    }

    public function getEmail()
    {
        if ($this->isEditMode()) {
            return $this->getCurrentPhoto()->getAuthorEmail();
        }

        return Mage::getSingleton('customer/session')->isLoggedIn() ? Mage::getSingleton('customer/session')->getCustomer()->getEmail() : '';
    }

    public function getPhotoName()
    {
        return $this->isEditMode() ? $this->getCurrentPhoto()->getName() : '';
    }

    public function getPhotoDescription()
    {
        return $this->isEditMode() ? $this->getCurrentPhoto()->getDescription() : '';
    }

    public function getUploadButtonTitle()
    {
        if ($this->isEditMode()) {
            return $this->helper('customer')->__('Save');
        }

        return $this->__('Upload Photo');
    }

    public function getBackTitle()
    {
        if ($this->isProductPhotoUpload())
            return $this->__('Back to product');
        elseif ($this->isEditMode() || $this->isCustomerPhotoUpload())
            return $this->__('Back');
        else
            return $this->__('Back to album %s', $this->getCurrentActiveAlbum()->getName());
    }

    public function getBackUrl()
    {
        if ($this->isProductPhotoUpload()) {
            if ($this->getBackUrlFromRequest()) {
                return $this->getBackUrlFromRequest();
            }

            return $this->getProduct()->getProductUrl();
        } elseif ($this->isEditMode() || $this->isCustomerPhotoUpload()) {
            if ($this->getBackUrlFromRequest()) {
                return $this->getBackUrlFromRequest();
            }

            return $this->_urlHelper->getCustomerPhotoUrl();
        } elseif ($this->getCurrentActiveAlbum()) {
            return $this->_urlHelper->getAlbumUrl($this->getCurrentActiveAlbum());
        }

        return '';
    }

    public function getCurrentAlbumKey()
    {
        if ($this->isCustomerPhotoUpload()) {
            return null;
        }

        return $this->getCurrentActiveAlbum()->getUrlKey();
    }

    public function getUploadUrl()
    {
        if ($this->isProductPhotoUpload()) {
            return $this->_urlHelper->getGalleryUrl(array('upload' => 'photo_save'));
        } elseif ($this->isCustomerPhotoUpload()) {
            return $this->_urlHelper->getCustomerPhotoUrl('upload');
        } elseif ($this->isEditMode()) {
            return $this->_urlHelper->getPhotoEditSaveUrl($this->getCurrentPhoto());
        } elseif ($this->getCurrentActiveAlbum()) {
            return $this->_urlHelper->getAlbumUrl($this->getCurrentActiveAlbum(), array('upload' => 'photo_save'));
        }

        return '';
    }

    public function isEditMode()
    {
        return Mage::registry('mpgallery_photo_edit');
    }

    public function isCustomerPhotoUpload()
    {
        return Mage::registry('mpgallery_customer_photo_upload');
    }

    public function isProductPhotoUpload()
    {
        return Mage::registry('mpgallery_product_photo_upload');
    }

    public function getProduct()
    {
        return Mage::registry('mpgallery_product');
    }

    public function getProductId()
    {
        return $this->getProduct()->getId();
    }

    public function getBackUrlFromRequest()
    {
        if ($this->getRequest()->getParam('back')) {
            return base64_decode($this->getRequest()->getParam('back'));
        } elseif ($this->getRequest()->getParam('return_url')) {
            return base64_decode($this->getRequest()->getParam('return_url'));
        }

        return '';
    }

    public function getAlbums()
    {
        return Mage::getModel('mpgallery/album')->getActiveLevelNames();
    }

    public function isAlbumVisible()
    {
        return !$this->isProductPhotoUpload()
        && !$this->isEditMode()
        && !Mage::helper('mpgallery/config')->isPhotoUploadAttachCurrentAlbum();
    }

    public function isPhotoImageVisible()
    {
        return !$this->isEditMode();
    }
}