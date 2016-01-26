<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Deliverydate_Block_Adminhtml_Deliverydate extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_deliverydate';
        $this->_blockGroup = 'vinehousefarm_deliverydate';
        $this->_headerText = Mage::helper('vinehousefarm_deliverydate')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('vinehousefarm_deliverydate')->__('Add Item');
        parent::__construct();
    }
}