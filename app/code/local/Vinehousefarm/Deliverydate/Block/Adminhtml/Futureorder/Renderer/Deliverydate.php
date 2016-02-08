<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Deliverydate_Block_Adminhtml_Futureorder_Renderer_Deliverydate extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $order = $row->load($row->getId());
        $value =  $order->getData($this->getColumn()->getIndex());

        return Mage::getModel('core/date')->date('Y-m-d', $value);
    }
}