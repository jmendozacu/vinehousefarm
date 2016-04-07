<?php
class Newedge_OrderSource_Block_Adminhtml_Source_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'newedge_ordersource_adminhtml';
        $this->_controller = 'source';
        $this->_mode = 'edit';

        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);
        $this->_updateButton('save', 'label', Mage::helper('newedge_ordersource')->__('Save Source'));

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_source') && Mage::registry('current_source')->getId())
        {
            return Mage::helper('newedge_ordersource')->__('Edit Source "%s"', $this->htmlEscape(Mage::registry('current_source')->getTitle()));
        } else {
            return Mage::helper('newedge_ordersource')->__('New Source');
        }
    }
}