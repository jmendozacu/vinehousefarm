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
 * Class Mageplace_Gallery_AlbumController
 */
class Mageplace_Gallery_AlbumController extends Mage_Core_Controller_Front_Action
{
    /**
     * @return bool|Mageplace_Gallery_Model_Album
     */
    protected function _initAlbum()
    {
        if (null !== Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM)) {
            return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM);
        }

        $albumId = (int)$this->getRequest()->getParam('id', false);
        if ($albumId === 0) {
            return false;
        } elseif (!$albumId) {
            $albumId = Mage::helper('mpgallery/config')->getRootAlbum();
            $this->getRequest()->setParam('id', $albumId);
        }

        /** @var Mageplace_Gallery_Model_Album $album */
        $album = Mage::getModel('mpgallery/album')->load($albumId);

        if (!Mage::helper('mpgallery/album')->canShow($album)) {
            return false;
        }

        Mage::register(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM, $album);
        Mage::register(Mageplace_Gallery_Helper_Const::CURRENT_ACTIVE_ALBUM, $album);

        Mage::getSingleton('mpgallery/session')->setLastVisitedAlbumId($album->getId());

        return $album;
    }

    public function indexAction()
    {
        $this->_forward('view');
    }

    public function viewAction()
    {
        if ($album = $this->_initAlbum()) {
            if (Mage::helper('mpgallery/photo')->canUpload()) {
                $action = strtolower($this->getRequest()->getParam('upload'));
                if ('photo_save' === $action) {
                    return $this->_forward('upload');
                }
            } else {
                $action = null;
            }

            $settings = $album->getDesignSettings();

            if ($designCustom = $settings->getDesignCustom()) {
                $this->_applyCustomDesign($designCustom);
            }

            $update = $this->getLayout()->getUpdate();
            $update->addHandle('default');

            if ($action) {
                $update->addHandle('mpgallery_album_photo_upload');
                $this->addActionLayoutHandles();
                $update->removeHandle('mpgallery_album_view');
            } else {
                if (!$album->hasChildren()) {
                    $update->addHandle('mpgallery_album_nochildren');
                }

                $this->addActionLayoutHandles();
                $update->addHandle('ALBUM_' . $album->getId());
            }

            $this->loadLayoutUpdates();
            $this->generateLayoutXml()->generateLayoutBlocks();

            if ($settings->getPageLayout()) {
                $this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
            }

            if ($root = $this->getLayout()->getBlock('root')) {
                $root->addBodyClass('album-' . $album->getUrlKey() . ($action ? '-upload' : ''));
            }

            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('mpgallery/session');

            $this->renderLayout();
        } elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }

    public function uploadAction()
    {
        $productUpload  = Mage::registry('mpgallery_product_photo_upload');
        $editMode       = Mage::registry('mpgallery_photo_edit');
        $customerUpload = Mage::registry('mpgallery_customer_photo_upload');

        if (!Mage::helper('mpgallery/photo')->canUpload()
            || ($productUpload && !Mage::helper('mpgallery/config')->isProductPhotoUploadEnable())
        ) {
            return $this->_forward('noRoute');
        }

        if ($productUpload || $editMode || $customerUpload || ($album = $this->_initAlbum())) {
            $uploaderName  = strip_tags($this->getRequest()->getPost('uploader_name'));
            $uploaderEmail = strip_tags($this->getRequest()->getPost('uploader_email'));
            $name          = strip_tags($this->getRequest()->getPost('name'));
            $description   = strip_tags($this->getRequest()->getPost('description'));

            if ($this->getRequest()->getPost('return_url')) {
                $returnUrl = base64_decode($this->getRequest()->getPost('return_url'));
            } elseif ($this->getRequest()->getPost('back')) {
                $returnUrl = base64_decode($this->getRequest()->getPost('back'));
            }

            if ($productUpload) {
                $productId = (int)$this->getRequest()->getParam('product_id');
                if (!Mage::helper('catalog/product')->canShow($productId)) {
                    $this->_getSession()->addError($this->__('Wrong product'));

                    return $this->_redirectUrl(Mage::helper('mpgallery/url')->getGalleryUrl());
                }

                if (!isset($returnUrl)) {
                    Mage::helper('mpgallery/url')->getGalleryUrl(array(
                        'upload'     => 'photo',
                        'product_id' => $productId,
                        'back'       => base64_encode(Mage::helper('core/url')->getCurrentUrl())
                    ));
                }

                $errorRedirectUrl = Mage::helper('mpgallery/url')->getGalleryUrl(array(
                    'upload'     => 'photo',
                    'product_id' => $productId,
                    'back'       => base64_encode(Mage::helper('core/url')->getCurrentUrl())
                ));
            } elseif ($editMode) {
                $photo = Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_PHOTO);

                if (!isset($returnUrl)) {
                    $returnUrl = Mage::helper('mpgallery/url')->getCustomerPhotoUrl();
                }
                $errorRedirectUrl = Mage::helper('mpgallery/url')->getPhotoEditUrl($photo);
            } elseif ($customerUpload) {
                if (!isset($returnUrl)) {
                    $returnUrl = Mage::helper('mpgallery/url')->getCustomerPhotoUrl();
                }
                $errorRedirectUrl = Mage::helper('mpgallery/url')->getCustomerPhotoUrl('uploadform');
            } else {
                $errorRedirectUrl = Mage::helper('mpgallery/url')->getAlbumUrl($album, array('upload' => 'photo'));
            }

            if (!Zend_Validate::is($uploaderName, 'NotEmpty')
                || !Zend_Validate::is($uploaderEmail, 'EmailAddress')
                || !Zend_Validate::is($name, 'NotEmpty')
            ) {
                $this->_getSession()->addError($this->__('Please fill all required fields'));

                return $this->_redirectUrl($errorRedirectUrl);
            }

            if ($productUpload) {
                $albumIds = Mage::getModel('mpgallery/album')->getAlbumIdsByProduct($productId);
            } elseif (isset($album) && Mage::helper('mpgallery/config')->isPhotoUploadAttachCurrentAlbum()) {
                $albumIds = $album->getId();
            } elseif (!$editMode) {
                $albumKey = $this->getRequest()->getPost('album');
                if (!$albumKey) {
                    if ($customerUpload && Mage::helper('mpgallery/config')->isPhotoUploadAttachCurrentAlbum()) {
                        $albumIds = array();
                    } else {
                        $this->_getSession()->addError($this->__('Please select album'));

                        return $this->_redirectUrl(Mage::helper('mpgallery/url')->getAlbumUrl($album, array('upload' => 'photo')));
                    }
                } else {
                    $albumIds = Mage::getResourceModel('mpgallery/album')->getAlbumByUrlKey($albumKey);
                    if (!Mage::helper('mpgallery/album')->canShow($albumIds)) {
                        $this->_getSession()->addError($this->__('Wrong album'));

                        return $this->_redirectUrl(Mage::helper('mpgallery/url')->getAlbumUrl($album, array('upload' => 'photo')));
                    }
                }
            }

            if (!$editMode && empty($_FILES['image'])) {
                $this->_getSession()->addError($this->__('Please select image file to upload'));

                return $this->_redirectUrl($errorRedirectUrl);
            } elseif ($editMode && !empty($_FILES)) {
                $this->_getSession()->addError($this->__('Please don\'t try to hack :)'));

                return $this->_redirectUrl($errorRedirectUrl);
            }

            try {
                if (!$editMode) {
                    $photo = Mage::getModel('mpgallery/photo');
                }

                $photo->setName($name)
                    ->setContentHeading($name)
                    ->setAuthorName($uploaderName)
                    ->setAuthorEmail($uploaderEmail)
                    ->setDescription($description)
                    ->setPendingStatus();

                if (!$editMode) {
                    $photo->setAlbumIds($albumIds)
                        ->setImageFile($_FILES['image'])
                        ->setDesignUseParentSettings(1)
                        ->setDisplayUseParentSettings(1)
                        ->setSizeUseParentSettings(1)
                        ->setCustomerId(
                            Mage::getSingleton('customer/session')->isLoggedIn()
                                ? Mage::getSingleton('customer/session')->getCustomerId()
                                : null);
                    if (Mage::app()->getStore() && ($storeId = Mage::app()->getStore()->getStoreId())) {
                        $photo->setStoreId($storeId);
                    }
                }

                $photo->save();

                if (!$editMode) {
                    if ($productUpload) {
                        $singleton = 'catalog';
                    } else {
                        $singleton = 'mpgallery';
                    }

                    Mage::getSingleton($singleton . '/session')->addSuccess($this->__('Your photo was successfully uploaded. Your photo will be published after approval by admin.'));
                } else {

                    Mage::getSingleton('customer/session')->addSuccess($this->__('Your photo was successfully saved. Your photo will be published after approval by admin.'));
                }

                if (!empty($returnUrl)) {
                    return $this->_redirectUrl($returnUrl);
                }

                return $this->_redirectUrl(Mage::helper('mpgallery/url')->getAlbumUrl($album));
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Error during photo upload. Try again later.'));
            }

            return $this->_redirectUrl($errorRedirectUrl);
        }

        if (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }

    protected function _applyCustomDesign($design)
    {
        $design = explode('/', $design);
        if (count($design) != 2) {
            return false;
        }
        $package = $design[0];
        $theme   = $design[1];

        Mage::getSingleton('core/design_package')
            ->setPackageName($package)
            ->setTheme($theme);
    }

    protected function _getSession()
    {
        return Mage::getSingleton('mpgallery/session');
    }
}