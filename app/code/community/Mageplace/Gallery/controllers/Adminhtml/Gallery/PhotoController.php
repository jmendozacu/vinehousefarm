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
class Mageplace_Gallery_Adminhtml_Gallery_PhotoController extends Mage_Adminhtml_Controller_Action
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

    protected function _initAction($breadcrumb = null, $title = null)
    {
        $galeryTitle = $this->__('Gallery');
        $albumsTitle = $this->__('Photos');

        $this
            ->loadLayout()
            ->_setActiveMenu('mpgallery/photos')
            ->_addBreadcrumb($galeryTitle, $galeryTitle)
            ->_addBreadcrumb($albumsTitle, $albumsTitle);

        if (null !== $breadcrumb) {
            $this->_addBreadcrumb($breadcrumb, $breadcrumb);
        }

        if (method_exists($this, '_title')) {
            $this
                ->_title($galeryTitle)
                ->_title($albumsTitle);

            if (null !== $title) {
                $this->_title($title);
            }
        }

        return $this;
    }

    public function indexAction()
    {
        if ($this->getRequest()->getParam('ajax')) {
            return $this->_forward('grid');
        }

        $this->_initAction($this->__('Manage Photos'), $this->__('Manage Photos'))
            ->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mpgallery/adminhtml_photo_grid')->toHtml()
        );
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function addAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $photo = $this->_initPhoto();

        if (!$photo) {
            return;
        }

        $data = $this->_getSession()->getPhotoData();
        if (!empty($data)) {
            $photo->setData($data);
            $this->_getSession()->unsPhotoData();
        }

        $tab = $this->getRequest()->getParam('tab');

        $title = $photo->getId() ? $this->__('Edit Photo') : $this->__('New Photo');
        $this->_initAction($title, $title);

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()
                ->getBlock('head')
                ->setCanLoadTinyMce(true);
        }

        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($position = $this->getRequest()->getParam('position')) {
            return $this->_forward('massSave');
        }

        if ($post = $this->getRequest()->getPost()) {
            try {
                if (empty($post['photo_id'])) {
                    unset($post['photo_id']);
                }
                $photo = Mage::getModel('mpgallery/photo');
                $photo->setData($post);
                $photo->save();

                $this->_getSession()->addSuccess($this->__('Photo was successfully saved'));
                $this->_getSession()->setFormData(false);

                $id = $photo->getId();
                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect('*/*/edit', array('_current' => true, 'id' => $id));
                } else {
                    return $this->_redirect('*/*/index', array('_current' => true, 'id' => $id));
                }
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()
                    ->addError($e->getMessage())
                    ->setPhotoData($post);
            }
        }

        $this->_redirect('*/*/edit', array('_current' => true));
    }

    public function massSaveAction()
    {
        $this->_redirect('*/*/index', array('_current' => true));

        $albumId = (int)$this->getRequest()->getParam('album_id');
        if ($albumId < 1) {
            $this->_getSession()->addError($this->__('Please select album first'));

            return;
        }

        $albumIds[] = $albumId;

        $positions = $this->getRequest()->getParam('position');
        if (empty($positions)) {
            $this->_getSession()->addError($this->__('Please enter position(s)'));

            return;
        }

        if (!is_array($positions)) {
            $positions = array($this->getRequest()->getParam('id') => $positions);
        }

        $count = 0;
        foreach ($positions as $photoId => $position) {
            try {
                $photo = Mage::getModel('mpgallery/photo')->load($photoId);
                if ($photo->getId()) {
                    $photo->setData('only_current_album_ids', true);
                    $photo->setData('album_ids', $albumIds);
                    $photo->setData('positions', array($albumId => $position));
                    $photo->save();
                    ++$count;
                }
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }

        if ($count > 0) {
            $this->_getSession()->addSuccess($this->__('Total of %d position(s) were updated', $count));
        }
    }

    public function deleteAction()
    {
        $this->_redirect('*/*/index');

        if ($id = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('mpgallery/photo')
                    ->load($id)
                    ->delete();

                $this->_getSession()->addSuccess($this->__('Photo was successfully deleted'));

                return;

            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());

                return $this->_redirect('*/*/edit', array('id' => $id));
            }
        }

        Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find a Photo to delete'));
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('phototable');
        if (!is_array($ids)) {
            $this->_getSession()->addError(Mage::helper('catalog')->__('Please select items.'));
        } else {
            $count = 0;
            foreach ($ids as $id) {
                try {
                    Mage::getModel('mpgallery/photo')
                        ->load($id)
                        ->delete();

                    ++$count;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                }
            }

            $this->_getSession()->addSuccess(
                Mage::helper('adminhtml')->__('Total of %d record(s) were deleted', $count)
            );
        }

        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $ids    = $this->getRequest()->getParam('phototable');
        $status = (int)$this->getRequest()->getParam('status');
        if (!is_array($ids)) {
            $this->_getSession()->addError(Mage::helper('catalog')->__('Please select items.'));
        } else {
            $count = 0;
            foreach ($ids as $id) {
                try {
                    Mage::getModel('mpgallery/photo')
                        ->load($id)
                        ->setIsActive($status)
                        ->save();

                    ++$count;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                }
            }

            $this->_getSession()->addSuccess(
                Mage::helper('adminhtml')->__('Total of %d record(s) have been updated.', $count)
            );
        }

        $this->_redirect('*/*/index');
    }

    public function massMoveAction()
    {
        $ids   = $this->getRequest()->getParam('phototable');
        $album = (int)$this->getRequest()->getParam('album');
        if (!is_array($ids)) {
            $this->_getSession()->addError(Mage::helper('catalog')->__('Please select items.'));
        } elseif (!$album) {
            $this->_getSession()->addError($this->__('Please select album.'));
        } else {
            $count = 0;
            foreach ($ids as $id) {
                try {
                    Mage::getModel('mpgallery/photo')
                        ->load($id)
                        ->setAlbumIds($album)
                        ->save();

                    ++$count;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                }
            }

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been moved.', $count)
            );
        }

        $this->_redirect('*/*/index');
    }

    public function massCopyAction()
    {
        $ids   = $this->getRequest()->getParam('phototable');
        $album = (int)$this->getRequest()->getParam('album');
        if (!is_array($ids)) {
            $this->_getSession()->addError(Mage::helper('catalog')->__('Please select items.'));
        } elseif (!$album) {
            $this->_getSession()->addError($this->__('Please select album.'));
        } else {
            $count = 0;
            foreach ($ids as $id) {
                try {
                    $photo = Mage::getModel('mpgallery/photo')->load($id);

                    $albumIds = $photo->getAlbumIds();
                    if (!is_array($albumIds)) {
                        $albumIds = array();
                    }

                    if (!in_array($album, $albumIds)) {
                        $albumIds[] = $album;
                        $photo->setAlbumIds($albumIds)->save();
                        ++$count;
                    }
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                }
            }

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been copied.', $count)
            );
        }

        $this->_redirect('*/*/index');
    }


    public function albumsAction()
    {
        $this->_initPhoto();

        $this->loadLayout();
        $this->renderLayout();
    }

    public function albumsJsonAction()
    {
        $this->_initPhoto();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mpgallery/adminhtml_photo_edit_tab_albums')
                ->getAlbumChildrenJson($this->getRequest()->getParam('album'))
        );
    }

    public function stateAction()
    {
        if ($this->getRequest()->getParam('isAjax') && $this->getRequest()->getParam('container')) {
            $container      = $this->getRequest()->getParam('container');
            $containerValue = (int)$this->getRequest()->getParam('value');

            Mage::getSingleton('mpgallery/session')->setFieldsetState($container, $containerValue);

            $this->getResponse()->setBody('success');
        }
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(Mageplace_Gallery_Helper_Const::ACL_PATH_PHOTOS);
    }
}