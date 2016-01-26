<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Block_Adminhtml_Processing extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'authoriselist';
        $this->_controller = 'adminhtml_processing';

        $this->_headerText = Mage::helper('sales')->__('Processing');

        parent::__construct();

        $this->removeButton('add');
    }
}

