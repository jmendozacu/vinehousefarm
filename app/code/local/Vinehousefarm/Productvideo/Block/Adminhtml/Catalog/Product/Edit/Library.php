<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Productvideo_Block_Adminhtml_Catalog_Product_Edit_Library
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
        $this->setId('video_product_grid');
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);

        $this->setDefaultFilter(array('is_videos' => ''));
    }

    protected function _prepareLayout()
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add Video'),
                    'onclick'   => $this->getJsObjectName().'.addvideo()',
                    'class'   => 'task'
                ))
        );

        return parent::_prepareLayout();
    }

    /**
     * Retirve currently edited product model
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Add filter
     *
     * @param object $column
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Library
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in video flag
        if ($column->getId() == 'in_videos') {
            $videoIds = $this->_getSelectedVideos();
            if (empty($videoIds)) {
                $videoIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$videoIds));
            } else {
                if($videoIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$videoIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        /* @var $collection Vinehousefarm_Productvideo_Model_Resource_Video_Collection */
        $collection = Mage::getModel('productvideo/video')->getCollection();

        if ($this->isReadonly()) {
            $videoIds = $this->_getSelectedVideos();
            if (empty($videoIds)) {
                $videoIds = array(0);
            }
            $collection->addFieldToFilter('entity_id', array('in' => $videoIds));
        }


        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return false;
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        if (!$this->isReadonly()) {
            $this->addColumn('in_videos', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_videos',
                'values'            => $this->_getSelectedVideos(),
                'align'             => 'center',
                'index'             => 'entity_id'
            ));
        }

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('productvideo')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'entity_id'
        ));

        $this->addColumn('video_name', array(
            'header'    => Mage::helper('productvideo')->__('Name'),
            'index'     => 'video_name'
        ));

        $this->addColumn('video_code', array(
            'header'    => Mage::helper('productvideo')->__('Code'),
            'index'     => 'video_code',
        ));

        $this->addColumn('position', array(
            'header'            => Mage::helper('productvideo')->__(''),
            'name'              => 'position',
            'type'              => 'number',
            'width'             => 60,
            'validate_class'    => 'validate-number',
            'index'             => 'position',
            'editable'          => true,
            'edit_only'         => true
        ));

        return parent::_prepareColumns();
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/product/videosGrid', array('_current'=>true));
    }

    /**
     * Retrieve selected videos
     *
     * @return array
     */
    protected function _getSelectedVideos()
    {
        $videos = $this->getProductVideos();

        if (!is_array($videos)) {
            /* @var $collection Vinehousefarm_Productvideo_Model_Resource_Product_Collection */
            $collection = Mage::getModel('productvideo/product')->getCollection()
                ->addFieldToFilter('product_id', array('in' => $this->_getProduct()->getId()));

            foreach ($collection as $item) {
                $videos[$item->getId()] = $item->getVideoId();
            };
        }

        return $videos;
    }

    /**
     * Retrieve video products
     *
     * @return array
     */
    public function getSelectedVideos()
    {
        $videos = array();

        /* @var $collection Vinehousefarm_Productvideo_Model_Resource_Video_Collection */
        $collection = Mage::getModel('productvideo/video')->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $this->_getSelectedVideos()));

        foreach ($collection as $position => $video) {
            $videos[$video->getId()] = array('position' => 0);
        }
        return $videos;
    }

    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Video Library');
    }

    /**
     * Retrieve the URL used to load the tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('*/product/videosGrid', array('_current'=>true));
    }

    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view Video library');
    }

    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
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

    /**
     * Retrieve the class name of the tab
     *
     * return string
     */
    public function getTabClass()
    {
        return 'ajax';
    }

    public function getSkipGenerateContent()
    {
        return true;
    }
}