<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Ukmail_Block_Adminhtml_Labels_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('grid_id');
        // $this->setDefaultSort('COLUMN_ID');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ukmail/label')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('order_id', array(
            'header' => Mage::helper('ukmail')->__('Order #'),
            'width' => '80px',
            'type' => 'text',
            'index' => 'order_id',
        ));

        $this->addColumn('consignment_number', array(
            'header' => Mage::helper('ukmail')->__('Consignment Number'),
            'index' => 'consignment_number',
        ));

        $this->addColumn('collection_job_number', array(
            'header' => Mage::helper('ukmail')->__('Collection Job Number'),
            'index' => 'collection_job_number',
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('ukmail')->__('Action'),
                'width' => '100px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('ukmail')->__('Labels'),
                        'url' => array('base' => '*/ukmail/labels'),
                        'field' => 'order_id',
                        'data-column' => 'action',
                    ),
                    array(
                        'caption' => Mage::helper('ukmail')->__('Cancel'),
                        'url' => array('base' => '*/ukmail/cancel'),
                        'field' => 'order_id',
                        'data-column' => 'action',
                        'confirm' => Mage::helper('ukmail')->__('Delivery to cancel. Are you sure?'),
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
//        $modelPk = Mage::getModel('ukmail/label')->getResource()->getIdFieldName();
//        $this->setMassactionIdField($modelPk);
//        $this->getMassactionBlock()->setFormFieldName('ids');
//        // $this->getMassactionBlock()->setUseSelectAll(false);
//        $this->getMassactionBlock()->addItem('cancel', array(
//            'label' => $this->__('Cancel'),
//            'url' => $this->getUrl('*/*/massCancel'),
//        ));
        return $this;
    }
}
