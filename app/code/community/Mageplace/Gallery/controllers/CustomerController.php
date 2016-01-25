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
 * Class Mageplace_Gallery_PhotoController
 */
class Mageplace_Gallery_CustomerController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();

        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    public function indexAction()
    {
        $this->_forward('photo');
    }

    public function photosAction()
    {
         if (!Mage::helper('mpgallery/config')->isPhotoUploadEnable()
            || !Mage::helper('mpgallery/config')->isPhotoUploadCustomerView()
        ) {
            return $this->_forward('noRoute');
        }

        try {
            $this->loadLayout();

            $this->_initLayoutMessages('customer/session');
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('mpgallery/session');

            $this->getLayout()->getBlock('head')->setTitle($this->__('My Photos'));

            if ($block = $this->getLayout()->getBlock('customer.account.link.back')) {
                $block->setRefererUrl($this->_getRefererUrl());
            }

            return $this->renderLayout();

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('customer/session')->addError($e->getMessage());
        }

        $this->_redirectUrl(Mage::helper('customer')->getAccountUrl());
    }

    public function uploadformAction()
    {
        if (!Mage::helper('mpgallery/config')->isPhotoUploadEnable()
            || !Mage::helper('mpgallery/config')->isPhotoUploadCustomerView()
        ) {
            return $this->_forward('noRoute');
        }

        Mage::register('mpgallery_customer_photo_upload', true);

        $this->loadLayout();

        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('mpgallery/session');

        $this->renderLayout();
    }

    public function uploadAction()
    {
        if (!Mage::helper('mpgallery/config')->isPhotoUploadEnable()
            || !Mage::helper('mpgallery/config')->isPhotoUploadCustomerView()
        ) {
            return $this->_forward('noRoute');
        }

        Mage::register('mpgallery_customer_photo_upload', true);
        $this->_forward('upload', 'album');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('mpgallery/session');
    }
}