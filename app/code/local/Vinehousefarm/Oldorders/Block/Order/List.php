<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */

class Vinehousefarm_Oldorders_Block_Order_List extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('vinehousefarm/oldorders/list.phtml');

        $orders = Mage::getModel('oldorders/orders')->getCollection()
            ->addFieldToFilter('customer_id', array('eq' => Mage::getSingleton('customer/session')->getCustomer()->getId()));

        $this->setCollection($orders);

        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('customer')->__('Old Orders'));
    }


    protected function _prepareLayout()
    {
        //   parent::_prepareLayout();
        $this->getLayout()->getBlock('head')
            ->setTitle(Mage::helper('customer')->__('Old Orders'));

        $pager = $this->getLayout()->createBlock('page/html_pager', 'oldorders.history.pager')
            ->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return parent::_prepareLayout();
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('customer/account/', array('_secure' => true));
    }
}