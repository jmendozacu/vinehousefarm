<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Model_SalesOrderPlanning_Planning extends MDN_SalesOrderPlanning_Model_Planning
{
    const ENTITY_DAY = 86400;

    /**
     * Set shipping information
     *
     */
    public function setShippingInformation($order, $quoteMode = false)
    {
        if ($order->getShippingArrivalDate()) {
            $shippingDate = Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave($order->getShippingArrivalDate());
            $shippingDateTimestamp = strtotime($shippingDate) - self::ENTITY_DAY;

            $holidayHelper = mage::helper('SalesOrderPlanning/Holidays');
            $shippingDateTimeStamp = $holidayHelper->addDaysWithoutHolyDays($shippingDateTimestamp, 0);

            $shippingComments .= mage::helper('SalesOrderPlanning')->__('Shipping date estimated to %s <br>', date('Y-m-d', $shippingDateTimeStamp));
            $this->setpsop_shipping_date(date('Y-m-d', $shippingDateTimeStamp));
            $this->setpsop_shipping_date_max(date('Y-m-d', $shippingDateTimeStamp));
            $this->setpsop_shipping_comments($shippingComments);

            return $this;
        }

        $shippingDateTimeStamp = null;
        $maxDateTimestamp = null;
        $shippingComments = null;

        //get shipment for order
        $shipmentDate = null;
        $shipments = $order->getShipmentsCollection();
        if ($shipments)
        {
            foreach ($shipments as $shipment)
            {
                if ($shipmentDate == null)
                    $shipmentDate = $shipment->getCreatedAt();
            }
            if ($shipmentDate != null)
            {
                $shippingComments .= mage::helper('SalesOrderPlanning')->__('Order shipped on %s <br>', $shipmentDate);
                $this->setpsop_shipping_date($shipmentDate);
                $this->setpsop_shipping_comments($shippingComments);
                return $this;
            }
        }

        //if no fullstock date, do not compute
        $fullstockDate = $this->getFullstockDate();
        $fullstockDateTimestamp = strtotime($fullstockDate);
        if ($fullstockDate == null)
        {
            $this->setpsop_shipping_date(null);
            $this->setpsop_shipping_date_max(null);
            $this->setpsop_shipping_comments('');
            return $this;
        }

        //add preparation duration
        $orderPreparationDuration = $this->getPreparationDurationForOrder($order, $quoteMode);

        //avoid holy day (if set)
        if (Mage::getStoreConfig('planning/shipping/avoid_holy_days') == 1)
        {
            $holidayHelper = mage::helper('SalesOrderPlanning/Holidays');
            $shippingDateTimeStamp = $holidayHelper->addDaysWithoutHolyDays(strtotime($fullstockDate), $orderPreparationDuration);
            $shippingComments .= mage::helper('SalesOrderPlanning')->__('add %s days to prepare order and avoid holidays<br>', $orderPreparationDuration);
        }
        else
        {
            $shippingDateTimeStamp = strtotime($fullstockDate) + $orderPreparationDuration * 3600 * 24;
            $shippingComments .= mage::helper('SalesOrderPlanning')->__('add %s days to prepare order<br>', $orderPreparationDuration);
        }

        //add security (max date)
        $mode = Mage::getStoreConfig('planning/shipping/maxdate_calculation_mode');
        $value = Mage::getStoreConfig('planning/shipping/maxdate_calculation_value');
        $diff = $orderPreparationDuration * 3600 * 24;
        $newDiff = 0;
        if ($value > 0)
        {
            switch ($mode)
            {
                case 'days':
                    $newDiff += $diff + $value * 3600 * 24;
                    $shippingComments .= mage::helper('SalesOrderPlanning')->__('add %s days to calculate max date<br>', $value);
                    break;
                case 'percent':
                    $newDiff += $diff * (1 + $value / 100);
                    $shippingComments .= mage::helper('SalesOrderPlanning')->__('add %s % to calculate max date<br>', $value);
                    break;
            }
        }
        $maxDateTimestamp = strtotime($this->getpsop_fullstock_date_max()) + $newDiff;

        //avoid holy day for max date (if set)
        if (Mage::getStoreConfig('planning/shipping/avoid_holy_days') == 1)
        {
            $daysToAdd = $this->DaysUntilNotHolyDay($maxDateTimestamp);
            if ($daysToAdd > 0)
            {
                $maxDateTimestamp += 3600 * 24 * $daysToAdd;
            }
        }


        //store values
        if ($shippingDateTimeStamp != null)
        {
            $shippingComments .= mage::helper('SalesOrderPlanning')->__('Shipping date estimated to %s <br>', date('Y-m-d', $shippingDateTimeStamp));
            $this->setpsop_shipping_date(date('Y-m-d', $shippingDateTimeStamp));
            $this->setpsop_shipping_date_max(date('Y-m-d', $maxDateTimestamp));
            $this->setpsop_shipping_comments($shippingComments);
        }

    }

    /**
     * Set delivery information
     *
     * @param unknown_type $order
     */
    public function setDeliveryInformation($order, $quoteMode = false)
    {
        if ($order->getShippingArrivalDate()) {
            $shippingDate = Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave($order->getShippingArrivalDate());
            $shippingDateTimestamp = strtotime($shippingDate);

            $holidayHelper = mage::helper('SalesOrderPlanning/Holidays');
            $deliveryDateTimeStamp = $holidayHelper->addDaysWithoutHolyDays($shippingDateTimestamp, 0);

            $this->setpsop_delivery_date(date('Y-m-d', $deliveryDateTimeStamp));
            $this->setpsop_delivery_date_max(date('Y-m-d', $deliveryDateTimeStamp));
            $this->setpsop_delivery_comments($order->getShippingArrivalComments());

            return $this;
        }

        $deliveryDateTimeStamp = null;
        $maxDateTimestamp = null;
        $deliveryComments = null;

        //if no shipping date, do not compute
        $shippingDate = $this->getShippingDate();
        $shippingDateTimestamp = strtotime($shippingDate);
        if ($shippingDate == null) {
            $this->setpsop_delivery_date(null);
            $this->setpsop_delivery_date_max(null);
            $this->setpsop_delivery_comments('');
            return $this;
        }

        //define shipping date
        if (!$quoteMode)
            $carrier = $order->getshipping_method();
        else
            $carrier = $order->getShippingAddress()->getShippingMethod();
        $country = '';
        if ($order->getShippingAddress() != null)
            $country = $order->getShippingAddress()->getcountry();
        $shippingDelay = mage::helper('SalesOrderPlanning/ShippingDelay')->getShippingDelayForCarrier($carrier, $country);

        //avoid holy day (if set)
        if (Mage::getStoreConfig('planning/delivery/avoid_holy_days') == 1) {
            $holidayHelper = mage::helper('SalesOrderPlanning/Holidays');
            $deliveryDateTimeStamp = $holidayHelper->addDaysWithoutHolyDays($shippingDateTimestamp, $shippingDelay);
            $deliveryComments .= 'add ' . $shippingDelay . ' days for shipping delay with ' . $carrier . ' to ' . $country . ' and avoid holidays<br>';
        } else {
            $deliveryDateTimeStamp = $shippingDateTimestamp + $shippingDelay * 3600 * 24;
            $deliveryComments .= 'add ' . $shippingDelay . ' days for shipping delay with ' . $carrier . ' to ' . $country . '<br>';
        }

        //add security (max date)
        $mode = Mage::getStoreConfig('planning/delivery/maxdate_calculation_mode');
        $value = Mage::getStoreConfig('planning/delivery/maxdate_calculation_value');
        $diff = $deliveryDateTimeStamp - $shippingDateTimestamp;
        $newDiff = 0;
        if ($value > 0) {
            switch ($mode) {
                case 'days':
                    $newDiff += $diff + $value * 3600 * 24;
                    $deliveryComments .= mage::helper('SalesOrderPlanning')->__('add %s days to calculate max date<br>', $value);
                    break;
                case 'percent':
                    $newDiff += $diff * (1 + $value / 100);
                    $deliveryComments .= mage::helper('SalesOrderPlanning')->__('add %s % to calculate max date<br>', $value);
                    break;
            }
        }
        $maxDateTimestamp = strtotime($this->getpsop_shipping_date_max()) + $newDiff;


        //store values
        if ($deliveryDateTimeStamp != null) {
            $deliveryComments .= mage::helper('SalesOrderPlanning')->__('Delivery date estimated to %s <br>', date('Y-m-d', $deliveryDateTimeStamp));
            $this->setpsop_delivery_date(date('Y-m-d', $deliveryDateTimeStamp));
            $this->setpsop_delivery_date_max(date('Y-m-d', $maxDateTimestamp));
            $this->setpsop_delivery_comments($deliveryComments);

        }

    }
}