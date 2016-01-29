<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Authoriselist_Block_Adminhtml_Sales_Order_View_Items_Renderer_Default extends Mage_Adminhtml_Block_Sales_Order_View_Items_Renderer_Default
{
    public function getShippingMethods()
    {
        if (Mage::helper('authoriselist')->isDropShipItem($this->getItem())) {
            return $this->__('Drop Ship Item');
        }

        if (Mage::helper('authoriselist')->isSupplierItem($this->getItem())) {
            return $this->__('Supplier Item');
        }

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save'),
            'method'    => 'post'
        ));

        $element = new Varien_Data_Form_Element_Select(
            array(
                'name' => 'item_' . $this->getItem()->getId() . '_shippng_method',
                'class' => 'input-text required-entry',
                'values' => $this->getShippingMethodValues(),
                'onchange' => "updateLabels(this);",
                'value' => $this->getShippingMethodValue()
            )
        );

        $element->setForm($form);
        $element->setId('item_' . $this->getItem()->getId() . '_shippng_method');

        return $element->getElementHtml();
    }

    /**
     * @return int
     */
    public function getLabels()
    {
        if ($this->getItem()->getProduct()) {
            if ($this->getItem()->getProduct()->hasNumberLabels()) {
                return (int) $this->getItem()->getProduct()->getNumberLabels() * $this->getItem()->getQtyOrdered();
            }
        }

        return 0;
    }

    public function getStockSummary() {
        $html = '<div style="white-space: nowrap;">';

        //Display stock quantity for a product : Available/Total
        $collection = mage::helper('AdvancedStock/Product_Base')->getStocksToDisplay($this->getItem()->getProductId());
        foreach ($collection as $item) {
            if ($item->ManageStock()) {
                $qty = ((int) $item->getqty());
                $available = ((int) $item->getAvailableQty());
                $color = ($available > 0 ? 'green' : 'red');
                $htmlLine = '<font color="'.$color.'">'.$item->getstock_name() . ' : ' . $available . ' / ' . $qty . '</font><br>';
                $html .= $htmlLine;
            }
        }

        //Display qty pending to be delivered by any supplier
        $waiting_for_delivery_qty = $this->getItem()->getProduct()->getData('waiting_for_delivery_qty');
        if($waiting_for_delivery_qty>0){
            $html .= Mage::helper('AdvancedStock')->__('Waiting for delivery'). ' : ' .$waiting_for_delivery_qty;
        }

        $html .= '</div>';

        return $html;
    }

    public function getWarehouse()
    {
        if (Mage::helper('authoriselist')->isDropShipItem($this->getItem())) {
            return $this->__('Drop Ship Item');
        }

        if (Mage::helper('authoriselist')->isSupplierItem($this->getItem())) {
            return $this->__('Supplier Item');
        }

        $warehouse = Mage::helper('authoriselist')->getWarehouses();

        if ($warehouse) {
            return Mage::getBlockSingleton('core/html_select')
                ->setName('item_' . $this->getItem()->getId() . '_warehouse')
                ->setId('warehouse')
                ->setTitle('')
                ->setClass('required-entry validate-select')
                ->setValue($this->getWarehouseValue())
                ->setOptions($warehouse)
                ->toHtml();
        } else {
            return '';
        }
    }

    /**
     * @return array
     */
    protected function getShippingMethodValues()
    {
        $methods = Mage::helper('authoriselist')->getShippingMethods();

        return array('' => 'Shipping method') + $methods;
    }

    /**
     * @param $_item
     * @return mixed
     */
    protected function getWarehouseValue()
    {
        if (!$this->getItem()->getWarehouseCode()) {

            $product = Mage::getModel('catalog/product')->load($this->getItem()->getProductId());

            $warehouse = (string)$product->getAttributeText('default_picked_from');

            return strtolower($warehouse);
        }

        return $this->getItem()->getWarehouseCode();
    }

    /**
     * @param $_item
     * @return mixed
     */
    protected function getShippingMethodValue()
    {
        if (!$this->getItem()->getShippingMethod()) {

            $product = Mage::getModel('catalog/product')->load($this->getItem()->getProductId());

            $shipping = (string)$product->getAttributeText('ships_method');

            return strtolower(str_replace(' ', '', $shipping));
        }

        return $this->getItem()->getShippingMethod();
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    protected function getProduct()
    {
        return $this->getItem()->getProduct();
    }
}