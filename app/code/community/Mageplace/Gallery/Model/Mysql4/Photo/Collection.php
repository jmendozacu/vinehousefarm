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
 * Class Mageplace_Gallery_Model_Mysql4_Photo_Collection
 */
class Mageplace_Gallery_Model_Mysql4_Photo_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_storeTable;
    protected $_albumPhotoTable;
    protected $_customerGroupTable;
    protected $_productTable;
    protected $_isPreview;
    protected $_addAlbums = false;

    protected function _construct()
    {
        $this->_init('mpgallery/photo');

        if (null === $this->_idFieldName) {
            $this->_setIdFieldName($this->getResource()->getIdFieldName());
        }

        $this->_storeTable         = $this->getTable('mpgallery/photo_store');
        $this->_albumPhotoTable    = $this->getTable('mpgallery/album_photo');
        $this->_customerGroupTable = $this->getTable('mpgallery/photo_customer_group');
        $this->_productTable       = $this->getTable('mpgallery/album_product');
    }

    public function addAlbumsToGridCollection()
    {
        $this->_addAlbums = true;

        return $this;
    }

    public function toOptionHash()
    {
        return $this->_toOptionHash($this->getIdFieldName());
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray($this->getIdFieldName());
    }

    /**
     * @return $this
     */
    public function addIsActiveFilter()
    {
        return $this->addFieldToFilter('main_table.is_active', array('eq' => Mageplace_Gallery_Model_Photo::APPROVED));
    }

    /**
     * @param int $parentId
     *
     * @return $this
     */
    public function addParentFilter($parentId)
    {
        return $this->addAlbumFilter($parentId);
    }

    /**
     * @param Mageplace_Gallery_Model_Album|mixed $album Album to be filtered
     *
     * @return $this
     */
    public function addAlbumFilter($album)
    {
        if ($album instanceof Mageplace_Gallery_Model_Album) {
            $album = $album->getId();
        }

        $album = (int)$album;

        $select = $this->getSelect()
            ->where(
                'album_table.album_id IN (?)',
                array($album)
            )
            ->group(
                'main_table.' . $this->_idFieldName
            );

        if (!$this->_addAlbums) {
            $select->join(
                array('album_table' => $this->_albumPhotoTable),
                'main_table.' . $this->_idFieldName . ' = album_table.' . $this->_idFieldName,
                array()
            );
        }

        return $this;
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
                array('store_table' => $this->_storeTable),
                'main_table.' . $this->_idFieldName . ' = store_table.' . $this->_idFieldName,
                array()
            )->where(
                'store_table.store_id IN (?)',
                array(0, $store)
            )->group(
                'main_table.' . $this->_idFieldName
            );

        return $this;
    }

    /**
     * @param array $customerGroupId Customer group ids to be filtered
     *
     * @return $this
     */
    public function addCustomerGroupIdsFilter($customerGroupId)
    {
        $customerGroupId = (array)$customerGroupId;

        $this->getSelect()
            ->joinLeft(
                array('customer_group_table' => $this->_customerGroupTable),
                'main_table.' . $this->_idFieldName . ' = customer_group_table.' . $this->_idFieldName,
                array()
            )
            ->where(
                '((customer_group_table.group_id IN (?) AND main_table.only_for_registered = 1) OR main_table.only_for_registered = 0)',
                $customerGroupId
            )
            ->group(
                'main_table.' . $this->_idFieldName
            );


        return $this;
    }

    /**
     * @return $this
     */
    public function addCustomerGroupFilter()
    {
        if (!Mage::helper('customer')->isLoggedIn()) {
            return $this->addFieldToFilter('main_table.only_for_registered', array('eq' => 0));
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
                array('album_table' => $this->_albumPhotoTable),
                'main_table.' . $this->_idFieldName . ' = album_table.' . $this->_idFieldName,
                array('album_table.position')
            )
            ->join(
                array('product_table' => $this->_productTable),
                'album_table.album_id = product_table.album_id',
                array()
            )
            ->where(
                'product_table.product_id IN (?)',
                array($productIds)
            )
            ->group(
                'main_table.' . $this->_idFieldName
            );

        return $this;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function addCustomerFilter($id)
    {
        return $this->addFieldToFilter('main_table.customer_id', array('eq' => $id));
    }

    public function addAlbumOrder($order, $dir)
    {
        $this->getSelect()->reset(Zend_Db_Select::ORDER)
            ->order(new Zend_Db_Expr($order . ' ' . $dir));

        return $this;
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

    public function getAllOrderedIds()
    {
        $idsSelect = clone $this->getSelect();

        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);

        $idsSelect->columns($this->getIdFieldName(), 'main_table');
        return $this->getConnection()->fetchCol($idsSelect);
    }

    public function getIds()
    {
        return (array)$this->getConnection()->fetchCol($this->getSelect()->columns($this->getIdFieldName()));
    }

    /**
     * @return $this
     */
    protected function _beforeLoad()
    {
        if ($this->_addAlbums) {
            $this->getSelect()
                ->join(
                    array('album_table' => $this->_albumPhotoTable),
                    'main_table.' . $this->_idFieldName . ' = album_table.' . $this->_idFieldName,
                    array()
                );
        }

        return parent::_beforeLoad();
    }

    /**
     * @return $this
     */
    protected function _afterLoad()
    {
        if ($this->_isPreview) {
            $items = $this->getColumnValues($this->_idFieldName);
            if (count($items)) {
                $select = $this->getConnection()
                    ->select()
                    ->from($this->_storeTable)
                    ->where('main_table.' . $this->_idFieldName . ' IN (?)', $items);

                if ($result = $this->getConnection()->fetchPairs($select)) {
                    foreach ($this as $item) {
                        $id = $item->getData($this->_idFieldName);
                        if (!isset($result[$id])) {
                            continue;
                        }

                        if ($result[$id] == 0) {
                            $stores    = Mage::app()->getStores(false, true);
                            $storeId   = current($stores)->getId();
                            $storeCode = key($stores);
                        } else {
                            $storeId   = $result[$id];
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
}