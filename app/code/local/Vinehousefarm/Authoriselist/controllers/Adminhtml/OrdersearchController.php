<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Authoriselist_Adminhtml_OrdersearchController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('admin/authoriselist/ordersearch')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Order Search'), Mage::helper('adminhtml')->__('Order Search'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/authoriselist/ordersearch');
    }
}