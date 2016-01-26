<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Authoriselist_Block_Adminhtml_Pickingpacking_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     * @throws Exception
     */
    protected function _prepareForm()
    {
        if (Mage::registry('reason_order')) {
            $data = Mage::registry('reason_order')->getData();
        } else {
            $data = array();
        }


        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/unpicking', array('order_ids' => $this->getRequest()->getParam('order_id'))),
            'method' => 'post',
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('return_form', array(
            'legend' =>Mage::helper('sales')->__('Return Information (Order: ' . $data['increment_id'] . ')' )
        ));

        $fieldset->addField('reason', 'textarea', array(
            'label'     => Mage::helper('sales')->__('Reason'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'reason',
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }
}