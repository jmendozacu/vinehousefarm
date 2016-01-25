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
 * Class Mageplace_Gallery_Model_Source_Pagelayout
 */
class Mageplace_Gallery_Model_Source_Pagelayout extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        if (null === $this->_options) {
            $this->_options = Mage::getSingleton('page/source_layout')->toOptionArray();
            array_unshift($this->_options, array('value' => '', 'label' => Mage::helper('catalog')->__('No layout updates')));
        }

        return $this->_options;
    }
}
