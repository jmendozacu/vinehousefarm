<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Common_Model_Shipping_Config extends Mage_Shipping_Model_Config
{
    public function getActiveCarriers($store = null)
    {
        $carriers = parent::getActiveCarriers($store);

        if (Mage::getDesign()->getArea() === Mage_Core_Model_App_Area::AREA_FRONTEND) {

            $carriersCodes = array_keys($carriers);
            $hiddenShippingMethods = Mage::helper('vinehousefarm_common')->getHiddenFrontendShippingMethods();

            foreach ($carriersCodes as $carriersCode) {
                if (in_array($carriersCode, $hiddenShippingMethods)) {
                    unset($carriers[$carriersCode]);
                }
            }
        }

        return $carriers;
    }
}