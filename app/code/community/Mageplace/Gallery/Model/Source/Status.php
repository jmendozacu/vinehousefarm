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
 * Class Mageplace_Gallery_Model_Source_Status
 */
class Mageplace_Gallery_Model_Source_Status extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        $options = array(
            array('value' => Mageplace_Gallery_Helper_Const::ENABLE, 'label' => Mage::helper('adminhtml')->__('Enable')),
            array('value' => Mageplace_Gallery_Helper_Const::DISABLE, 'label' => Mage::helper('adminhtml')->__('Disable')),
        );

        return $options;
    }
}