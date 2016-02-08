<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */

class Vinehousefarm_Authoriselist_Block_Adminhtml_Sales_Order_Create_Search_Grid extends Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid
{
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        //replace qty column
        $this->addColumn('stock', array(
            'header'=> Mage::helper('AdvancedStock')->__('Stock Summary'),
            'index' => 'entity_id',
            'renderer'	=> 'Vinehousefarm_Common_Block_AdvancedStock_Product_Widget_Grid_Column_Renderer_StockSummary',
            'filter' => 'MDN_AdvancedStock_Block_Product_Widget_Grid_Column_Filter_StockSummary',
            'sortable'	=> false
        ));
    }
}