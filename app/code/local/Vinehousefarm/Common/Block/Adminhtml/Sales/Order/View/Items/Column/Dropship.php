<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */

class Vinehousefarm_Common_Block_Adminhtml_Sales_Order_View_Items_Column_Dropship extends Mage_Adminhtml_Block_Sales_Items_Column_Default
{
    public function getButtonAction()
    {
        if ($this->getItem()->getItemDropship() || $this->getItem()->getItemSupplier()) {
            return $this->getLayout()
                ->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setLabel('Supplier Sent')
                ->setOnClick("window.location='" . $this->getUrl('adminhtml/processing/suppliersent', array('order_id' => $this->getItem()->getOrder()->getId())) . "';")
                ->_toHtml();
        }

        return '';
    }
}