<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Adminhtml_FutureorderController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('admin/sales/future_dispatch_date')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Future Dispatch Date'), Mage::helper('adminhtml')->__('Future Dispatch Date'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/future_dispatch_date');
    }
}