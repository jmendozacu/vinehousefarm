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
 * Class Mageplace_Gallery_Model_Source_Cmsblock
 */
class Mageplace_Gallery_Model_Source_Cmsblock extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('cms/block_collection')
                ->load()
                ->toOptionArray();

            array_unshift($this->_options, array('value' => '', 'label' => Mage::helper('catalog')->__('Please select a static block ...')));
        }

        return $this->_options;
    }
}