<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Productvideo_Block_Adminhtml_Library extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup      = 'productvideo';
        $this->_controller      = 'adminhtml_library';
        $this->_headerText      = $this->__('Video Library');
        $this->_addButtonLabel  = $this->__('Add Video');

        parent::__construct();
    }
}