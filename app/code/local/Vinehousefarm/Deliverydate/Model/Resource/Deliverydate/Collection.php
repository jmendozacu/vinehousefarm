<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Deliverydate_Model_Resource_Deliverydate_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('vinehousefarm_deliverydate/deliverydate');
    }

    protected function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $item->setHolidayTime(date('d-m', strtotime($item->getHolidayTime())));
        }

        return parent::_afterLoad();
    }
}