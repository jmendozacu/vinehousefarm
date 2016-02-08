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
class Vinehousefarm_Deliverydate_Model_Config_Timeformat extends Mage_Core_Model_Config_Data
{

    /**
     * Get possible sharing configuration options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            'g:i a' => Mage::helper('vinehousefarm_deliverydate')->__('g:i a'),
            'H:i:s' => Mage::helper('vinehousefarm_deliverydate')->__('H:i:s')
        );
    }

}
