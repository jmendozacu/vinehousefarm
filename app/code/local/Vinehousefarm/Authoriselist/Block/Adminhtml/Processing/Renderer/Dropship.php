<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Authoriselist_Block_Adminhtml_Processing_Renderer_Dropship extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $order = $row->load($row->getId());
        $value =  $order->getData($this->getColumn()->getIndex());
        $dropshipFlag = false;
        $supplierFlag = false;

        /**
         * @var $item Mage_Sales_Model_Order_Item
         */
        foreach ($order->getAllItems() as $item) {
            //TODO need refactoring
            /**
             * @var $product Mage_Catalog_Model_Product
             */
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            $dropship = (string)$product->getResource()
                ->getAttribute('dropship')
                ->getFrontend()
                ->getValue($product);

            if ($product->getSupplier()) {
                $supplierFlag = true;
            }

            if ($dropship == 'Yes') {
                $dropshipFlag = true;
            }
        }

        if ($dropshipFlag) {
            return $this->getLayout()
                ->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setLabel('Drop Ship Sent')
                ->setOnClick("window.location='" . $this->getUrl('*/*/dropshipsent', array('order_id' => $row->getId())) . "';")
                ->_toHtml();
        }

        if ($supplierFlag) {
            return $this->getLayout()
                ->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setLabel('Supplier Sent')
                ->setOnClick("window.location='" . $this->getUrl('*/*/suppliersent', array('order_id' => $row->getId())) . "';")
                ->_toHtml();
        }

        return '';
    }
}