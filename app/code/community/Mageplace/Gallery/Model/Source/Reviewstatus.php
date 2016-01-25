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
class Mageplace_Gallery_Model_Source_Reviewstatus extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        $options = array(
            array('value' => Mageplace_Gallery_Model_Review::PENDING, 'label' => Mage::helper('review')->__('Pending')),
            array('value' => Mageplace_Gallery_Model_Review::APPROVED, 'label' => Mage::helper('review')->__('Approved')),
            array('value' => Mageplace_Gallery_Model_Review::DISABLED, 'label' => Mage::helper('review')->__('Not Approved')),
        );

        return $options;
    }
}