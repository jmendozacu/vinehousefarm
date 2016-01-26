<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Common_Model_Adminhtml_System_Config_Source_Shipping_Methods
{
    protected $_options;

    public function toOptionArray()
    {
        $carriers = Mage::getSingleton('shipping/config')->getAllCarriers();

        $carriersActive = Mage::getSingleton('shipping/config')->getActiveCarriers();
        $carriersActive = array_keys($carriersActive);

        if (!$this->_options) {
            foreach ($carriers as $carrier) {
                $carrierCode = $carrier->getId();
                $carrierTitle = Mage::getStoreConfig('carriers/' . $carrierCode . '/title', Mage::app()->getStore()->getId());
                $carrierTitle = trim($carrierTitle);

                if (empty($carrierTitle)) {
                    continue;
                }

                if (in_array($carrierCode, $carriersActive)) {
                    $carrierTitle = sprintf('%s (currently active)', $carrierTitle);
                } else {
                    $carrierTitle = sprintf('%s (currently inactive)', $carrierTitle);
                }

                $this->_options[] = array('value' => $carrierCode, 'label' => $carrierTitle);
            }
        }

        $options = $this->_options;

        array_unshift($options, array('value' => '', 'label' => ''));

        return $options;
    }
}