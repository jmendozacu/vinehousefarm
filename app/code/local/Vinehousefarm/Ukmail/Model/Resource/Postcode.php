<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */

class Vinehousefarm_Ukmail_Model_Resource_Postcode extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('ukmail/postcode', 'entity_id');
    }

    public function truncate() {
        $this->_getWriteAdapter()->query('TRUNCATE '.$this->getMainTable());
    }
}