<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Adminhtml_DeliverydateController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('admin/deliverydate')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function deliverychangeAction()
    {
        $deliverydate = $this->getRequest()->getParam('deliverydate', '');
        $deliveryorder = $this->getRequest()->getParam('dateorder', 0);

        if ($deliverydate && $deliveryorder) {
            $order = Mage::getModel('sales/order')->load($deliveryorder);

            if ($order->getId()) {
                $arrivalDate = Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave(str_replace('/','-',$deliverydate));
                $order->setShippingArrivalDate($arrivalDate);

                $planning = mage::getModel('SalesOrderPlanning/Planning')->getCollection()
                    ->addFieldToFilter('psop_order_id', $order->getId())
                    ->getFirstItem();

                $planning->setShippingInformation($order);
                $planning->setDeliveryInformation($order);

                $planning->save();

                try {
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('vinehousefarm_deliverydate')->__('Delivery date changed.'));
                    $order->save();
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vinehousefarm_deliverydate')->__('Item does not exist'));
                }
            }
        }

        $this->_redirect('adminhtml/sales_order/view', array('order_id' => $deliveryorder));
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('vinehousefarm_deliverydate/deliverydate')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('deliverydate_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('deliverydate/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('vinehousefarm_deliverydate/adminhtml_deliverydate_edit'))
                ->_addLeft($this->getLayout()->createBlock('vinehousefarm_deliverydate/adminhtml_deliverydate_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('deliverydate')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $model = Mage::getModel('vinehousefarm_deliverydate/deliverydate');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('vinehousefarm_deliverydate')->__('Item was successfully saved'));
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
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vinehousefarm_deliverydate')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('vinehousefarm_deliverydate/deliverydate');

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
        $deliverydateIds = $this->getRequest()->getParam('deliverydate');
        if (!is_array($deliverydateIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($deliverydateIds as $deliverydateId) {
                    $deliverydate = Mage::getModel('vinehousefarm_deliverydate/deliverydate')->load($deliverydateId);
                    $deliverydate->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($deliverydateIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $deliverydateIds = $this->getRequest()->getParam('deliverydate');
        if (!is_array($deliverydateIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($deliverydateIds as $deliverydateId) {
                    $deliverydate = Mage::getSingleton('vinehousefarm_deliverydate/deliverydate')
                        ->load($deliverydateId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($deliverydateIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName = 'deliverydate.csv';
        $content = $this->getLayout()->createBlock('vinehousefarm_deliverydate/adminhtml_deliverydate_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'deliverydate.xml';
        $content = $this->getLayout()->createBlock('vinehousefarm_deliverydate/adminhtml_deliverydate_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}