<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */ 
class Vinehousefarm_Deliverydate_Block_Adminhtml_Sales_Order_View_Tab_Info extends Mage_Adminhtml_Block_Sales_Order_View_Tab_Info
{
    /**
     * @return string
     * @throws Exception
     */
    public function getDeliveryDateElement()
    {
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save'),
            'method'    => 'post'
        ));

        $element = new Varien_Data_Form_Element_Date(
            array(
                'name' => 'date',
                'label' => Mage::helper('vinehousefarm_deliverydate')->__('Shipping Arrival Date'),
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                'value' => date(
                    Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                    strtotime($this->getOrder()->getShippingArrivalDate())
                )
            )
        );

        $element->setId('deliveridate');
        $form->addElement($element);

//        $buttom = new Varien_Data_Form_Element_Button(
//            array(
//                'name' => 'datesave',
//                'type' => 'button',
//                'value' => 'Change',
//                'onclick' => "changeDeliveryDate();",
//                'class' => 'button',
//            )
//        );
//
//        $buttom = $this->getLayout()->createBlock('adminhtml/widget_button', 'datesave',
//            array(
//                'value' => 'Change',
//                'onclick' => "changeDeliveryDate();",
//            )
//        );
//
//        $buttom->setId('datesave');
//        $form->addElement($buttom);

        $hidden = new Varien_Data_Form_Element_Hidden(
            array(
                'name' => 'delivery_date_order',
                'value' => $this->getOrder()->getId(),
            )
        );

        $hidden->setId('dateorder');
        $form->addElement($hidden);

        return $form->getHtml();
    }

    public function getButtonSave()
    {
        $buttom = $this->getLayout()->createBlock('adminhtml/widget_button', 'datesave',
            array(
                'label' => 'Change',
                'onclick' => "changeDeliveryDate();",
            )
        );

        return $buttom->toHtml();
    }

    public function getDaliveryChange()
    {
        return $this->getUrl('adminhtml/deliverydate/deliverychange');
    }
}