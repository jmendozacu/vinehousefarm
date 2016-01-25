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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Grid
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('photo_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('photo_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setMassactionBlockName('mpgallery/adminhtml_photo_grid_massaction');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('mpgallery/photo_collection');
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');

        parent::_afterLoadCollection();
    }

    protected function _prepareColumns()
    {
        $albumId = $this->getAlbumId();

        $this->addColumn('photo_id',
            array(
                'header'       => $this->__('ID'),
                'index'        => 'photo_id',
                'filter_index' => 'main_table.photo_id',
                'width'        => '80px',
                'type'         => 'number',
            )
        );

        $this->addColumn('photos',
            array(
                'header'   => $this->__('Photo'),
                'index'    => 'image',
                'renderer' => 'mpgallery/adminhtml_photo_grid_column_renderer_thumbs',
                'sortable' => false,
                'filter'   => false,
                'width'    => '60px'
            )
        );

        $this->addColumn('name',
            array(
                'header'       => $this->__('Name'),
                'index'        => 'name',
                'filter_index' => 'main_table.name',
            )
        );

        $this->addColumn('url_key',
            array(
                'header'       => $this->__('URL key'),
                'index'        => 'url_key',
                'filter_index' => 'main_table.url_key',
            )
        );

        $this->addColumn('only_for_registered',
            array(
                'header'  => $this->__('Only for registered'),
                'index'   => 'only_for_registered',
                'type'    => 'options',
                'width'   => '70px',
                'options' => $this->_getYesNo()
            )
        );

        $this->addColumn('author_name',
            array(
                'header'       => $this->__('Author Name'),
                'index'        => 'author_name',
                'filter_index' => 'main_table.author_name',
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id',
                array(
                    'header'                    => Mage::helper('cms')->__('Store view'),
                    'index'                     => 'store_id',
                    'type'                      => 'store',
                    'store_all'                 => true,
                    'store_view'                => true,
                    'sortable'                  => false,
                    'filter_condition_callback' => array(
                        $this,
                        '_filterStoreCondition'
                    )
                )
            );
        }

        $this->addColumn('album_ids',
            array(
                'header'                    => $this->__('Album'),
                'index'                     => 'album_ids',
                'type'                      => 'options',
                'options'                   => $this->_getAlbums(),
                'renderer'                  => 'mpgallery/adminhtml_photo_grid_column_renderer_albums',
                'sortable'                  => false,
                'filter_condition_callback' => array(
                    $this,
                    '_filterAlbumCondition'
                )
            )
        );

        $this->addColumn('is_active',
            array(
                'header'       => $this->__('Status'),
                'index'        => 'is_active',
                'filter_index' => 'main_table.is_active',
                'type'         => 'options',
                'width'        => '70px',
                'options'      => $this->_getStatuses()
            )
        );

        $columnPosition = array(
            'header'     => Mage::helper('catalog')->__('Position'),
            'index'      => 'position',
            'type'       => 'number',
            'album'      => $albumId,
            'width'      => '1',
            'editable'   => 1,
            'inline_css' => 'massaction-position-input',
            'renderer'   => 'mpgallery/adminhtml_photo_grid_column_renderer_position',
        );
        if (null === $albumId) {
            $columnPosition['filter']   = false;
            $columnPosition['sortable'] = false;
        }
        $this->addColumn('position', $columnPosition);

        $actions = array();
        if (null !== $albumId) {
            $actions[] = array(
                'caption'  => $this->__('Save position'),
                'url'      => $this->getUrl('*/*/save', array('id' => '$photo_id', 'position' => '')),
                'position' => 1
            );
        }

        $actions[] = array(
            'caption' => Mage::helper('adminhtml')->__('Edit'),
            'url'     => $this->getUrl('*/*/edit', array('id' => '$photo_id')),
        );

        $actions[] = array(
            'caption' => Mage::helper('adminhtml')->__('Delete'),
            'url'     => $this->getUrl('*/*/delete', array('id' => '$photo_id')),
            'confirm' => $this->__('Are you sure that you want to delete this photo(s)?')
        );

        $this->addColumn('action',
            array(
                'header'    => $this->__('Action'),
                'index'     => 'action',
                'type'      => 'action',
                'width'     => '50px',
                'actions'   => $actions,
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
                'renderer'  => 'mpgallery/adminhtml_photo_grid_column_renderer_action',
            )
        );

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('photo_id');

        $this->getMassactionBlock()->setFormFieldName('phototable');
        $this->getMassactionBlock()->setSavePositionName('save_position');
        $this->getMassactionBlock()->setAlbumIdFilterName('album_ids');
        $this->getMassactionBlock()->setPositionFieldName('position');


        $statuses = Mage::getSingleton('mpgallery/source_photostatus')->toOptionArray();
        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'      => Mage::helper('catalog')->__('Change status'),
            'url'        => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'status_visibility' => array(
                    'name'   => 'status',
                    'label'  => Mage::helper('catalog')->__('Status'),
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'values' => $statuses,
                )
            )
        ));

        $this->getMassactionBlock()
            ->addItem('delete',
                array(
                    'label'   => Mage::helper('adminhtml')->__('Delete'),
                    'url'     => $this->getUrl('*/*/massDelete', array('_current' => true)),
                    'confirm' => $this->__('Are you sure that you want to delete this photo(s)?')
                )
            );

        $this->getMassactionBlock()
            ->addItem('save_position',
                array(
                    'label' => $this->__('Save positions'),
                    'url'   => $this->getUrl('*/*/massSave', array('_current' => true))
                )
            );

        $albums = $this->_getAlbums();
        if ($albums) {
            $albumValues = array(array('value' => null, 'label' => ''));
            foreach ($albums as $value => $label) {
                $albumValues[] = array('value' => $value, 'label' => $label);
            }

            $this->getMassactionBlock()->addItem('move', array(
                'label'      => $this->__('Move to album'),
                'url'        => $this->getUrl('*/*/massMove', array('_current' => true)),
                'additional' => array(
                    'move_visibility' => array(
                        'name'   => 'album',
                        'label'  => $this->__('Album'),
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'values' => $albumValues
                    )
                )
            ));

            $this->getMassactionBlock()->addItem('copy', array(
                'label'      => $this->__('Copy to album'),
                'url'        => $this->getUrl('*/*/massCopy', array('_current' => true)),
                'additional' => array(
                    'copy_visibility' => array(
                        'name'   => 'album',
                        'label'  => $this->__('Album'),
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'values' => $albumValues
                    )
                )
            ));
        }

        return $this;
    }

    protected function getAlbumId()
    {
        if (false === $this->hasData('album_id')) {
            $filter = $this->getParam($this->getVarNameFilter(), null);
            if (!empty($filter)) {
                if (is_string($filter)) {
                    $filter = $this->helper('adminhtml')->prepareFilterString($filter);
                }

                if (is_array($filter) && !empty($filter['album_ids'])) {
                    $albumId = $filter['album_ids'];
                }
            }

            $this->setData('album_id', isset($albumId) ? $albumId : null);
        }

        return $this->_getData('album_id');
    }

    protected function _getAlbums()
    {
        return Mage::getSingleton('mpgallery/source_albums')->toOptionArray();
    }

    protected function _getStatuses()
    {
        return Mage::getSingleton('mpgallery/source_photostatus')->toOptionHash();
    }

    protected function _getYesNo()
    {
        return array(
            0 => Mage::helper('cms')->__('No'),
            1 => Mage::helper('cms')->__('Yes')
        );
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    protected function _filterAlbumCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addAlbumFilter($value);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/index', array('_current' => true));
    }
}
