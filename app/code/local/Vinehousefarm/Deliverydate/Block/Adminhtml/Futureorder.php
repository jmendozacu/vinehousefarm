<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Deliverydate_Block_Adminhtml_Futureorder extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_futureorder';
        $this->_blockGroup = 'vinehousefarm_deliverydate';

        $this->_headerText = Mage::helper('sales')->__('Future Dispatch Date');

        parent::__construct();

        $this->removeButton('add');
    }
}