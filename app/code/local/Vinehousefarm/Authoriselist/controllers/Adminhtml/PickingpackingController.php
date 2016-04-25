<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Authoriselist_Adminhtml_PickingpackingController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('admin/authoriselist/pickingpacking')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Picking & Packing'), Mage::helper('adminhtml')->__('Picking & Packing'));
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
            $this->getLayout()->createBlock('authoriselist/adminhtml_pickingpacking_grid')->toHtml()
        );
    }

    public function reasonAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            Mage::register('reason_order', $order);
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    public function unpickingAction()
    {
        try {
            $orderIds = $this->getRequest()->getParam('order_ids');

            $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('entity_id', $orderIds);

            foreach ($orders as $order) {
                $order->setState(Mage_Sales_Model_Order::STATE_NEW, true);

                $reason = $this->getRequest()->getParam('reason');

                if ($reason) {
                    $history = $order->addStatusHistoryComment('Order was set to Pending. Reason: ' . $reason, false);
                } else {
                    $history = $order->addStatusHistoryComment('Order was set to Pending.', false);
                }

                $history->setIsCustomerNotified(false);
            }

            $orders->walk('save');

            $this->_redirect('*/*/index');

        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
            $this->_forward('*/*/index');
        }
    }

    public function completedAction()
    {
        try {
            $orderIds = $this->getRequest()->getParam('order_ids');

            $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('entity_id', $orderIds);

            foreach ($orders as $order) {

                Mage::getModel('Orderpreparation/ordertoprepare')->RemoveSelectedOrder($order->getId(), false);

                //create invoice for the order
                $invoice = $order->prepareInvoice()
                    ->setTransactionId($order->getId())
                    ->addComment("Invoice created.")
                    ->register()
                    ->pay();

                $transaction_save = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());

                $transaction_save->save();
                //now create shipment
                //after creation of shipment, the order auto gets status COMPLETE
                $shipment = $order->prepareShipment();
                if( $shipment ) {
                    $shipment->register();
                    $order->setIsInProcess(true);

                    $transaction_save = Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($shipment->getOrder())
                        ->save();
                }
            }

            $orders->walk('save');

            $this->_redirect('*/*/index');

        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
            $this->_forward('*/*/index');
        }
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/authoriselist/pickingpacking');
    }
}