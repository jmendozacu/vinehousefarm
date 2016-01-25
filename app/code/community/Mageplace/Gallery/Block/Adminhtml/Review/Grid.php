<?php
/**
 * MagePlace Gallery Extension
 *
 * @category    Mageplace_Gallery
 * @package     Mageplace_Gallery
 * @copyright   Copyright (c) 2014 Mageplace. (http://www.mageplace.com)
 * @license     http://www.mageplace.com/disclaimer.html
 */

/**
 * Class Mageplace_Gallery_Block_Adminhtml_Review_Grid
 */
class Mageplace_Gallery_Block_Adminhtml_Review_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('review_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('creation_date');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('mpgallery/review_collection')->joinPhoto();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('review_id',
            array(
                'header'       => $this->__('ID'),
                'type'         => 'number',
                'index'        => 'review_id',
                'filter_index' => 'main_table.review_id',
                'width'        => '80px',
            )
        );

        $this->addColumn('creation_date',
            array(
                'header'       => $this->helper('review')->__('Created On'),
                'type'         => 'datetime',
                'index'        => 'creation_date',
                'filter_index' => 'main_table.creation_date',
                'align'        => 'left',
                'width'        => '100px',
            )
        );

        $this->addColumn('name',
            array(
                'header'       => $this->helper('review')->__('Name'),
                'index'        => 'name',
                'filter_index' => 'main_table.name',
            )
        );

        $this->addColumn('email',
            array(
                'header'       => $this->helper('customer')->__('Email'),
                'index'        => 'email',
                'filter_index' => 'main_table.email',
            )
        );

        $this->addColumn('comment',
            array(
                'header'       => $this->helper('review')->__('Review'),
                'type'         => 'text',
                'index'        => 'comment',
                'filter_index' => 'main_table.comment',
                'truncate'     => 100
            )
        );

        $this->addColumn('rate',
            array(
                'header'       => $this->__('Rate'),
                'type'         => 'number',
                'index'        => 'rate',
                'filter_index' => 'main_table.rate',
                'width'        => '80px',
            )
        );

        $this->addColumn('status',
            array(
                'header'       => $this->helper('review')->__('Status'),
                'type'         => 'options',
                'index'        => 'status',
                'filter_index' => 'main_table.status',
                'width'        => '70px',
                'options'      => Mage::getSingleton('mpgallery/source_reviewstatus')->toOptionHash()
            )
        );

        $this->addColumn('photo_id',
            array(
                'header'       => $this->__('Photo ID'),
                'type'         => 'number',
                'index'        => 'photo_id',
                'filter_index' => 'photo_table.photo_id',
                'width'        => '80px',
            )
        );

        $this->addColumn('photo_name',
            array(
                'header'       => $this->__('Photo Name'),
                'index'        => 'photo_name',
                'filter_index' => 'photo_table.name',
            )
        );

        $this->addColumn('photo_thumb',
            array(
                'header'   => $this->__('Thumb'),
                'index'    => 'photo_thumb',
                'renderer' => 'mpgallery/adminhtml_photo_grid_column_renderer_thumbs',
                'sortable' => false,
                'filter'   => false,
                'width'    => '60px'
            )
        );

        $actions   = array();
        $actions[] = array(
            'caption' => $this->helper('adminhtml')->__('Edit'),
            'url'     => $this->getUrl('*/*/edit', array('review_id' => '$review_id')),
        );
        $actions[] = array(
            'caption' => $this->helper('adminhtml')->__('Delete'),
            'url'     => $this->getUrl('*/*/delete', array('review_id' => '$review_id')),
            'confirm' => $this->__('Are you sure that you want to delete this review(s)?')
        );

        $this->addColumn('action',
            array(
                'header'    => $this->helper('adminhtml')->__('Action'),
                'index'     => 'action',
                'type'      => 'action',
                'width'     => '50px',
                'actions'   => $actions,
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('review_id');

        $this->getMassactionBlock()->setFormFieldName('reviewtable');
        $this->getMassactionBlock()->setSavePositionName('save_position');

        $statuses = Mage::getSingleton('mpgallery/source_reviewstatus')->toOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'      => Mage::helper('catalog')->__('Change status'),
            'url'        => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name'   => 'status',
                    'label'  => $this->helper('catalog')->__('Status'),
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'values' => $statuses
                )
            )
        ));

        $this->getMassactionBlock()
            ->addItem('delete',
                array(
                    'label'   => $this->helper('adminhtml')->__('Delete'),
                    'url'     => $this->getUrl('*/*/massDelete', array('_current' => true)),
                    'confirm' => $this->__('Are you sure that you want to delete this review(s)?')
                )
            );

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('review_id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/index', array('_current' => true));
    }
}
