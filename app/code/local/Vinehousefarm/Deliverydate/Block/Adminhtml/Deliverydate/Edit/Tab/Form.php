<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Deliverydate_Block_Adminhtml_Deliverydate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('deliverydate_form', array('legend' => Mage::helper('vinehousefarm_deliverydate')->__('Item information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('holiday_time', 'date', array(
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Date'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'name' => 'holiday_time',
            'format' => 'dd-MM'
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('vinehousefarm_deliverydate')->__('Enabled'),
                ),

                array(
                    'value' => 2,
                    'label' => Mage::helper('vinehousefarm_deliverydate')->__('Disabled'),
                ),
            ),
        ));

        if (Mage::getSingleton('adminhtml/session')->getDeliverydateData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getDeliverydateData());
            Mage::getSingleton('adminhtml/session')->setDeliverydateData(null);
        } elseif (Mage::registry('deliverydate_data')) {
            $form->setValues(Mage::registry('deliverydate_data')->getData());
        }

        return parent::_prepareForm();
    }
}