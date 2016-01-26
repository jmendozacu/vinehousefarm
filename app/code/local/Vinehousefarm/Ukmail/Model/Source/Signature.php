<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Source_Signature extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();

        $options[] = array(
            'value' => '1',
            'label' => Mage::helper('ukmail')->__('Signature Service to the specified address & neighbour')
        );

        $options[] = array(
            'value' => '2',
            'label' => Mage::helper('ukmail')->__('Signature Service to the specified address only')
        );

        $options[] = array(
            'value' => '3',
            'label' => Mage::helper('ukmail')->__('Leave Safe at specified address or signature service to neighbour')
        );

        return $options;
    }
}