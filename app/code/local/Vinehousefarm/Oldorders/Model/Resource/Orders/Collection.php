<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */

class Vinehousefarm_Oldorders_Model_Resource_Orders_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('oldorders/orders');
    }
}