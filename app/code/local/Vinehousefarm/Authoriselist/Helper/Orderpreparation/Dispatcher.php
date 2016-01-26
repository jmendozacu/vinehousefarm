<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Processing_Helper_Orderpreparation_Dispatcher extends MDN_Orderpreparation_Helper_Dispatcher
{
    /**
     * Dispatch order in fullstock / stockless / ignored tab
     */
    public function DispatchOrder($order) {

        //delete old record(s)
        $debug = '##Dispatch order #' . $order->getId();
        $this->removeOrderFromOrderToPreparePending($order);

        //status check is  Mage_Sales_Model_Order::STATE_CANCELED
        $orderState = $order->getstatus();
        if (($orderState == Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_AUTHORISE))
        {
            $debug .= ', state '.$orderState.' is not supported in order preparation';
            return $debug;
        }

        $debug = parent::DispatchOrder($order);

        //mage::log($debug);
        return $debug;
    }
}