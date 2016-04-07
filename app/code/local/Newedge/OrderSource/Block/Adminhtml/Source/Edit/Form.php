<?php
class Newedge_OrderSource_Block_Adminhtml_Source_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{


	protected function _prepareForm()
	{
		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
			'method' => 'post',
			'enctype' => 'multipart/form-data',
		));

		$form->setUseContainer(true);

		$this->setForm($form);

		$fieldset = $form->addFieldset('source_form', array(
			'legend' =>Mage::helper('newedge_ordersource')->__('Source Information')
		));

		$fieldset->addField('title', 'text', array(
			'label'     => Mage::helper('newedge_ordersource')->__('Name'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'title',
			'note'     => Mage::helper('newedge_ordersource')->__('The name of the Source.'),
		));
		$id = Mage::app()->getRequest()->getParam('id');
		$model= Mage::getModel('newedge_ordersource/source')->load($id);
		$form->setValues($model->getData());
//		$form->setValues($data);

		return parent::_prepareForm();
	}

	protected function _prepareLayout()
	{
		parent::_prepareLayout();

	}
}