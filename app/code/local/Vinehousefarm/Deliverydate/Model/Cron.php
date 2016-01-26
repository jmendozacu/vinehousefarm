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

                if (abs($currentTimestamp - $orderTimestamp) < 86400) {
                    $this->getHelper()->setOrder($order);

//                    $this->getHelper()->checkAddress();
//                    $this->getHelper()->checkTradeCustomer();
//                    $this->getHelper()->checkShippingMethods();
//                    $this->getHelper()->checkWeightThreshold();
//                    $this->getHelper()->checkBeforeShipping();
//                    $this->getHelper()->checkLabels();
//
//                    $this->getHelper()->dropshipItem();
//                    $this->getHelper()->supplierItem();

                    if (!$this->getHelper()->isChangeStatus()) {
                        $order->addStatusHistoryComment('Move to Awaiting Authorisation.');
                        $order->setStatus(self::STATUS_ORDER_AUTHORISE);
                        $order->save();
                    }
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