<?php
/**
 * @package PhpStorm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */ 
class Vinehousefarm_Authoriselist_Block_Adminhtml_Sales_Order_Create_Items_Grid extends Aitoc_Aitloyalty_Block_Rewrite_AdminhtmlSalesOrderCreateItemsGrid
{
    public function getShippingMethods($_item)
    {
        if (Mage::helper('authoriselist')->isDropShipItem($_item)) {
            return $this->__('Drop Ship Item');
        }

        if (Mage::helper('authoriselist')->isSupplierItem($_item)) {
            return $this->__('Drop Ship Item');
        }

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save'),
            'method'    => 'post'
        ));

        $element = new Varien_Data_Form_Element_Select(
            array(
                'name' => 'item_' . $_item->getId() . '_shippng_method',
                'class' => 'required-entry validate-select',
                'values' => $this->getShippingMethodValues(),
                'value' => $this->getShippingMethodValue($_item)
            )
        );

        $element->setForm($form);
        $element->setId('item_' . $_item->getId() . '_shippng_method');

        return $element->getElementHtml();
    }

    public function getStockSummary($_item) {
        $html = '<div style="white-space: nowrap;">';

        //Display stock quantity for a product : Available/Total
        $collection = mage::helper('AdvancedStock/Product_Base')->getStocksToDisplay($_item->getProductId());
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
        $waiting_for_delivery_qty = $_item->getProduct()->getData('waiting_for_delivery_qty');
        if($waiting_for_delivery_qty>0){
            $html .= Mage::helper('AdvancedStock')->__('Waiting for delivery'). ' : ' .$waiting_for_delivery_qty;
        }

        $html .= '</div>';

        return $html;
    }

    public function getWarehouse($_item)
    {
        if (Mage::helper('authoriselist')->isDropShipItem($_item)) {
            return $this->__('Drop Ship Item');
        }

        if (Mage::helper('authoriselist')->isSupplierItem($_item)) {
            return $this->__('Drop Ship Item');
        }

        $warehouse = Mage::helper('authoriselist')->getWarehouses();

        if ($warehouse) {
            return Mage::getBlockSingleton('core/html_select')
                ->setName('item_' . $_item->getId() . '_warehouse')
                ->setId('warehouse')
                ->setTitle('')
                ->setClass('required-entry validate-select')
                ->setValue($this->getWarehouseValue($_item))
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
    public function getWarehouseValue($_item)
    {
        if (!$_item->getWarehouseCode()) {

            $product = Mage::getModel('catalog/product')->load($_item->getProductId());

            $warehouse = (string)$product->getAttributeText('default_picked_from');

            return strtolower($warehouse);
        }

        return $_item->getWarehouseCode();
    }

    /**
     * @param $_item
     * @return mixed
     */
    public function getShippingMethodValue($_item)
    {
        if (!$_item->getShippingMethod()) {

            $product = Mage::getModel('catalog/product')->load($_item->getProductId());

            $shipping = (string)$product->getAttributeText('ships_method');

            return strtolower(str_replace(' ', '', $shipping));
        }

        return $_item->getShippingMethod();
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    protected function getProduct()
    {
        return $this->getItem()->getProduct();
    }
}