<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Block_Adminhtml_Order_Empty extends Vinehousefarm_Authoriselist_Block_Adminhtml_Order_Edit
{
    public function __construct()
    {
        // $this->_objectId = 'id';
        parent::__construct();
        $this->_blockGroup      = 'authoriselist';
        $this->_controller = 'adminhtml_order';

        $this->removeButton('save');
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->_mode            = 'edit';
    }
}
