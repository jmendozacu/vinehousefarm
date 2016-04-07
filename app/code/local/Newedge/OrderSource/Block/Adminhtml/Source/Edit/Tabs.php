<?php
class Newedge_OrderSource_Block_Adminhtml_Source_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('source_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle('Source Information');
	}
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
			'label' => 'Source Information',
			'title' => 'Source Information',
			'content' => $this->getLayout()
				->createBlock('newedge_ordersource/adminhtml_source_edit_tab_form')
				->toHtml()
		));
		return parent::_beforeToHtml();
	}
}