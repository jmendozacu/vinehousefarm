<?php
/**
 * @package PhpStorm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */ 
class Vinehousefarm_Authoriselist_Model_AdvancedStock_Observer extends MDN_AdvancedStock_Model_Observer
{
    /**
     * Collect orders with stocks_updated = 0 and status not finished (complete or canceled)
     *
     * @return type
     */
    public function getOrdersNotYetConsidered(){

        $collection = mage::getModel('sales/order')
            ->getCollection()
            ->addFieldToFilter('stocks_updated', '0')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('state', array('nin' => array('complete', 'canceled')))
            ->addAttributeToFilter('status', array('nin' => array(Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_PICKING, Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_AUTHORISE, Vinehousefarm_Deliverydate_Helper_Data::STATUS_ORDER_DELIVERY_DATE)));

        return $collection;
    }
}