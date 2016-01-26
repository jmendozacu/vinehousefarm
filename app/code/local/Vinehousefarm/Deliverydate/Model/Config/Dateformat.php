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
class Vinehousefarm_Deliverydate_Model_Config_Dateformat extends Mage_Core_Model_Config_Data
{

    /**
     * Get possible sharing configuration options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            'd/M/Y' => Mage::helper('vinehousefarm_deliverydate')->__('d/M/Y'),
            'M/d/y' => Mage::helper('vinehousefarm_deliverydate')->__('M/d/y'),
            'd-M-Y' => Mage::helper('vinehousefarm_deliverydate')->__('d-M-Y'),
            'M-d-y' => Mage::helper('vinehousefarm_deliverydate')->__('M-d-y'),
            'm.d.y' => Mage::helper('vinehousefarm_deliverydate')->__('m.d.y'),
            'd.M.Y' => Mage::helper('vinehousefarm_deliverydate')->__('d.M.Y'),
            'M.d.y' => Mage::helper('vinehousefarm_deliverydate')->__('M.d.y'),
            'F j ,Y' => Mage::helper('vinehousefarm_deliverydate')->__('F j ,Y'),
            'D M j' => Mage::helper('vinehousefarm_deliverydate')->__('D M j'),
            'Y-m-d' => Mage::helper('vinehousefarm_deliverydate')->__('Y-m-d')
        );
    }

}
