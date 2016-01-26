<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Adminhtml_Catalog_Product_Edit_Library
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
        $this->setId('bird_product_grid');
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);

        $this->setDefaultFilter(array('is_birds' => ''));
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
        // Set custom filter for in bird flag
        if ($column->getId() == 'in_birds') {
            $birdIds = $this->_getSelectedBirds();
            if (empty($birdIds)) {
                $birdIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$birdIds));
            } else {
                if($birdIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$birdIds));
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
        /* @var $collection Vinehousefarm_Birdlibrary_Model_Resource_Bird_Collection */
        $collection = Mage::getModel('birdlibrary/bird')->getCollection();

        if ($this->isReadonly()) {
            $birdIds = $this->_getSelectedBirds();
            if (empty($birdIds)) {
                $birdIds = array(0);
            }
            $collection->addFieldToFilter('entity_id', array('in' => $birdIds));
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
            $this->addColumn('in_birds', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_birds',
                'values'            => $this->_getSelectedBirds(),
                'align'             => 'center',
                'index'             => 'entity_id'
            ));
        }

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('birdlibrary')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'entity_id'
        ));

        $this->addColumn('bird_name', array(
            'header'    => Mage::helper('birdlibrary')->__('Name'),
            'index'     => 'bird_name'
        ));

        $this->addColumn('latin_name', array(
            'header'    => Mage::helper('birdlibrary')->__('Latin Name'),
            'index'     => 'latin_name',
        ));

        $this->addColumn('position_bird', array(
            'header'            => Mage::helper('birdlibrary')->__(''),
            'name'              => 'position_bird',
            'type'              => 'number',
            'width'             => 60,
            'validate_class'    => 'validate-number',
            'index'             => 'position',
            'editable'          => true,
            'edit_only'         => true,
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
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/product/birdsGrid', array('_current'=>true));
    }

    /**
     * Retrieve selected birds
     *
     * @return array
     */
    protected function _getSelectedBirds()
    {
        $birds = $this->getProductBirds();

        if (!is_array($birds)) {
            /* @var $collection Vinehousefarm_Birdlibrary_Model_Resource_Product_Collection */
            $collection = Mage::getModel('birdlibrary/product')->getCollection()
                ->addFieldToFilter('product_id', array('in' => $this->_getProduct()->getId()));

            foreach ($collection as $item) {
                $birds[$item->getId()] = $item->getBirdId();
            }
        }

        return $birds;
    }

    /**
     * Retrieve crosssell products
     *
     * @return array
     */
    public function getSelectedBirds()
    {
        $birds = array();

        /* @var $collection Vinehousefarm_Birdlibrary_Model_Resource_Bird_Collection */
        $collection = Mage::getModel('birdlibrary/bird')->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $this->_getSelectedBirds()));

        foreach ($collection as $position => $bird) {
            $birds[$bird->getId()] = array('position' => 0);
        }

        return $birds;
    }

    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Bird Library');
    }

    /**
     * Retrieve the URL used to load the tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('*/product/birdsGrid', array('_current'=>true));
    }

    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view Bird library');
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