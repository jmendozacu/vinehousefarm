<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Birdlibrary_Block_Adminhtml_Library_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $collection = Mage::getModel('birdlibrary/bird')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper('birdlibrary')->__('Entity #'),
            'width' => '80px',
            'type' => 'text',
            'index' => 'entity_id',
        ));

        $this->addColumn('bird_name', array(
            'header' => Mage::helper('birdlibrary')->__('Name'),
            'index' => 'bird_name',
            'type' => 'text',
        ));

        $this->addColumn('latin_name', array(
            'header' => Mage::helper('birdlibrary')->__('Latin Name'),
            'index' => 'latin_name',
        ));

        $this->addColumn('family', array(
            'header' => Mage::helper('birdlibrary')->__('Family'),
            'index' => 'family',
        ));

        $this->addColumn('distribution_map', array(
            'header' => Mage::helper('birdlibrary')->__('Distribution Map and Info'),
            'index' => 'distribution_map',
            'type' => 'text',
        ));

        $this->addColumn('habitat', array(
            'header' => Mage::helper('birdlibrary')->__('Habitat'),
            'index' => 'habitat',
            'type' => 'text',
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('birdlibrary')->__('Action'),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('birdlibrary')->__('View'),
                        'url' => array('base' => '*/library/view'),
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
        $modelPk = Mage::getModel('birdlibrary/bird')->getResource()->getIdFieldName();
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
