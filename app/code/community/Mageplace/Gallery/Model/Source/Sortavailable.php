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
 * Class Mageplace_Gallery_Model_Source_Sortavailable
 */
class Mageplace_Gallery_Model_Source_Sortavailable extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray($includeAll = false, $default = false)
    {
        $options = array(
            array('value' => Mageplace_Gallery_Helper_Const::SORT_BY_POSITION, 'label' => $this->_helper()->__($default ? 'Default' : 'Position')),
            array('value' => Mageplace_Gallery_Helper_Const::SORT_BY_NAME, 'label' => $this->_helper()->__('Name')),
            array('value' => Mageplace_Gallery_Helper_Const::SORT_BY_UPDATE_DATE, 'label' => $this->_helper()->__('Date')),
        );

        if($includeAll) {
            array_unshift($options, array('value' => 0, 'label' => $this->_helper()->__('Use All Available')));
        }

        return $options;
    }
}