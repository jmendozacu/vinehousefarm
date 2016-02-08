<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Source_Services extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();

        $signature = Mage::helper('ukmail')->getConfigValue('signature_service');

        $services = Mage::getModel('ukmail/carrier')->getDeliveryServiceCodes();

        foreach ($services as $service => $values) {

            $service_value = array();

            foreach ($values as $title => $value) {

                if (!$value[$signature - 1]) {
                    continue;
                }

                $service_value[] = array(
                    'value' => $value[$signature - 1],
                    'label' => Mage::helper('ukmail')->__($title)
                );
            }

            $options[] = array(
                'value' => $service_value,
                'label' => Mage::helper('ukmail')->__($service)
            );
        }

        return $options;
    }
}