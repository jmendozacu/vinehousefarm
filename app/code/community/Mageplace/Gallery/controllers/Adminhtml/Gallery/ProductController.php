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
 * Class Mageplace_Gallery_Adminhtml_Gallery_PhotoController
 */
class Mageplace_Gallery_Adminhtml_Gallery_ProductController extends Mage_Adminhtml_Controller_Action
{
    protected function _initPhoto()
    {
        $photo = Mage::getModel('mpgallery/photo');

        if (!$photoId = (int)$this->getRequest()->getParam('photo_id')) {
            $photoId = (int)$this->getRequest()->getParam('id');
        }

        if ($photoId > 0) {
            $photo->load($photoId);
            if (!$photo->getId()) {
                $this->_getSession()->addError($this->__('This photo does not exist'));
                $this->_redirect('*/*/index');

                return false;
            }
        }

        Mage::register('photo', $photo);
        Mage::register('current_photo', $photo);

        return $photo;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }


    public function albumJsonAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mpgallery/adminhtml_catalog_product_edit_tab_albums')
                ->getAlbumChildrenJson($this->getRequest()->getParam('album'))
        );
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(Mageplace_Gallery_Helper_Const::ACL_PATH_PRODUCT);
    }
}