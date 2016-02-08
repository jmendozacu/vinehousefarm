<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */

class Vinehousefarm_Common_Block_Adminhtml_Sales_Order_View_Tab_Dropship
    extends Mage_Adminhtml_Block_Sales_Order_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _construct()
    {
        parent::_construct();
        $this->setBlockAlias('sales_order_dropship');
        $this->setTemplate('vinehousefarm/common/list.phtml');

    }

    /**
     * Retrieve order items collection
     *
     * @return unknown
     */
    public function getItemsCollection()
    {
        if (!$this->getCollection()) {

            $itemIds = array();
            $dropItems = array();
            $supplierItems = array();

            foreach ($this->getOrder()->getItemsCollection() as $item) {
                if (Mage::helper('authoriselist')->isSupplierItem($item)) {
                    $itemIds[$item->getId()] = $item->getId();
                    $dropItems[$item->getId()] = $item->getId();
                }

                if (Mage::helper('authoriselist')->isDropShipItem($item)) {
                    $itemIds[$item->getId()] = $item->getId();
                    $supplierItems[$item->getId()] = $item->getId();
                }
            }

            $collection = Mage::getModel('sales/order_item')->getCollection()
                ->addAttributeToFilter('order_id', $this->getOrder()->getId())
                ->addAttributeToFilter('item_id', array('in' => $itemIds));

            foreach ($collection as $item) {
                if (in_array($item->getId(), $dropItems)) {
                    $item->setItemDropship(true);
                    $item->setItemSupplier(false);
                }

                if (in_array($item->getId(), $supplierItems)) {
                    $item->setItemDropship(false);
                    $item->setItemSupplier(true);
                }
            }

            $this->setCollection($collection);
        }

        return $this->getCollection();
    }

    public function getItemsHtml()
    {
        /**
         * @var Mage_Adminhtml_Block_Sales_Order_View_Items
         */
        $items = $this->getLayout()->createBlock('vinehousefarm_common/adminhtml_sales_order_view_items', 'sales_order_dropship_items', array(
            'template' => 'vinehousefarm/common/items.phtml'
        ));

        $items->addItemRender('default', 'adminhtml/sales_order_view_items_renderer_default', 'vinehousefarm/common/items/renderer/default.phtml');
        $items->addColumnRender('qty', 'adminhtml/sales_items_column_qty', 'sales/items/column/qty.phtml');
        $items->addColumnRender('name', 'adminhtml/sales_items_column_name', 'sales/items/column/name.phtml');
        $items->addColumnRender('action', 'vinehousefarm_common/adminhtml_sales_order_view_items_column_dropship', 'vinehousefarm/common/items/column/dropship.phtml');

        $this->setChild('sales_order_dropship_items', $items);

        return $this->getChildHtml('sales_order_dropship_items');
    }

    /**
     * Retrieve available order
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if ($this->hasOrder()) {
            return $this->getData('order');
        }
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order');
        }
        if (Mage::registry('order')) {
            return Mage::registry('order');
        }
        Mage::throwException(Mage::helper('vinehousefarm_common')->__('Cannot get order instance'));
    }

    public function getTabLabel()
    {
        return Mage::helper('vinehousefarm_common')->__('Dropship Items');
    }

    public function getTabTitle()
    {
        return Mage::helper('vinehousefarm_common')->__('Dropship Items');
    }

    public function canShowTab()
    {
        if ($this->getOrder()->getId()) {
            if ($this->getItemsCollection()) {
                if ($this->getItemsCollection()->count()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isHidden()
    {
        return false;
    }
}