<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Adminhtml_Library_Edit_Tab_Links
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
        $this->setId('link_section');
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
        return $this->getUrl('*/*/links', array('_current' => true));
    }

    /**
     * Return products.
     *
     * @return string
     */
    public function getBirdsJson()
    {
        $birds = $this->_getSelectedBirds();
        if (!empty($birds)) {
            return Mage::helper('core')->jsonEncode((object)$birds);
        }

        return '{}';
    }

    protected function _getModel()
    {
        return Mage::registry('current_data');
    }

    protected function _getHelper()
    {
        return Mage::helper('birdlibrary');
    }

    /**
     * Return products attached to the location.
     *
     * @return mixed
     */
    protected function _getSelectedBirds()
    {
        $birds = $this->getRequest()->getPost('links');

        if (is_null($birds) && $this->_getModel() instanceof Varien_Object) {
            /**
             * @var $collection Vinehousefarm_Birdlibrary_Model_Resource_Bird_Collection
             */
            $collection = Mage::getModel('birdlibrary/link')->getCollection()
                ->addFieldToFilter('bird_id', $this->_getModel()->getId());

            foreach ($collection as $item) {
                $birds[$item->getLinkId()] = $item->getLinkId();
            }
        }

        return $birds;
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('birdlibrary')->__('Similar birds');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('birdlibrary')->__('Similar birds / birds in the same family?');
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
     * Prepare collection for Grid
     *
     * @return Belvg_Storelocator_Block_Adminhtml_Store_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('birdlibrary/bird')->getCollection();
            //->addFieldToFilter('entity_id', array('neq', $this->_getModel()->getId()));

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
        $this->addColumn('in_links', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_location',
            'values' => $this->_getSelectedBirds(),
            'align' => 'center',
            'index' => 'entity_id',
        ));

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('birdlibrary')->__('ID'),
            'sortable' => true,
            'width' => '60',
            'index' => 'entity_id',
        ));

        $this->addColumn('bird_name', array(
            'header' => Mage::helper('birdlibrary')->__('Name'),
            'index' => 'bird_name',
        ));

        $this->addColumn('latin_name', array(
            'header' => Mage::helper('birdlibrary')->__('Latin Name'),
            'width' => '80',
            'index' => 'latin_name',
        ));

        return parent::_prepareColumns();
    }
}