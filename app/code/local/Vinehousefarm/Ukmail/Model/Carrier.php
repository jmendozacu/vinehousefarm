<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Ukmail_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'ukmail';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');
        $result->append($this->_getDefaultRate());

        return $result;
    }

    public function getAllowedMethods()
    {
        return array(
            'ukmail' => $this->getConfigData('name'),
        );
    }

    public function getDeliveryServiceCodes()
    {
        return array(
            'Parcels' => array(
                'Next Day' => array(1, 0, 0),
                'Next Day 09:00' => array(0, 220, 210),
                'Next Day 10:30' => array(9, 222, 212),
                'Next Day 12:00' => array(2, 221, 211),
                'Afternoon' => array(77, 223, 213),
                'Evening' => array(78, 224, 214),
                'Saturday' => array(4, 225, 215),
                'Saturday 09:00' => array(0, 5, 0),
                'Saturday 10:30' => array(7, 226, 216),
                'Timed Delivery' => array(0, 6, 0),
                '48 Hour' => array(48, 0, 0),
                '48 Hour Pack' => array(75, 0, 0),
                '72 Hour' => array(0, 0, 72),
                'Pallet 24 Hours' => array(0, 97, 0),
                'Pallet 48 Hours' => array(0, 98, 0),
                'Collection Point' => array(0, 90, 0),
                'Collection Point 09:00' => array(0, 92, 0),
                'Collection Point 12:00' => array(0, 91, 0),
            ),
            'Bagit Small (&Express)' => array(
                'Next Day' => array(40, 240, 230),
                'Next Day 09:00' => array(0, 42, 0),
                'Next Day 10:30' => array(49, 242, 232),
                'Next Day 12:00' => array(41, 241, 231),
                'Afternoon' => array(243, 0, 233),
                'Evening' => array(244, 0, 234),
                'Saturday' => array(43, 245, 235),
                'Saturday 09:00' => array(0, 45, 0),
                'Saturday 10:30' => array(46, 246, 236),
                'Timed Delivery' => array(0, 45, 0),
                '48 Hour' => array(48, 0, 0),
                '72 Hour' => array(0, 0, 72),
            ),
            'Bagit Medium' => array(
                'Next Day' => array(30, 250, 0),
                'Next Day 09:00' => array(0, 32, 0),
                'Next Day 10:30' => array(39, 252, 0),
                'Next Day 12:00' => array(31, 251, 0),
                'Afternoon' => array(253, 0, 0),
                'Evening' => array(254, 0, 0),
                'Saturday' => array(33, 255, 0),
                'Saturday 09:00' => array(0, 34, 0),
                'Saturday 10:30' => array(36, 256, 0),
                'Timed Delivery' => array(0, 35, 0),
                '48 Hour' => array(48, 0, 0),
                '72 Hour' => array(0, 0, 72),
            ),
            'Bagit Large' => array(
                'Next Day' => array(20, 260, 0),
                'Next Day 09:00' => array(0, 32, 0),
                'Next Day 10:30' => array(39, 252, 0),
                'Next Day 12:00' => array(31, 251, 0),
                'Afternoon' => array(253, 0, 0),
                'Evening' => array(254, 0, 0),
                'Saturday' => array(33, 255, 0),
                'Saturday 09:00' => array(0, 34, 0),
                'Saturday 10:30' => array(36, 256, 0),
                'Timed Delivery' => array(0, 25, 0),
                '48 Hour' => array(48, 0, 0),
                '72 Hour' => array(0, 0, 72),
            ),
            'Bagit XL' => array(
                'Next Day' => array(10, 270, 0),
                'Next Day 09:00' => array(0, 12, 0),
                'Next Day 10:30' => array(19, 272, 0),
                'Next Day 12:00' => array(11, 271, 0),
                'Afternoon' => array(273, 0, 0),
                'Evening' => array(274, 0, 0),
                'Saturday' => array(13, 275, 0),
                'Saturday 09:00' => array(0, 14, 0),
                'Saturday 10:30' => array(16, 276, 0),
                'Timed Delivery' => array(0, 15, 0),
                '48 Hour' => array(48, 0, 0),
                '72 Hour' => array(0, 0, 72),
            )
        );
    }

    protected function _getDefaultRate()
    {
        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod($this->_code);
        $rate->setMethodTitle($this->getConfigData('name'));
        $rate->setPrice($this->getConfigData('price'));
        $rate->setCost(0);

        return $rate;
    }
}