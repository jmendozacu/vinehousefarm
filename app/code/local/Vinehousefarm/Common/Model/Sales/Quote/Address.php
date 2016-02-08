<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Common_Model_Sales_Quote_Address extends Designcoil_MinimumOrder_Model_Sales_Quote_Address
{
    public function getShippingRatesCollection()
    {
        parent::getShippingRatesCollection();

        if (Mage::getDesign()->getArea() === Mage_Core_Model_App_Area::AREA_FRONTEND) {

            $hiddenFrontendShippingMethods = Mage::helper('vinehousefarm_common')->getHiddenFrontendShippingMethods();

            $removeRates = array();

            foreach ($this->_rates as $key => $rate) {
                if (in_array($rate->getCarrier(), $hiddenFrontendShippingMethods)) {
                    $removeRates[] = $key;
                }
            }

            foreach ($removeRates as $key) {
                $this->_rates->removeItemByKey($key);
            }
        }

        return $this->_rates;
    }
}