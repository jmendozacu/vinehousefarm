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
class Mageplace_Gallery_PhotoController extends Mage_Core_Controller_Front_Action
{
    /**
     * @param bool $edit
     *
     * @return bool|Mageplace_Gallery_Model_Photo
     */
    protected function _initPhoto($edit = false)
    {
        if (Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_PHOTO)) {
            return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_PHOTO);
        }

        $photoId = (int)$this->getRequest()->getParam('photo_id', false);

        /** @var Mageplace_Gallery_Model_Photo $photo */
        $photo = Mage::getModel('mpgallery/photo')->load($photoId);
        if (!Mage::helper('mpgallery/photo')->canShow($photo, $edit)) {
            return false;
        }

        $albumId = (int)$this->getRequest()->getParam('album_id', false);
        if (!$albumId) {
            $albumIds = $photo->getAlbumIds();
            if (is_array($albumIds) && in_array(Mageplace_Gallery_Model_Album::TREE_ROOT_ID, $albumIds)) {
                $albumId = Mageplace_Gallery_Model_Album::TREE_ROOT_ID;
            }
        }

        if ($albumId) {
            /** @var Mageplace_Gallery_Model_Album $album */
            $realAlbum = Mage::getModel('mpgallery/album')->load($albumId);
            if ($realAlbum->getId()) {
                $photo->setRealAlbum($realAlbum);
                Mage::register(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM, $realAlbum);
                //var_dump($albumId); die;
                if (Mageplace_Gallery_Model_Album::TREE_ROOT_ID != $albumId) {
                    $albumIds = $realAlbum->getActiveParentAlbumIds();
                    $albumId  = array_pop($albumIds);
                }
                $album = Mage::getModel('mpgallery/album')->load($albumId);
                if ($albumId = $album->getId()) {
                    $photo->setAlbum($album);
                    $this->getRequest()->setParam('album_id', $albumId);
                    Mage::register(Mageplace_Gallery_Helper_Const::CURRENT_ACTIVE_ALBUM, $album);
                }
            }
        } else {
            $realAlbum = $photo->getAlbum();
            if (is_object($realAlbum) && $realAlbum->getId()) {
                $photo->setRealAlbum($realAlbum);
                Mage::register(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM, $realAlbum);
            }
        }

        if (is_object($realAlbum) && $realAlbum->getId()) {
            if (!$order = Mage::getSingleton('mpgallery/session')->getSortOrder(Mageplace_Gallery_Helper_Const::PHOTO)) {
                $order = $photo->getDisplaySettings()->getData('photo_view_list_sort_by');
            }

            if (!$dir = Mage::getSingleton('mpgallery/session')->getSortDir(Mageplace_Gallery_Helper_Const::PHOTO)) {
                $dir = $photo->getDisplaySettings()->getData('photo_view_list_sort_dir');
            }

            $collection = Mage::getResourceModel('mpgallery/photo_collection')
                ->addParentFilter($realAlbum->getId())
                ->addIsActiveFilter()
                ->addStoreFilter()
                ->addCustomerGroupFilter()
                ->addAlbumOrder($order, $dir);

            $photo->setAlbumPhotos($collection);

            Mage::register(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM_PHOTOS, $collection);
        }

        Mage::register(Mageplace_Gallery_Helper_Const::CURRENT_PHOTO, $photo);

        return $photo;
    }

    public function indexAction()
    {
        $this->_forward('view');
    }

    public function viewAction()
    {
        if (null !== $this->getRequest()->getParam('review')) {
            return $this->_forward($this->getRequest()->getParam('review') . 'Review');
        } elseif(null !== $this->getRequest()->getParam('edit')) {
            return $this->_forward('edit');
        } elseif(null !== $this->getRequest()->getParam('delete')) {
            return $this->_forward('delete');
        }

        if ($photo = $this->_initPhoto()) {
            $settings = $photo->getDesignSettings();

            if ($designCustom = $settings->getDesignCustom()) {
                $this->_applyCustomDesign($designCustom);
            }

            $update = $this->getLayout()->getUpdate();
            $update->addHandle('default');

            $this->addActionLayoutHandles();
            $update->addHandle('PHOTO_' . $photo->getId());
            $this->loadLayoutUpdates();
            $this->generateLayoutXml()->generateLayoutBlocks();

            if ($settings->getPageLayout()) {
                $this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
            }

            if ($root = $this->getLayout()->getBlock('root')) {
                $root->addBodyClass('photo-' . $photo->getUrlKey());
            }

            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('mpgallery/session');

            $this->renderLayout();
        } elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }

    public function uploadformAction()
    {
        if (!Mage::helper('mpgallery/photo')->canUpload() || !Mage::helper('mpgallery/config')->isProductPhotoUploadEnable()) {
            return $this->_forward('noRoute');
        }

        $productId = (int)$this->getRequest()->getParam('product_id');
        if (!Mage::helper('catalog/product')->canShow($productId)) {
            $this->_getSession()->addError($this->__('Wrong product'));

            return $this->_redirectUrl(Mage::helper('mpgallery/url')->getGalleryUrl());
        }

        Mage::register('mpgallery_product_photo_upload', true);
        Mage::register('mpgallery_product', Mage::getModel('catalog/product')->load($productId));

        $this->loadLayout();

        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('mpgallery/session');

        $this->renderLayout();
    }

    public function uploadAction()
    {
        if (!Mage::helper('mpgallery/photo')->canUpload() || !Mage::helper('mpgallery/config')->isProductPhotoUploadEnable()) {
            return $this->_forward('noRoute');
        }

        Mage::register('mpgallery_product_photo_upload', true);
        $this->_forward('upload', 'album');
    }

    public function editAction()
    {
        if(!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return $this->_redirectUrl(Mage::helper('customer')->getAccountUrl());
        }

        if (!Mage::helper('mpgallery/photo')->canEdit()) {
            return $this->_forward('noRoute');
        }

        if ($photo = $this->_initPhoto(true)) {
            if(!$photo->isOwner(Mage::getSingleton('customer/session')->getCustomerId())) {
                return $this->_redirectUrl(Mage::helper('mpgallery/url')->getPhotoUrl($photo));
            }

            Mage::register('mpgallery_photo_edit', true);
            if('photo_save' == $this->getRequest()->getParam('edit')) {
                return $this->_forward('upload', 'album');
            } elseif('photo_delete' == $this->getRequest()->getParam('edit')) {
                if(Mage::helper('mpgallery/photo')->canDelete()) {
                    $photo->delete();
                }

                Mage::getSingleton('customer/session')->addSuccess($this->__('Your photo was successfully deleted.'));

                if ($this->getRequest()->getParam('return_url')) {
                    $returnUrl = base64_decode($this->getRequest()->getParam('return_url'));
                } elseif ($this->getRequest()->getParam('back')) {
                    $returnUrl = base64_decode($this->getRequest()->getParam('back'));
                }

                if(!$returnUrl){
                    $returnUrl = Mage::helper('mpgallery/url')->getCustomerPhotoUrl();
                }

                return $this->_redirectUrl($returnUrl);
            }

            $this->loadLayout();

            $this->_initLayoutMessages('customer/session');
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('mpgallery/session');

            $this->renderLayout();
        } elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }

    public function saveReviewAction()
    {
        if (!Mage::helper('mpgallery/photo')->canReview()) {
            return $this->_forward('noRoute');
        }

        if (($photo = $this->_initPhoto()) && ($post = $this->getRequest()->getPost())) {
            $rate    = $this->getRequest()->getPost('rate');
            $name    = strip_tags($this->getRequest()->getPost('name'));
            $email   = $this->getRequest()->getPost('email');
            $comment = strip_tags($this->getRequest()->getPost('comment'));

            if (!Zend_Validate::is($rate, 'Digits')
                || !Zend_Validate::is($name, 'NotEmpty')
                || !Zend_Validate::is($email, 'EmailAddress')
                || !Zend_Validate::is($comment, 'NotEmpty')
            ) {
                $this->_getSession()->addError($this->__('Please fill all required fields'));

                return $this->_redirectUrl(Mage::helper('mpgallery/url')->getPhotoUrl($photo));
            }

            Mage::getModel('mpgallery/review')
                ->setRate($rate)
                ->setName($name)
                ->setEmail($email)
                ->setComment($comment)
                ->setPhotoId($photo->getId())
                ->save();

            $this->_getSession()->addSuccess($this->__('Your review was successfully saved'));

            return $this->_redirectUrl(Mage::helper('mpgallery/url')->getPhotoUrl($photo));
        }

        if (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }

    public function showReviewAction()
    {
        if (!Mage::helper('mpgallery/photo')->canReview()) {
            return $this->getResponse()->setBody('');
        }

        if ($photo = $this->_initPhoto()) {
            $page  = (int)$this->getRequest()->getQuery('page');
            $limit = (int)$this->getRequest()->getQuery('limit');

            return $this->getResponse()->setBody(
                $this->getLayout()->createBlock('mpgallery/review_list')
                    ->setPage($page)
                    ->setLimit($limit)
                    ->setDisplayEmptyMessage(false)
                    ->setDisplayWrapper(false)
                    ->setDisplayShowMoreButton(false)
                    ->toHtml()
            );
        }

        return $this->getResponse()->setBody('');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('mpgallery/session');
    }

    protected function _getAlbum()
    {
        return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM);
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

}