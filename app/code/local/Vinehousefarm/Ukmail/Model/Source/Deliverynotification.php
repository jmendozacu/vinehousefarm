<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Source_Deliverynotification extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();

        $options[] = array(
            'value' => 'NonRequired',
            'label' => Mage::helper('ukmail')->__('Non Required')
        );

        $options[] = array(
            'value' => 'Telephone',
            'label' => Mage::helper('ukmail')->__('Telephone')
        );

        $options[] = array(
            'value' => 'Email',
            'label' => Mage::helper('ukmail')->__('Email')
        );

        return $options;
    }
}