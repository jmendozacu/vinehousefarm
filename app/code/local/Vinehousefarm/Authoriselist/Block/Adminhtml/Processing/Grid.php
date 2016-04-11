<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Block_Adminhtml_Processing_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('processing_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_grid_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass())
            ->addFieldToFilter('entity_id', array('in', $this->getOrderIds()))
            ->addAttributeToFilter('order.status', array('nin' => array(Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_PICKING, Vinehousefarm_Deliverydate_Helper_Data::STATUS_ORDER_DELIVERY_DATE, Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_AUTHORISE)));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function getOrderIds()
    {
        return $this->helper('authoriselist')->getErpIds();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('order.created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));

//        $this->addColumn('dropship_sent', array(
//            'header' => Mage::helper('sales')->__('Drop Ship'),
//            'index' => 'dropship_sent',
//            'align' => 'center',
//            'filter' => false,
//            'sortable' => false,
//            'renderer' => 'Vinehousefarm_Authoriselist_Block_Adminhtml_Processing_Renderer_Dropship',
//        ));

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action',
                array(
                    'header'    => Mage::helper('sales')->__('Action'),
                    'width'     => '50px',
                    'type'      => 'action',
                    'getter'     => 'getId',
                    'actions'   => array(
                        array(
                            'caption' => Mage::helper('sales')->__('View'),
                            'url'     => array('base'=>'*/sales_order/view'),
                            'field'   => 'order_id',
                            'data-column' => 'action',
                        )
                    ),
                    'filter'    => false,
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
                ));
        }

        Mage::dispatchEvent('salesorder_grid_preparecolumns', array('grid'=>$this));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem('authorization_order', array(
            'label'=> Mage::helper('authoriselist')->__('Move back to Authorization screen'),
            'url'  => $this->getUrl('*/*/authorizationmove'),
        ));

        $this->getMassactionBlock()->addItem('picking_order', array(
            'label'=> Mage::helper('authoriselist')->__('Picking/Packing'),
            'url'  => $this->getUrl('*/*/picking'),
        ));

        $this->getMassactionBlock()->addItem('pdfshipments_order_warehouse', array(
            'label'=> Mage::helper('authoriselist')->__('Warehouse Picklist + Advice slip'),
            'url'  => $this->getUrl('*/*/pdfshipments', array('type' => 'warehouse')),
        ));

        $this->getMassactionBlock()->addItem('pdfshipments_order_office', array(
            'label'=> Mage::helper('authoriselist')->__('Office Picklist + Advice slip'),
            'url'  => $this->getUrl('*/*/pdfshipments', array('type' => 'office')),
        ));

//        $this->getMassactionBlock()->addItem('pdfshipments1_order', array(
//            'label'=> Mage::helper('authoriselist')->__('Print Advice slips'),
//            'url'  => $this->getUrl('*/*/pdfadviceslips'),
//        ));

        $this->getMassactionBlock()->addItem('print_shipping_label', array(
            'label'=> Mage::helper('authoriselist')->__('Print Shipping Labels'),
            'url'  => $this->getUrl('*/*/printlabels'),
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
//        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
//            return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
//        }
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
