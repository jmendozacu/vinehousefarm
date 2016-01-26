<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Block_Adminhtml_Deliverydate_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'vinehousefarm_deliverydate';
        $this->_controller = 'adminhtml_deliverydate';

        $this->_updateButton('save', 'label', Mage::helper('vinehousefarm_deliverydate')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('vinehousefarm_deliverydate')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('deliverydate_data') && Mage::registry('deliverydate_data')->getId()) {
            return Mage::helper('vinehousefarm_deliverydate')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('deliverydate_data')->getTitle()));
        } else {
            return Mage::helper('vinehousefarm_deliverydate')->__('Add Item');
        }
    }
}