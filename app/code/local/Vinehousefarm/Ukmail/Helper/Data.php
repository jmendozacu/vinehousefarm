<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Ukmail_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_CONFIG_PATH = 'carriers/ukmail/';

    const RESULT_SUCCESSFUL = 'Successful';
    const RESULT_FAILED = 'Failed';

    const STATUS_UNKNOWN = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;

    /**
     * Return value from config.
     *
     * @param $key
     * @param string $store
     *
     * @return mixed
     */
    public function getConfigValue($key, $store = '')
    {
        return $this->_getConfigValue($key, $store);
    }

    /**
     * Config value.
     *
     * @param $key
     * @param $store
     *
     * @return mixed
     */
    protected function _getConfigValue($key, $store = '')
    {
        return Mage::getStoreConfig(self::XML_CONFIG_PATH . $key, $store);
    }
}