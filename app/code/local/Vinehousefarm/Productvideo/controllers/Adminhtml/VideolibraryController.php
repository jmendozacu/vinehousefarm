<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Productvideo_Adminhtml_VideolibraryController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('admin/video')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Video Library'), Mage::helper('adminhtml')->__('Video Library'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');

        /* @var $model Vinehousefarm_Productvideo_Model_Video */
        $model = Mage::getModel('productvideo/video')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('current_data', $model);

            $this->loadLayout();

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('birdlibrary')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function viewAction()
    {
        $this->_forward('edit');
    }


    /**
     * Product grid for AJAX request
     */
    public function productsAction()
    {
        /* @var $model Vinehousefarm_Productvideo_Model_Video */
        $model = Mage::getModel('productvideo/video');

        $itemId = $this->getRequest()->getParam('id');

        if ($itemId) {
            $model->load($itemId);

            Mage::register('current_data', $model);
        }

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('productvideo/adminhtml_library_edit_tab_product', 'video.tabs.products')
                ->toHtml()
        );
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('main')) {

            /* @var $model Vinehousefarm_Productvideo_Model_Video */
            $model = Mage::getModel('productvideo/video');
            $model->setData($data);

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productvideo')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $data['entity_id']));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productvideo')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                /* @var $model Vinehousefarm_Productvideo_Model_Video */
                $model = Mage::getModel('productvideo/video');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $entityIds = $this->getRequest()->getParam('ids');
        if (!is_array($entityIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($entityIds as $entityId) {
                    /* @var $model Vinehousefarm_Productvideo_Model_Video */
                    $model = Mage::getModel('productvideo/video')->load($entityId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($entityIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName = 'productvideo.csv';
        $content = $this->getLayout()->createBlock('productvideo/adminhtml_video_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'productvideo.xml';
        $content = $this->getLayout()->createBlock('productvideo/adminhtml_video_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return true;
    }
}