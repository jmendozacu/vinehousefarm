<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Productvideo_Block_Adminhtml_Library_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $collection = Mage::getModel('productvideo/video')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper('productvideo')->__('Entity #'),
            'width' => '80px',
            'type' => 'text',
            'index' => 'entity_id',
        ));

        $this->addColumn('video_name', array(
            'header' => Mage::helper('productvideo')->__('Name'),
            'index' => 'video_name',
            'type' => 'text',
        ));

        $this->addColumn('video_code', array(
            'header' => Mage::helper('productvideo')->__('Code'),
            'index' => 'video_code',
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('productvideo')->__('Action'),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('productvideo')->__('View'),
                        'url' => array('base' => '*/videolibrary/view'),
                        'field' => 'id',
                        'data-column' => 'action',
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
        $modelPk = Mage::getModel('productvideo/video')->getResource()->getIdFieldName();
        $this->setMassactionIdField($modelPk);
        $this->getMassactionBlock()->setFormFieldName('ids');
        // $this->getMassactionBlock()->setUseSelectAll(false);
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
        ));
        return $this;
    }
}
