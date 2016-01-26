<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Helper_Data extends Mage_Core_Helper_Abstract
{
    const STATUS_ORDER_DELIVERY_DATE = 'delivery_date';

    public function getFormatedDeliveryDate($date = null)
    {
        //if null or 0-0-0 00:00:00 return no date string
        if (empty($date) || $date == null || $date == '0000-00-00') {
            return Mage::helper('vinehousefarm_deliverydate')->__("No Delivery Date Specified.");
        }

        //Format Date
        $formatedDate = Mage::helper('core')->formatDate($date, 'short');
        //TODO: check that date is valid before passing it back

        return $formatedDate;
    }

    public function getFormatedDeliveryDateToSave($date = null)
    {
        if (empty($date) || $date == null || $date == '0000-00-00 00:00:00') {
            return null;
        }

        $timestamp = null;
        try {
            //TODO: add Better Date Validation
            $timestamp = strtotime($date);
            $dateArray = explode("-", $date);
            if (count($dateArray) != 3) {
                //invalid date
                return null;
            }
            //die($timestamp."<<");
            //$formatedDate = date('Y-m-d H:i:s', strtotime($timestamp));
            //$formatedDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $dateArray[0], $dateArray[1], $dateArray[2]));
            $formatedDate = date('Y-m-d', strtotime($date));
        } catch (Exception $e) {
            //TODO: email error
            //return null if not converted ok
            return null;
        }

        return $formatedDate;
    }

    public function saveShippingArrivalDate($observer){

        $order = $observer->getEvent()->getOrder();
//        if (Mage::getStoreConfig('vinehousefarm_deliverydate/deliverydate_general/on_which_page')==2){
//            $desiredArrivalDate = Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave(Mage::app()->getRequest()->getParam('shipping_arrival_date'));
//            if (isset($desiredArrivalDate) && !empty($desiredArrivalDate)){
//                $order->setShippingArrivalComments(Mage::app()->getRequest()->getParam('shipping_arrival_comments'));
//                $order->setShippingArrivalDate($desiredArrivalDate);
//            }
//        }else{
            $cart = Mage::getModel('checkout/cart')->getQuote()->getData();
            $desiredArrivalDate = Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave($cart['shipping_arrival_date']);
            $shipping_arrival_comments = $cart['shipping_arrival_comments'];
            if (isset($desiredArrivalDate) && !empty($desiredArrivalDate)){
                $order->setShippingArrivalComments($shipping_arrival_comments);
                $order->setShippingArrivalDate($desiredArrivalDate);
            }
//        }
    }

    public function saveShippingArrivalDateAdmin($observer){

        $order = $observer->getEvent()->getOrder();
        $cart = Mage::app()->getRequest()->getParams();
        $desiredArrivalDate = Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave($cart['shipping_arrival_date_display']);
        $shipping_arrival_comments = $cart['shipping_arrival_comments'];
        if (isset($desiredArrivalDate) && !empty($desiredArrivalDate)){
            $order->setShippingArrivalComments($shipping_arrival_comments);
            $order->setShippingArrivalDate($desiredArrivalDate);
        }

    }
}