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
 * Class Mageplace_Gallery_Model_Source_Photostatus
 */
class Mageplace_Gallery_Model_Source_Photostatus extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        $options = array(
            array('value' => Mageplace_Gallery_Model_Photo::PENDING, 'label' => Mage::helper('review')->__('Pending')),
            array('value' => Mageplace_Gallery_Model_Photo::APPROVED, 'label' => Mage::helper('review')->__('Approved')),
            array('value' => Mageplace_Gallery_Model_Photo::DISABLED, 'label' => Mage::helper('adminhtml')->__('Disabled')),
        );

        return $options;
    }
}