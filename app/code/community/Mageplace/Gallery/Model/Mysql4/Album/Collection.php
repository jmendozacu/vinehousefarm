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
 * Class Mageplace_Gallery_Model_Mysql4_Album_Collection
 */
class Mageplace_Gallery_Model_Mysql4_Album_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_isPreview = false;
    protected $_loadWithPhotoCount = false;
    protected $_photoTable;
    protected $_albumPhotoTable;
    protected $_productTable;

    protected function _construct()
    {
        $this->_init('mpgallery/album');

        if (null === $this->_idFieldName) {
            $this->_setIdFieldName($this->getResource()->getIdFieldName());
        }

        $this->_photoTable      = $this->getTable('mpgallery/photo');
        $this->_albumPhotoTable = $this->getTable('mpgallery/album_photo');
        $this->_productTable    = $this->getTable('mpgallery/album_product');
    }

    public function toOptionHash()
    {
        return $this->_toOptionHash('album_id', 'name');
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('album_id', 'name');
    }

    public function setLoadPhotoCount($flag)
    {
        $this->_loadWithPhotoCount = $flag;

        return $this;
    }

    public function load($printQuery = false, $logQuery = false)
    {
        parent::load($printQuery, $logQuery);

        if ($this->_loadWithPhotoCount) {
            $this->loadPhotoCount();
        }

        return $this;
    }

    public function loadPhotoCount($items = null)
    {
        if (null === $items) {
            $items = $this->_items;
            $ids   = array_keys($items);
        }

        if (!is_array($items)) {
            return $this;
        }

        if (empty($items)) {
            return $this;
        }

        if (!isset($ids)) {
            $ids = array();
            foreach ($items as $item) {
                $ids[] = $item->getId();
            }
        }

        $select = $this->getConnection()->select()
            ->from(
                array('main_table' => $this->_albumPhotoTable),
                array('album_id', new Zend_Db_Expr('COUNT(main_table.photo_id)'))
            )
            ->where($this->getConnection()->quoteInto('main_table.album_id IN(?)', $ids))
            ->group('main_table.album_id');
        $counts = $this->getConnection()->fetchPairs($select);

        foreach ($items as $item) {
            if (isset($counts[$item->getId()])) {
                $item->setPhotoCount($counts[$item->getId()]);
            } else {
                $item->setPhotoCount(0);
            }
        }

        return $this;
    }

    protected function _afterLoad()
    {
        if ($this->_isPreview) {
            $albums = $this->getColumnValues('album_id');
            if (count($albums)) {
                $select = $this->getConnection()
                    ->select()
                    ->from($this->getTable('mpgallery/album_store'))
                    ->where(
                        $this->getTable('mpgallery/album_store') . '.album_id IN (?)',
                        $albums
                    );

                if ($result = $this->getConnection()->fetchPairs($select)) {
                    foreach ($this as $item) {
                        $album_id = $item->getData('album_id');
                        if (!isset($result[$album_id])) {
                            continue;
                        }

                        if ($result[$album_id] == 0) {
                            $stores    = Mage::app()->getStores(false, true);
                            $storeId   = current($stores)->getId();
                            $storeCode = key($stores);
                        } else {
                            $storeId   = $result[$album_id];
                            $storeCode = Mage::app()->getStore($storeId)->getCode();
                        }

                        $item->setData('_first_store_id', $storeId);
                        $item->setData('store_code', $storeCode);
                    }
                }
            }
        }

        parent::_afterLoad();
    }

    /**
     * @param array|int $parentId
     *
     * @return $this
     */
    public function addParentFilter($parentId)
    {
        return $this->addFieldToFilter('parent_id', array('eq' => $parentId));
    }

    /**
     * @return $this
     */
    public function addIsActiveFilter()
    {
        return $this->addFieldToFilter('is_active', array('eq' => 1));
    }

    /**
     * @param null|int|Mage_Core_Model_Store $store Store to be filtered
     *
     * @return $this
     */
    public function addStoreFilter($store = null)
    {
        if (null === $store) {
            $store = Mage::app()->getStore()->getId();
        }

        if ($store instanceof Mage_Core_Model_Store) {
            $store = $store->getId();
        }

        $store = (int)$store;

        $this->getSelect()
            ->join(
                array('store_table' => $this->getTable('mpgallery/album_store')),
                'main_table.album_id = store_table.album_id',
                array()
            )
            ->where(
                'store_table.store_id IN (?)',
                array(0, $store)
            )
            ->group(
                'main_table.album_id'
            );

        return $this;
    }

    public function addCustomerGroupIdsFilter($customerGroupId)
    {
        $customerGroupId = (array)$customerGroupId;

        $this->getSelect()
            ->joinLeft(
                array('customer_group_table' => $this->getTable('mpgallery/album_customer_group')),
                'main_table.album_id = customer_group_table.album_id',
                array()
            )
            ->where(
                '((customer_group_table.group_id IN (?) AND main_table.only_for_registered = 1) OR main_table.only_for_registered = 0)',
                $customerGroupId
            )
            ->group(
                'main_table.album_id'
            );

        return $this;
    }

    public function addCustomerGroupFilter()
    {
        if (!Mage::helper('customer')->isLoggedIn()) {
            $this->addFieldToFilter('only_for_registered', array('eq' => 0));
        } else {
            $group_ids   = array();
            $group_ids[] = Mage::helper('customer')->getCustomer()->getGroupId();
            $this->addCustomerGroupIdsFilter($group_ids);
        }

        return $this;
    }

    public function addProductFilter($productIds)
    {
        if (!$productIds) {
            return $this;
        }

        if (!is_array($productIds)) {
            $productIds = (array)$productIds;
        }

        $this->getSelect()
            ->join(
                array('product_table' => $this->_productTable),
                'main_table.album_id = product_table.album_id',
                array()
            )
            ->where(
                'product_table.product_id IN (?)',
                array($productIds)
            )
            ->group(
                'main_table.album_id'
            );

        return $this;
    }

    /**
     * @param $albumIds
     *
     * @return $this
     */
    public function addIdFilter($albumIds)
    {
        $condition = '';
        if (is_array($albumIds)) {
            if (!empty($albumIds)) {
                $condition = array('in' => $albumIds);
            }
        } elseif (is_numeric($albumIds)) {
            $condition = $albumIds;
        } elseif (is_string($albumIds)) {
            $ids = explode(',', $albumIds);
            if (empty($ids)) {
                $condition = $albumIds;
            } else {
                $condition = array('in' => $ids);
            }
        }

        return $this->addFieldToFilter('main_table.album_id', $condition);
    }

    /**
     * @param Varien_Object|string $settings
     * @param string               $direction
     *
     * @return Varien_Data_Collection_Db
     */
    public function addOrder($settings, $direction = self::SORT_ORDER_DESC)
    {
        if ($settings instanceof Varien_Object) {
            if (!$sortBy = $settings->getAlbumDefaultSortBy()) {
                $sortBy = Mage::helper('mpgallery/config')->getDisplayAlbumSortBy();
            }

            switch ($sortBy) {
                case Mageplace_Gallery_Helper_Const::SORT_BY_NAME :
                case Mageplace_Gallery_Helper_Const::SORT_BY_POSITION :
                    $field = $sortBy;
                    break;

                default :
                    $field = 'position';
            }

            if (!$direction = $settings->getAlbumDefaultSortDir()) {
                $direction = Mage::helper('mpgallery/config')->getDisplayAlbumSortDir();
            }
        } else {
            $field = $settings;
        }

        return parent::addOrder($field, $direction);
    }

    public function getAllIdsSql()
    {
        $idsSelect = clone $this->getSelect();

        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);
        $idsSelect->reset(Zend_Db_Select::GROUP);

        return $idsSelect->columns($this->getIdFieldName());
    }

    public function getAllInactiveIds()
    {
        $idsSelect = $this->getAllIdsSql()
            ->where('is_active = ?', 0);

        return $this->getConnection()->fetchCol($idsSelect);
    }

    public function getAlbumsByCleanPath()
    {
        $select = $this->getConnection()
            ->select()
            ->from(
                array('main_table' => $this->getMainTable()),
                array('*', 'clean_path' => new Zend_Db_Expr('TRIM(TRAILING CONCAT("/", ' . $this->_idFieldName . ') FROM path)'))
            )
            ->where($this->getConnection()->quoteInto('main_table.album_id NOT IN(?)', Mageplace_Gallery_Model_Album::TREE_ROOT_ID))
            ->order(array('clean_path', 'position'));

        return $this->getConnection()->fetchAll($select);
    }

    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->reset(Zend_Db_Select::GROUP);

        $countSelect->columns('COUNT(DISTINCT(main_table.' . $this->_idFieldName . '))');

        return $countSelect;
    }

    public function getIds()
    {
        return (array)$this->getConnection()->fetchCol($this->getSelect()->columns($this->getIdFieldName()));
    }
}
