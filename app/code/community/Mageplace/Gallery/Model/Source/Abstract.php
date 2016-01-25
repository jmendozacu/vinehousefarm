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
 * Class Mageplace_Gallery_Model_Source_Abstract
 */
abstract class Mageplace_Gallery_Model_Source_Abstract
{
    protected $_helper;
    protected $_options;
    protected $_hashOptions;

    function __construct()
    {
        $this->_helper = Mage::helper('mpgallery');
    }

    abstract public function toOptionArray();

    public function toOptionHash()
    {
        if(null === $this->_hashOptions) {
            $this->_hashOptions = array();
            foreach ($this->toOptionArray() as $item) {
                $this->_hashOptions[$item['value']] = $item['label'];
            }
        }

        return $this->_hashOptions;
    }

    /**
     * @return Mageplace_Gallery_Helper_Data
     */
    protected function _helper()
    {
        return $this->_helper;
    }
}
