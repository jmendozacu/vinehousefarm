<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Adminhtml_ProcessingController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sales')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Sales'), Mage::helper('adminhtml')->__('Sales'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Sales Management'), Mage::helper('adminhtml')->__('Sales Management'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Processing'), Mage::helper('adminhtml')->__('Processing'))
        ;
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
            $this->getLayout()->createBlock('authoriselist/adminhtml_processing_grid')->toHtml()
        );
    }

    public function pickingAction()
    {
        try {
            $orderIds = $this->getRequest()->getParam('order_ids');

            //TODO
            $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('entity_id', $orderIds);

            foreach ($orders as $order) {
                $order->setStatus(Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_PICKING, true);
                $history = $order->addStatusHistoryComment('Order was sent to Picking/Packing.', false);
                $history->setIsCustomerNotified(false);
            }

            $orders->walk('save');

            $this->_redirect('*/*/index');

        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
            $this->_redirect('*/*/index');
        }
    }

    public function pdfadviceslipsAction()
    {
        try {
            $orderIds = $this->getRequest()->getParam('order_ids');

            $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('entity_id', $orderIds);

            $pdf = Mage::helper('authoriselist/orderpreparation_pickingList')->getAdviceSlipsPdf($orders ,$type);

            $this->_prepareDownloadResponse('advice_slips.pdf', $pdf->render(), 'application/pdf');
        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
            $this->_redirect('*/*/index');
        }
    }

    public function pdfshipmentsAction()
    {
        try {
            $orderIds = $this->getRequest()->getParam('order_ids');
            $type = $this->getRequest()->getParam('type', 'office');

            $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('entity_id', $orderIds);

            $pdf = Mage::helper('authoriselist/orderpreparation_pickingList')->getPickListPdf($orders, $type);

            $this->_prepareDownloadResponse('picking_list.pdf', $pdf->render(), 'application/pdf');
        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
            $this->_redirect('*/*/index');
        }
    }

    public function printlabelsAction()
    {
        try {
            $orderIds = $this->getRequest()->getParam('order_ids');

            $orderIdsIgnore = array(0 => 0);
            $allLabels = array();

            $orderLabels = Mage::getModel('ukmail/label')->getCollection()
                ->addFieldToFilter('order_id', $orderIds);

            foreach ($orderLabels as $orderLabel) {
                $orderIdsIgnore[$orderLabel->getOrderId()] = $orderLabel->getOrderId();
                $allLabels[$orderLabel->getOrderId()] = Mage::helper('ukmail/label')->getOrderLabels($orderLabel);
            }

            $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('entity_id',array('in' => $orderIds))
                ->addFieldToFilter('entity_id',array('nin' => $orderIdsIgnore));

            $allLabels = $this->getServiceLabels($orders, $allLabels);

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
     * Mail send to Drop Ship.
     */
    public function dropshipsentAction()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');

            if ($orderId) {
                $order = Mage::getModel('sales/order')->load($orderId);

                $result = Mage::helper('authoriselist')->notify($order);

                if ($result['dropships']) {
                    $messageDrophips = array();

                    foreach ($result['dropships'] as $dropship) {
                        $messageDrophips[] = $dropship['name'] . ' (' . $dropship['email'] . ')';
                    }

                    $message = implode(',', $messageDrophips);

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Emails were sent to %s', $message));
                }
            }

            $this->_redirect('*/*/index');

        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
            $this->_redirect('*/*/index');
        }
    }

    /**
     * Mail send to Supplier.
     */
    public function suppliersentAction()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');

            if ($orderId) {
                $order = Mage::getModel('sales/order')->load($orderId);
                $suppliers = array();

                foreach ($order->getAllItems() as $item) {
                    //TODO need refactoring
                    /**
                     * @var $product Mage_Catalog_Model_Product
                     */
                    $product = Mage::getModel('catalog/product')->load($item->getProductId());

                    if ($product->getSupplier()) {
                        $suppliers[$product->getSupplier()] = $product->getSupplier();
                    }
                }

                foreach ($suppliers as $supplier) {
                    $result = Mage::helper('authoriselist')->notifySupplier($order, $supplier);

                    if ($result['suppliers']) {
                        $resultSuppliers[] = $result['suppliers'];
                    }
                }

                if ($suppliers) {

                    $messageSuppliers = array();

                    foreach ($resultSuppliers as  $supplier) {
                        $messageSuppliers[] = $supplier[0]['name'] . ' (' . $supplier[0]['email'] . ')';
                    }

                    $message = implode(',', $messageSuppliers);

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Emails were sent to %s', $message));
                }
            }

            $this->_redirect('*/*/index');

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

            foreach ($orders as $order) {

                if (!$order->getShippingLabels()) {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('It does not indicate the number of labels by order : %s', $order->getIncrementId()));
                    continue;
                }

                $book = Mage::getModel('ukmail/service_book');

                $book->setOrder($order);
                $bookCollection = $book->getBookCoolection();

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