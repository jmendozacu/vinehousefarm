<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

/**
 * Customer sharing config model
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Vinehousefarm_Deliverydate_Model_Config_Dayoff extends Mage_Core_Model_Config_Data
{

    /**
     * Get possible sharing configuration options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $test_array[7] = array(
            'value' => '',
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('No Day'),
        );
        $test_array[0] = array(
            'value' => 0,
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Sunday'),
        );
        $test_array[1] = array(
            'value' => 1,
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Monday'),
        );
        $test_array[2] = array(
            'value' => 2,
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Tuesday'),
        );
        $test_array[3] = array(
            'value' => 3,
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Wedenesday'),
        );
        $test_array[4] = array(
            'value' => 4,
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Thursday'),
        );
        $test_array[5] = array(
            'value' => 5,
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Friday'),
        );
        $test_array[6] = array(
            'value' => 6,
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Saturday'),
        );

        return $test_array;
    }

}
