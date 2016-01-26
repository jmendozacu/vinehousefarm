<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */

class Vinehousefarm_Oldorders_Model_Resource_Orders  extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('oldorders/oldorders', 'id');
    }
}