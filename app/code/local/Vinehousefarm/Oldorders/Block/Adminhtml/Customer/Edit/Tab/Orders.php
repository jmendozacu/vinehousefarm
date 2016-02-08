<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */

class Vinehousefarm_Oldorders_Block_Adminhtml_Customer_Edit_Tab_Orders
    extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_history_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(false);
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Old Orders');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Old Orders');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        $customer = Mage::registry('current_customer');
        return (bool)$customer->getId();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $store = Mage::app()->getStore();

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('oldorders')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'entity_id'
        ));

        $this->addColumn('delivery_name', array(
            'header'    => Mage::helper('oldorders')->__('Customer Name'),
            'index'     => 'delivery_name'
        ));

        $this->addColumn('order_date', array(
            'header'    => Mage::helper('oldorders')->__('Order Date'),
            'type'      => 'date',
            'index'     => 'order_date'
        ));

        $this->addColumn('delivery_date', array(
            'header'    => Mage::helper('oldorders')->__('Order Delivery Date'),
            'type'      => 'date',
            'index'     => 'delivery_date'
        ));

        $this->addColumn('total_ex_vat', array(
            'header'    => Mage::helper('oldorders')->__('Order Total Ex. VAT'),
            'type'      => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index'     => 'total_ex_vat'
        ));

        $this->addColumn('total_vat', array(
            'header'    => Mage::helper('oldorders')->__('Order Total VAT'),
            'type'      => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index'     => 'total_vat'
        ));

        $this->addColumn('total_final', array(
            'header'    => Mage::helper('oldorders')->__('Order Total'),
            'type'      => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index'     => 'total_final'
        ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('oldorders')->__('Products'),
                'index'		=> 'entity_id',
                'renderer'  => 'Vinehousefarm_Oldorders_Block_Adminhtml_Renderer_Products',
                'filter'    => false,
                'sortable'  => false
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $customer_id = $this->getRequest()->getParam('id');
        $customer = Mage::getModel('customer/customer')->load($customer_id);

        $collection = Mage::getModel('oldorders/orders')->getCollection()
            ->addFieldToFilter('client_id', array('eq' => $customer->getClinetId()));

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}