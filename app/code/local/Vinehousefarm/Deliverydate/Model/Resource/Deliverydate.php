<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Model_Resource_Deliverydate extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        // Note that the deliverydate_id refers to the key field in your database table.
        $this->_init('vinehousefarm_deliverydate/vinehousefarm_deliverydate', 'deliverydate_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {

        $data = Mage::app()->getLocale()->date($object->getHolidayTime(), 'dd-MM');
        $object->setHolidayTime($data->toString('YYYY-MM-dd HH:mm:ss'));

        return parent::_beforeSave($object);
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $object->setHolidayTime(date('d-m', strtotime($object->getHolidayTime())));

        return parent::_afterLoad($object);
    }
}