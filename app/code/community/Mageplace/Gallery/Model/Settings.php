<?php
/**
 * MagePlace Gallery Extension
 *
 * @category    Mageplace_Gallery
 * @package     Mageplace_Gallery
 * @copyright   Copyright (c) 2014 Mageplace. (http://www.mageplace.com)
 * @license     http://www.mageplace.com/disclaimer.html
 */

/**
 * Class Mageplace_Gallery_Model_Settings
 */
class Mageplace_Gallery_Model_Settings extends Varien_Object
{
    /**
     * @var Mageplace_Gallery_Helper_Config
     */
    protected $_configHelper;
    protected $_configData = array();
    protected $_group;


    protected function _construct()
    {
        $this->_configHelper = Mage::helper('mpgallery/config');
    }

    public function setGroup($group)
    {
        $this->_group = $group;

        return $this;
    }

    public function getData($key = '', $group = null)
    {
#        Mage::log($key);
        if(array_key_exists($key, $this->_configData)) {
            return $this->_configData[$key];
        }

        $data = parent::getData($key);
#        Mage::log($data);
        if (null === $data) {
            $data = $this->_configHelper->find($key, null !== $group ? $group : $this->_group);
#           Mage::log($data);
        }

        $this->_configData[$key] = $data;

        return $data;
    }
}
