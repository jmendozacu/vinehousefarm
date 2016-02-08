<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */ 
class Vinehousefarm_Common_Block_AdvancedStock_Product_Widget_Grid_Column_Renderer_StockSummary
    extends MDN_AdvancedStock_Block_Product_Widget_Grid_Column_Renderer_StockSummary {

    public function render(Varien_Object $row) {
        $html = '<div style="white-space: nowrap;">';

        $row->setProductId($row->getId());

        if (Mage::helper('authoriselist')->isDropShipItem($row)) {
            return '<font color="#8b0000">' . $this->__('Drop Ship Item') . '</font>';
        }

        if (Mage::helper('authoriselist')->isSupplierItem($row)) {
            return '<font color="#8b0000">' . $this->__('Drop Ship Item') . '</font>';
        }

        //Display stock quantity for a product : Available/Total
        $collection = mage::helper('AdvancedStock/Product_Base')->getStocksToDisplay($row->getId());
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
        $waiting_for_delivery_qty = $row->getData('waiting_for_delivery_qty');
        if($waiting_for_delivery_qty>0){
            $html .= Mage::helper('AdvancedStock')->__('Waiting for delivery'). ' : ' .$waiting_for_delivery_qty;
        }

        $html .= '</div>';

        return $html;
    }
}