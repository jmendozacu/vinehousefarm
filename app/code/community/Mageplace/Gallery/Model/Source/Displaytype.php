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
 * Class Mageplace_Gallery_Model_Source_Displaytype
 */
class Mageplace_Gallery_Model_Source_Displaytype extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_GRID_LIST_SIMPLE, 'label' => $this->_helper()->__('Grid, list, simple')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_GRID_SIMPLE_LIST, 'label' => $this->_helper()->__('Grid, simple, list')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_LIST_GRID_SIMPLE, 'label' => $this->_helper()->__('List, grid, simple')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_LIST_SIMPLE_GRID, 'label' => $this->_helper()->__('List, simple, grid')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_SIMPLE_LIST_GRID, 'label' => $this->_helper()->__('Simple, list, grid')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_SIMPLE_GRID_LIST, 'label' => $this->_helper()->__('Simple, grid, list')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_GRID_LIST, 'label' => $this->_helper()->__('Grid, list')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_GRID_SIMPLE, 'label' => $this->_helper()->__('Grid, simple')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_LIST_GRID, 'label' => $this->_helper()->__('List, grid')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_LIST_SIMPLE, 'label' => $this->_helper()->__('List, simple')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_SIMPLE_LIST, 'label' => $this->_helper()->__('Simple, list')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_SIMPLE_GRID, 'label' => $this->_helper()->__('Simple, grid')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_GRID, 'label' => $this->_helper()->__('Grid')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_LIST, 'label' => $this->_helper()->__('List')),
            array('value' => Mageplace_Gallery_Helper_Const::DISPLAY_TYPES_SIMPLE, 'label' => $this->_helper()->__('Simple')),
        );
    }
}