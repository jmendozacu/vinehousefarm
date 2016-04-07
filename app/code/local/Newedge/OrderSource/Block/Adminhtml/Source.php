<?php
class Newedge_OrderSource_Block_Adminhtml_Source extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	protected function _construct()
	{
		parent::_construct();
		$this->_blockGroup = 'newedge_ordersource_adminhtml';
        $this->_controller = 'source';
        $this->_headerText = Mage::helper('newedge_ordersource')->__('Order Sources');
    }
}