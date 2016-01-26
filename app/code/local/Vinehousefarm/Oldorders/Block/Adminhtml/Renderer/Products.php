<?php

/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */
class Vinehousefarm_Oldorders_Block_Adminhtml_Renderer_Products extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        return '<a href="javascript:void(0);" class="oldorders_details" data-id="' . $value . '" >' . Mage::helper('oldorders')->__('Products') . '</a>';
    }
}