<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */

class Vinehousefarm_Ukmail_Model_Country extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ukmail/country');
    }

    public function truncate() {
        $this->getResource()->truncate();
    }
}