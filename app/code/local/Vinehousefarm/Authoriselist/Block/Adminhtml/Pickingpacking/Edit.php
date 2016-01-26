<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Authoriselist_Block_Adminhtml_Pickingpacking_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'id';
        $this->_controller = 'adminhtml_processing';
        $this->_blockGroup = 'authoriselist';

        parent::__construct();

        $this->updateButton('save', 'label', Mage::helper('sales')->__('Return to proccesing'));

        $this->removeButton('back');
        $this->removeButton('reset');
    }

    public function getHeaderText()
    {
        return Mage::helper('sales')->__('Return to proccesing');
    }
}