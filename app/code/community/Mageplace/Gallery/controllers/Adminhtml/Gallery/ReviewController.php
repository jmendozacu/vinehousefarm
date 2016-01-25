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
 * Class Mageplace_Gallery_Adminhtml_Gallery_ReviewController
 */
class Mageplace_Gallery_Adminhtml_Gallery_ReviewController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction($breadcrumb = null, $title = null)
    {
        $galeryTitle = $this->__('Gallery');
        $reviewTitle = $this->__('Reviews');

        $this
            ->loadLayout()
            ->_setActiveMenu('mpgallery/reviews')
            ->_addBreadcrumb($galeryTitle, $galeryTitle)
            ->_addBreadcrumb($reviewTitle, $reviewTitle);

        if (null !== $breadcrumb) {
            $this->_addBreadcrumb($breadcrumb, $breadcrumb);
        }

        if (method_exists($this, '_title')) {
            $this
                ->_title($galeryTitle)
                ->_title($reviewTitle);

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

        $this->_initAction($this->__('Manage Reviews'), $this->__('Manage Reviews'))
            ->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mpgallery/adminhtml_review_grid')->toHtml()
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
        $review = Mage::getModel('mpgallery/review');

        $id = $this->getRequest()->getParam('review_id');
        if ($id) {
            $review->load($id);
            if (!$review->getId()) {
                $this->_getSession()->addError($this->__('This review does not exist'));
                $this->_redirect('*/*/index');

                return;
            }
        }

        $data = $this->_getSession()->getReviewData();
        if (!empty($data)) {
            $review->setData($data);
            $this->_getSession()->unsPhotoData();
        }

        Mage::register('review', $review);

        $title = $review->getId() ? $this->__('Edit Review') : $this->__('New Review');
        $this->_initAction($title, $title);
        if (!$id) {
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('mpgallery/adminhtml_review_photo_grid'));
        }
        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($post = $this->getRequest()->getPost()) {
            try {
                if (empty($post['review_id'])) {
                    unset($post['review_id']);
                }

                $review = Mage::getModel('mpgallery/review');
                $review->setData($post);
                $review->save();

                $this->_getSession()->addSuccess($this->__('Review was successfully saved'));
                $this->_getSession()->setFormData(false);

                $id = $review->getId();
                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect('*/*/edit', array('_current' => true, 'id' => $id));
                } else {
                    return $this->_redirect('*/*/index', array('_current' => true, 'id' => $id));
                }
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()
                    ->addError($e->getMessage())
                    ->setReviewData($post);
            }
        }

        $this->_redirect('*/*/edit', array('_current' => true));
    }

    public function deleteAction()
    {
        $this->_redirect('*/*/index');

        if ($id = $this->getRequest()->getParam('review_id')) {
            try {
                Mage::getModel('mpgallery/review')
                    ->load($id)
                    ->delete();

                $this->_getSession()->addSuccess($this->__('Review was successfully deleted'));

                return;

            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());

                return $this->_redirect('*/*/edit', array('id' => $id));
            }
        }

        Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find a Review to delete'));
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('reviewtable');
        if (!is_array($ids)) {
            $this->_getSession()->addError(Mage::helper('catalog')->__('Please select items.'));
        } else {
            $count = 0;
            foreach ($ids as $id) {
                try {
                    Mage::getModel('mpgallery/review')
                        ->load($id)
                        ->delete();

                    ++$count;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                }
            }

            $this->_getSession()->addSuccess(
                Mage::helper('adminhtml')->__('Total of %d record(s) were deleted', count($ids))
            );
        }

        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $ids    = $this->getRequest()->getParam('reviewtable');
        $status = (int)$this->getRequest()->getParam('status');
        if (!is_array($ids)) {
            $this->_getSession()->addError(Mage::helper('catalog')->__('Please select items.'));
        } else {
            $count = 0;
            foreach ($ids as $id) {
                try {
                    Mage::getModel('mpgallery/review')
                        ->load($id)
                        ->setStatus($status)
                        ->save();

                    ++$count;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                }
            }

            $this->_getSession()->addSuccess(
                Mage::helper('adminhtml')->__('Total of %d record(s) have been updated.', count($count))
            );
        }

        $this->_redirect('*/*/index');
    }


    public function photoGridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('mpgallery/adminhtml_review_photo_grid')->toHtml());
    }

    public function jsonPhotoInfoAction()
    {
        $response = new Varien_Object();

        $id = $this->getRequest()->getParam('id');
        if (intval($id) > 0) {
            $photo = Mage::getModel('mpgallery/photo')->load($id);

            $response->setId($id);
            $response->setData('name', $photo->getName());
            $response->setData('image_src', Mage::helper('mpgallery/image')->initialize($photo, 'thumbnail')->resizeBySize(Mage::helper('mpgallery/config')->getAdminThumbSize())->__toString());
            $response->setData('edit_url', $this->getUrl('*/gallery_photo/edit', array('id' => $photo->getId())));

            $response->setError(0);
        } else {
            $response->setError(1);
            $response->setMessage($this->__('Unable to get the photo ID.'));
        }

        $this->getResponse()->setBody($response->toJSON());
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(Mageplace_Gallery_Helper_Const::ACL_PATH_REVIEWS);
    }
}