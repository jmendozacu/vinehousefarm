<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Deliverydate_Block_Adminhtml_Deliverydate_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('deliverydateGrid');
        $this->setDefaultSort('deliverydate_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('vinehousefarm_deliverydate/deliverydate')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'deliverydate_id',
            array(
                'header' => Mage::helper('vinehousefarm_deliverydate')->__('ID'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'deliverydate_id',
            )
        );

        $this->addColumn('title', array(
            'header' => Mage::helper('vinehousefarm_deliverydate')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('holiday_time', array(
            'header' => Mage::helper('vinehousefarm_deliverydate')->__('Holiday'),
            'align' => 'left',
            'index' => 'holiday_time',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('vinehousefarm_deliverydate')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn(
            'action',
            array(
                'header' => Mage::helper('vinehousefarm_deliverydate')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('vinehousefarm_deliverydate')->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            )
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('vinehousefarm_deliverydate')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('vinehousefarm_deliverydate')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('deliverydate_id');
        $this->getMassactionBlock()->setFormFieldName('deliverydate');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('vinehousefarm_deliverydate')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('vinehousefarm_deliverydate/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));

        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('vinehousefarm_deliverydate')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('vinehousefarm_deliverydate')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}