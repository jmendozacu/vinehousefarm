<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Authoriselist_Adminhtml_AuthoriselistController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('admin/authoriselist/authorisation')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Awaiting Authorisation'), Mage::helper('adminhtml')->__('Awaiting Authorisation'));
        return $this;
    }

    public function indexAction()
    {
        if (!$this->_getHelper()->getCountNext()) {
            $this->_redirect('*/*/empty');
        } else {
            $this->_initAction()
                ->renderLayout();
        }
    }

    public function emptyAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function holdAction()
    {
        $id = $this->getRequest()->getParam('orderid');
        $model = Mage::getModel('sales/order')->load($id);
        $nextUrl = '*/*/index';

        if ($model->getId()) {

            try {
                $model->addStatusHistoryComment('Move to Processing.');
                $model->setState(Mage_Sales_Model_Order::STATE_HOLDED, true);
                $model->save();
            } catch (Exception $ex) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
                $this->_forward('*/*/index');
            }

        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('authoriselist')->__('Item does not exist'));
        }

        if (!$this->_getHelper()->getCountNext()) {
            $nextUrl = '*/*/empty';
        }

        $this->_redirect($nextUrl);
    }

    public function approveAction()
    {
        $id = $this->getRequest()->getParam('orderid');
        $model = Mage::getModel('sales/order')->load($id);
        $nextUrl = '*/*/index';

        if ($model->getId()) {

            try {
                $model->addStatusHistoryComment('Move to Pending.');
                $model->setState(Mage_Sales_Model_Order::STATE_NEW, true);
                $model->save();
            } catch (Exception $ex) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : %s', $ex->getMessage()));
                $this->_forward('*/*/index');
            }

        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('authoriselist')->__('Item does not exist'));
        }

        if (!$this->_getHelper()->getCountNext()) {
            $nextUrl = '*/*/empty';
        }

        $this->_redirect($nextUrl);
    }

    protected function _getHelper()
    {
        return Mage::helper('authoriselist');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/authorisation/moto');
    }
}