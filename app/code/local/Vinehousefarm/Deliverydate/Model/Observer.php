<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Model_Observer
{
    public function checkoutControllerOnepageSaveShippingMethod(Varien_Event_Observer $observer)
    {
        //if (Mage::getStoreConfig('vinehousefarm_deliverydate/deliverydate_general/on_which_page') == 1) {
            $request = $observer->getEvent()->getRequest();
            $quote = $observer->getEvent()->getQuote();

            $desiredArrivalDate = Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave($request->getPost('shipping_arrival_date', ''));
            if (isset($desiredArrivalDate) && !empty($desiredArrivalDate)) {
                $quote->setShippingArrivalDate($desiredArrivalDate);
                $quote->setShippingArrivalComments($request->getPost('shipping_arrival_comments'));
                $quote->save();
            }
        //}

        return $this;
    }

   public function salesOrderAddressSaveAfter(Varien_Event_Observer $observer)
   {
       Mage::helper('vinehousefarm_deliverydate')->saveShippingArrivalDate($observer);

       return $this;
   }

    public  function salesOrderSaveAfter(Varien_Event_Observer $observer)
    {
        Mage::helper('vinehousefarm_deliverydate')->saveShippingArrivalDateAdmin($observer);

        return $this;
    }
}