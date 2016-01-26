<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Model_Status extends Varien_Object
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    public static function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED => Mage::helper('vinehousefarm_deliverydate')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('vinehousefarm_deliverydate')->__('Disabled')
        );
    }
}