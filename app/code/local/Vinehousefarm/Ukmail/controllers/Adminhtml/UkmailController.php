<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Ukmail_Adminhtml_UkmailController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('admin/sales/ukmail')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('UK Mail'), Mage::helper('adminhtml')->__('UK Mail Labels'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=> true));
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('ukmail/adminhtml_labels_grid')->toHtml()
        );
    }

    public function cancelAction()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');

            $allLabels = array();

            $orderLabels = Mage::getModel('ukmail/label')->getCollection()
                ->addFieldToFilter('entity_id', $orderId);

            $service = Mage::getModel('ukmail/service_cancel');

            foreach ($orderLabels as $orderLabel) {
                $service->setLabel($orderLabel);
                $service->cancelReturn();
            }

            $this->_redirect('*/*/index');

        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
            $this->_redirect('*/*/index');
        }
    }

    public function labelsAction()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');

            $allLabels = array();

            $orderLabels = Mage::getModel('ukmail/label')->getCollection()
                ->addFieldToFilter('entity_id', $orderId);

            foreach ($orderLabels as $orderLabel) {
                $orderIdsIgnore[$orderLabel->getOrderId()] = $orderLabel->getOrderId();
                $allLabels[$orderLabel->getOrderId()] = Mage::helper('ukmail/label')->getOrderLabels($orderLabel);
            }

            $pdf = Mage::helper('ukmail/label')->getLabelPdf($allLabels);

            if (!is_null($pdf)) {
                $this->_prepareDownloadResponse('labels-' . uniqid() . '.pdf', $pdf->render(), 'application/pdf');
            }

            //$this->_redirect('*/*/index');

        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
            $this->_redirect('*/*/index');
        }
    }

    /**
     *
     *
     * @param unknown_type $fileName
     * @param unknown_type $content
     * @param unknown_type $contentType
     */
    protected function _prepareDownloadResponse($fileName, $content, $contentType = 'application/octet-stream', $contentLength = null)
    {
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true)
            ->setHeader('Content-Length', strlen($content))
            ->setHeader('Content-Disposition', 'attachment; filename=' . $fileName)
            ->setBody($content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/authoriselist/processing');
    }

    /**
     * @param $orders
     * @param $allLabels
     * @return mixed
     */
    protected function getServiceLabels($orders, $allLabels)
    {
        if ($orders->getAllIds()) {

            $book = Mage::getModel('ukmail/service_book');

            $bookCollection = $book->getBookCoolection();

            foreach ($orders as $order) {

                if (!$order->getShippingLabels()) {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('It does not indicate the number of labels by order : %s', $order->getIncrementId()));
                    continue;
                }

                $label = Mage::getModel('ukmail/service_label');

                $label->setBookCollection($bookCollection);
                $label->setOrder($order);

                $label->getLabel();

                if (count($label->getErrors())) {
                    foreach ($label->getErrors() as $error) {
                        Mage::getSingleton('adminhtml/session')->addError($this->__('%s : %s', $order->getIncrementId(), $error));
                    }
                } else {

                    $files = Mage::helper('ukmail/label')->save($label);

                    if (count($files)) {
                        $allLabels[$order->getId()] = $files;
                    }
                }
            }
            return $allLabels;
        }
        return $allLabels;
    }
}