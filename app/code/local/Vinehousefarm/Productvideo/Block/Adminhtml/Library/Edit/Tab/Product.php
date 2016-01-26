<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Productvideo_Block_Adminhtml_Library_Edit_Tab_Product
    extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Init Grid default properties
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('product_section');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Grid url getter.
     *
     * @return string current grid url
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/products', array('_current' => true));
    }

    /**
     * Return products.
     *
     * @return string
     */
    public function getProductsJson()
    {
        $products = $this->_getSelectedProducts();
        if (!empty($products)) {
        return Mage::helper('core')->jsonEncode((object)$products);
    }

        return '{}';
    }

    /**
     * Return products attached to the location.
     *
     * @return mixed
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('products');

        if (is_null($products) && $this->_getModel() instanceof Varien_Object) {
            /**
             * @var $collection Vinehousefarm_Productvideo_Model_Resource_Product_Collection
             */
            $collection = Mage::getModel('productvideo/product')->getCollection()
                ->addFieldToFilter('video_id', $this->_getModel()->getId());

            foreach ($collection as $item) {
                $products[$item->getProductId()] = $item->getProductId();
            }
        }

        return $products;
    }

    protected function _getModel()
    {
        return Mage::registry('current_data');
    }

    protected function _getHelper()
    {
        return Mage::helper('productvideo');
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_getHelper()->__('Products');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_getHelper()->__('Products');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Added for current location products.
     *
     * @param $column
     *
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_location') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }

            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Prepare collection for Grid
     *
     * @return Belvg_Storelocator_Block_Adminhtml_Store_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addStoreFilter($this->getRequest()->getParam('store'));

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare Grid columns
     *
     * @return Mage_Adminhtml_Block_Catalog_Search_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_location', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_location',
            'values' => $this->_getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id',
        ));

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('productvideo')->__('ID'),
            'sortable' => true,
            'width' => '60',
            'index' => 'entity_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('productvideo')->__('Name'),
            'index' => 'name',
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('productvideo')->__('SKU'),
            'width' => '80',
            'index' => 'sku',
        ));

        $this->addColumn('position', array(
            'header'            => Mage::helper('productvideo')->__('Position'),
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
}