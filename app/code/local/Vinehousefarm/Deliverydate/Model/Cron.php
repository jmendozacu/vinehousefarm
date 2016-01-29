<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Model_Cron
{
    public function futureOrders()
    {
        $collection = Mage::getResourceModel('sales/order_grid_collection')
            ->addAttributeToFilter('status', array('in' => array(Vinehousefarm_Deliverydate_Helper_Data::STATUS_ORDER_DELIVERY_DATE)));

        foreach ($collection as $item) {
            $order = Mage::getModel('sales/order')->load($item->getId());
            if ($order->getShippingArrivalDate()) {
                $currentTimestamp = Mage::getModel('core/date')->timestamp(time());
                $orderTimestamp = strtotime($order->getShippingArrivalDate());

                if (($orderTimestamp - $currentTimestamp) < 86400) {
                    $order->addStatusHistoryComment('Move to Awaiting Authorisation.');
                    $order->setStatus(Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_AUTHORISE);
                    $order->save();
                }
            }
        }
    }

    /**
     * @return Vinehousefarm_Authoriselist_Helper_Data
     */
    protected function getHelper()
    {
        $helper = Mage::helper('authoriselist');
        return $helper;
    }
}