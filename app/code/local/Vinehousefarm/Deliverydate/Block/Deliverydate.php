<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Block_Deliverydate extends Mage_Core_Block_Template
{
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getDeliverydate()
    {
        if (!$this->hasData('deliverydate')) {
            $this->setData('deliverydate', Mage::registry('deliverydate'));
        }
        return $this->getData('deliverydate');

    }

    public function getDateFormat()
    {
        return Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) . ' ' . Mage::app()->getLocale()->getTimeStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }

    public function getDayOff()
    {
        $result = array();

        $collection = Mage::getModel('vinehousefarm_deliverydate/deliverydate')->getCollection()
            ->addFieldToFilter('status', 1);

        foreach ($collection as $item)
        {
            $result[] = $item->getHolidayTime();
        }

        return implode(',', $result);
    }
}