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
 * Class Mageplace_Gallery_Model_Mysql4_Album
 */
class Mageplace_Gallery_Model_Mysql4_Album extends Mageplace_Gallery_Model_Mysql4_Abstract
{
    protected static $DESIGN_FIELDS = array(
        'design_custom' => null,
        'page_layout'   => null
    );

    protected static $DISPLAY_FIELDS = array(
        'display_mode'                       => null,
        'display_order'                      => null,
        'cms_block'                          => null,
        'album_display_toolbar_top'          => null,
        'album_display_toolbar_bottom'       => null,
        'album_display_type'                 => null,
        'album_available_sort_by'            => null,
        'album_default_sort_by'              => null,
        'album_default_sort_dir'             => null,
        'album_grid_column_count'            => null,
        'album_simple_column_count'          => null,
        'photo_display_toolbar_top'          => null,
        'photo_display_toolbar_bottom'       => null,
        'photo_display_type'                 => null,
        'photo_available_sort_by'            => null,
        'photo_default_sort_by'              => null,
        'photo_default_sort_dir'             => null,
        'photo_grid_per_page'                => null,
        'photo_list_per_page'                => null,
        'photo_simple_per_page'              => null,
        'photo_grid_column_count'            => null,
        'photo_list_column_count'            => null,
        'photo_simple_column_count'          => null,
        'photo_grid_pager_limit'             => null,
        'photo_list_pager_limit'             => null,
        'photo_simple_pager_limit'           => null,
        'photo_view_display_mode'            => null,
        'photo_view_list_sort_by'            => null,
        'photo_view_list_sort_dir'           => null,
        'photo_view_list_per_page'           => null,
        'album_view_display_image'           => null,
        'album_view_display_name'            => null,
        'album_view_display_update_date'     => null,
        'album_view_display_short_descr'     => null,
        'album_view_display_descr'           => null,
        'album_grid_display_name'            => null,
        'album_list_display_name'            => null,
        'album_simple_display_name'          => null,
        'album_grid_display_short_descr'     => null,
        'album_list_display_short_descr'     => null,
        'album_simple_display_short_descr'   => null,
        'album_grid_display_update_date'     => null,
        'album_list_display_update_date'     => null,
        'album_simple_display_update_date'   => null,
        'album_grid_display_show_link'       => null,
        'album_list_display_show_link'       => null,
        'album_simple_display_show_link'     => null,
        'photo_view_display_name'            => null,
        'photo_view_display_update_date'     => null,
        'photo_view_display_short_descr'     => null,
        'photo_view_display_descr'           => null,
        'photo_view_display_back_url'        => null,
        'photo_grid_display_name'            => null,
        'photo_list_display_name'            => null,
        'photo_simple_display_name'          => null,
        'photo_carousel_display_name'        => null,
        'photo_grid_display_short_descr'     => null,
        'photo_list_display_short_descr'     => null,
        'photo_simple_display_short_descr'   => null,
        'photo_carousel_display_short_descr' => null,
        'photo_grid_display_update_date'     => null,
        'photo_list_display_update_date'     => null,
        'photo_simple_display_update_date'   => null,
        'photo_carousel_display_update_date' => null,
        'photo_grid_display_show_link'       => null,
        'photo_list_display_show_link'       => null,
        'photo_simple_display_show_link'     => null,
        'photo_carousel_display_show_link'   => null,
        'photo_view_display_review'          => null,
        'photo_grid_display_rate'            => null,
        'photo_list_display_rate'            => null,
        'photo_simple_display_rate'          => null,
        'photo_carousel_display_rate'        => null,
    );

    protected static $SIZE_FIELDS = array(
        'album_size'                => null,
        'album_grid_thumb_size'     => null,
        'album_list_thumb_size'     => null,
        'album_simple_thumb_size'   => null,
        'photo_size'                => null,
        'photo_grid_thumb_size'     => null,
        'photo_list_thumb_size'     => null,
        'photo_simple_thumb_size'   => null,
        'photo_carousel_thumb_size' => null,
    );

    protected static $RELATED_FIELDS = array(
        'album_display_toolbar'     => array(
            'album_display_toolbar_top',
            'album_display_toolbar_bottom',
        ),
        'album_display_name'        => array(
            'album_grid_display_name',
            'album_list_display_name',
            'album_simple_display_name',
        ),
        'album_display_update_date' => array(
            'album_grid_display_update_date',
            'album_list_display_update_date',
            'album_simple_display_update_date',
        ),
        'album_display_short_descr' => array(
            'album_grid_display_short_descr',
            'album_list_display_short_descr',
            'album_simple_display_short_descr',
        ),
        'album_display_show_link'   => array(
            'album_grid_display_show_link',
            'album_list_display_show_link',
            'album_simple_display_show_link',
        ),
        'photo_display_toolbar'     => array(
            'photo_display_toolbar_top',
            'photo_display_toolbar_bottom',
        ),
        'photo_display_name'        => array(
            'photo_grid_display_name',
            'photo_list_display_name',
            'photo_simple_display_name',
            'photo_carousel_display_name'
        ),
        'photo_display_update_date' => array(
            'photo_grid_display_update_date',
            'photo_list_display_update_date',
            'photo_simple_display_update_date',
            'photo_carousel_display_update_date',
        ),
        'photo_display_short_descr' => array(
            'photo_grid_display_short_descr',
            'photo_list_display_short_descr',
            'photo_simple_display_short_descr',
            'photo_carousel_display_short_descr',
        ),
        'photo_display_show_link'   => array(
            'photo_grid_display_show_link',
            'photo_list_display_show_link',
            'photo_simple_display_show_link',
            'photo_carousel_display_show_link',
        ),
        'photo_display_rate'        => array(
            'photo_grid_display_rate',
            'photo_list_display_rate',
            'photo_simple_display_rate',
            'photo_carousel_display_rate'
        ),
    );

    protected function _construct()
    {
        $this->_init('mpgallery/album', 'album_id');

        $this->_storeTable         = $this->getTable('mpgallery/album_store');
        $this->_customerGroupTable = $this->getTable('mpgallery/album_customer_group');
        $this->_productTable       = $this->getTable('mpgallery/album_product');
        $this->_albumTable         = $this->getMainTable();
        $this->_photoTable         = $this->getTable('mpgallery/photo');
    }

    protected function _helper()
    {
        return Mage::helper('mpgallery/album');
    }

    public function getDisplayFields()
    {
        return self::$DISPLAY_FIELDS;
    }

    public function getDesignFields()
    {
        return self::$DESIGN_FIELDS;
    }

    public function getSizeFields()
    {
        return self::$SIZE_FIELDS;
    }

    /**
     * @param Mageplace_Gallery_Model_Album|Mage_Core_Model_Abstract $object
     *
     * @return $this
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        parent::_afterLoad($object);

        if (!$object->getId()) {
            return $this;
        }

        $availableSortBy = $object->getData('album_available_sort_by');
        if (null !== $availableSortBy && !is_array($availableSortBy)) {
            if ('' === $availableSortBy) {
                $availableSortBy = array();
            } else {
                $availableSortBy = explode(',', $availableSortBy);
            }

            $object->setData('album_available_sort_by', $availableSortBy);
        }

        $availableSortBy = $object->getData('photo_available_sort_by');
        if (null !== $availableSortBy && !is_array($availableSortBy)) {
            if ('' === $availableSortBy || null === $availableSortBy) {
                $availableSortBy = array();
            } else {
                $availableSortBy = explode(',', $availableSortBy);
            }

            $object->setData('photo_available_sort_by', $availableSortBy);
        }
    }

    /**
     * @param Mageplace_Gallery_Model_Album|Mage_Core_Model_Abstract $object
     *
     * @return $this
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);

        if ($object->getLevel() === null) {
            $object->setLevel(1);
        }

        if (!$object->getId()) {
            $object->setPosition($this->_getMaxPosition($object->getPath()) + 1);

            $path  = explode('/', $object->getPath());
            $level = count($path);
            $object->setLevel($level);
            if ($level) {
                $object->setParentId($path[$level - 1]);
            }
            $object->setPath($object->getPath() . '/');

            $toUpdateChild = explode('/', $object->getPath());

            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array('children_count' => new Zend_Db_Expr('children_count + 1')),
                array($this->getIdFieldName() . ' IN (?)' => $toUpdateChild)
            );
        }

        if ($availableSortBy = $object->getData('album_available_sort_by')) {
            if (!is_array($availableSortBy)) {
                $availableSortBy = explode(',', $availableSortBy);
            }

            if (in_array('0', $availableSortBy, true)) {
                $availableSortBy = array('0');
            }

            $object->setData('album_available_sort_by', implode(',', $availableSortBy));
        }

        if ($availableSortBy = $object->getData('photo_available_sort_by')) {
            if (!is_array($availableSortBy)) {
                $availableSortBy = explode(',', $availableSortBy);
            }

            if (in_array('0', $availableSortBy, true)) {
                $availableSortBy = array('0');
            }

            $object->setData('photo_available_sort_by', implode(',', $availableSortBy));
        }

        foreach (self::$RELATED_FIELDS as $field => $relFields) {
            if (!$fieldValue = $object->getData($field)) {
                foreach ($relFields as $relField) {
                    $object->setData($relField, $fieldValue);
                }
            }
        }

        if ($object->hasData('display_order_excl_block')) {
            $object->setData('display_order', $object->getData('display_order_excl_block'));
        } elseif ($object->hasData('display_order_excl_album')) {
            $object->setData('display_order', $object->getData('display_order_excl_album'));
        } elseif ($object->hasData('display_order_excl_photo')) {
            $object->setData('display_order', $object->getData('display_order_excl_photo'));
        }

        return $this;
    }

    /**
     * @param Mageplace_Gallery_Model_Album|Mage_Core_Model_Abstract $object
     *
     * @return $this
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);

        if (substr($object->getPath(), -1) == '/') {
            $object->setPath($object->getPath() . $object->getId());
            $this->_savePath($object);
        }

        return $this;
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeDelete($object);

        $parentIds = $object->getParentIds();
        if ($parentIds) {
            $childDecrease = $object->getChildrenCount() + 1;
            $data          = array('children_count' => new Zend_Db_Expr('children_count - ' . $childDecrease));
            $where         = array('album_id IN(?)' => $parentIds);
            $this->_getWriteAdapter()->update($this->getMainTable(), $data, $where);
        }

        $this->deleteChildren($object);

        return $this;
    }

    protected function _getMaxPosition($path)
    {
        $read = $this->getReadConnection();

        $level = count(explode('/', $path));
        $bind  = array(
            'c_level' => $level,
            'c_path'  => $path . '/%'
        );

        $select = $read->select()
            ->from($this->getMainTable(), 'MAX(' . $read->quoteIdentifier('position') . ')')
            ->where($read->quoteIdentifier('path') . ' LIKE :c_path')
            ->where($read->quoteIdentifier('level') . ' = :c_level');

        $position = $read->fetchOne($select, $bind);
        if (!$position) {
            $position = 0;
        }

        return $position;
    }

    protected function _savePath($object)
    {
        if ($object->getId()) {
            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array('path' => $object->getPath()),
                array('album_id = ?' => $object->getId())
            );
        }

        return $this;
    }

    protected function _processPositions($album, $newParent, $afterAlbumId)
    {
        $table         = $this->getMainTable();
        $adapter       = $this->_getWriteAdapter();
        $positionField = $adapter->quoteIdentifier('position');

        $bind  = array(
            'position' => new Zend_Db_Expr($positionField . ' - 1')
        );
        $where = array(
            'parent_id = ?'         => $album->getParentId(),
            $positionField . ' > ?' => $album->getPosition()
        );
        $adapter->update($table, $bind, $where);

        if ($afterAlbumId) {
            $select = $adapter->select()
                ->from($table, 'position')
                ->where('album_id = :album_id');

            $position = $adapter->fetchOne($select, array('album_id' => $afterAlbumId));

            $bind = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );

            $where = array(
                'parent_id = ?'         => $newParent->getId(),
                $positionField . ' > ?' => $position
            );

            $adapter->update($table, $bind, $where);
        } elseif ($afterAlbumId !== null) {
            $position = 0;
            $bind     = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );

            $where = array(
                'parent_id = ?'         => $newParent->getId(),
                $positionField . ' > ?' => $position
            );

            $adapter->update($table, $bind, $where);
        } else {
            $select = $adapter->select()
                ->from($table, array('position' => new Zend_Db_Expr('MIN(' . $positionField . ')')))
                ->where('parent_id = :parent_id');

            $position = $adapter->fetchOne($select, array('parent_id' => $newParent->getId()));
        }

        $position += 1;

        return $position;
    }

    public function deleteChildren(Varien_Object $object)
    {
        $write     = $this->_getWriteAdapter();
        $pathField = $write->quoteIdentifier('path');

        $select = $write->select()
            ->from($this->getMainTable(), array('album_id'))
            ->where($pathField . ' LIKE :c_path');

        $childrenIds = $write->fetchCol($select, array('c_path' => $object->getPath() . '/%'));

        if (!empty($childrenIds)) {
            $write->delete(
                $this->getMainTable(),
                array('album_id IN (?)' => $childrenIds)
            );
        }

        $object->setDeletedChildrenIds($childrenIds);

        return $this;
    }

    public function changeParent(Mageplace_Gallery_Model_Album $album, Mageplace_Gallery_Model_Album $newParent, $afterAlbumId = null)
    {
        $childrenCount = $this->getChildrenCount($album->getId()) + 1;
        $table         = $this->getMainTable();
        $adapter       = $this->_getWriteAdapter();
        $levelFiled    = $adapter->quoteIdentifier('level');
        $pathField     = $adapter->quoteIdentifier('path');

        $adapter->update(
            $table,
            array('children_count' => new Zend_Db_Expr('children_count - ' . $childrenCount)),
            array('album_id IN(?)' => $album->getParentIds())
        );

        $adapter->update(
            $table,
            array('children_count' => new Zend_Db_Expr('children_count + ' . $childrenCount)),
            array('album_id IN(?)' => $newParent->getPathIds())
        );

        $position = $this->_processPositions($album, $newParent, $afterAlbumId);

        $newPath          = sprintf('%s/%s', $newParent->getPath(), $album->getId());
        $newLevel         = $newParent->getLevel() + 1;
        $levelDisposition = $newLevel - $album->getLevel();

        $adapter->update(
            $table,
            array(
                'path'  => new Zend_Db_Expr('REPLACE(' . $pathField . ',' .
                        $adapter->quote($album->getPath() . '/') . ', ' . $adapter->quote($newPath . '/') . ')'
                    ),
                'level' => new Zend_Db_Expr($levelFiled . ' + ' . $levelDisposition)
            ),
            array($pathField . ' LIKE ?' => $album->getPath() . '/%')
        );

        $data = array(
            'path'      => $newPath,
            'level'     => $newLevel,
            'position'  => $position,
            'parent_id' => $newParent->getId()
        );
        $adapter->update($table, $data, array('album_id = ?' => $album->getId()));

        $album->addData($data);

        return $this;
    }

    public function getChildrenCount($albumId)
    {
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getMainTable(), 'children_count')
            ->where('album_id = :album_id');

        $bind = array('album_id' => $albumId);

        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * @param Mageplace_Gallery_Model_Album $album
     * @param int                           $isActiveFlag
     *
     * @return string
     */
    public function getChildrenAlbumCount(Mageplace_Gallery_Model_Album $album, $isActiveFlag = 1)
    {
        $adapter = $this->_getReadAdapter();
        $storeId = Mage::app()->getStore()->getId();

        $select = $adapter->select()
            ->from(
                array('m' => $this->getMainTable()),
                array('COUNT(m.' . $this->getIdFieldName() . ')'))
            ->join(
                array('s' => $this->_storeTable),
                's.' . $this->getIdFieldName() . ' = m.' . $this->getIdFieldName(),
                array())
            ->where('s.store_id IN (?)', array('0', $storeId))
            ->where('m.path LIKE ?', $album->getPath() . '/%')
            ->where('m.is_active = ?', $isActiveFlag);

        return $adapter->fetchOne($select);
    }

    public function getAlbumNameById($id)
    {
        /* @var Zend_Db_Select $select */
        $select = $this->_getReadAdapter()
            ->select()
            ->from(array('main_table' => $this->getMainTable()), 'name')
            ->where('main_table.album_id = ?', $id);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function getAlbumByUrlKey($urlKey)
    {
        /* @var Zend_Db_Select $select */
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getMainTable(), array('album_id'))
            ->where('url_key = ?', $urlKey);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function getPhotoCount(Mageplace_Gallery_Model_Album $album)
    {
        $read   = $this->_getReadAdapter();
        $select = $read->select()
            ->from(
                array('main_table' => $read->getTableName('mpgallery/album_photo')),
                array(new Zend_Db_Expr('COUNT(main_table.photo_id)'))
            )
            ->where($this->getIdFieldName() . ' = :album_id');

        $bind   = array('album_id' => (int)$album->getId());
        $counts = $read->fetchOne($select, $bind);

        return intval($counts);
    }

    public function getParentAlbums(Mageplace_Gallery_Model_Album $album, $notIncludeLevel1 = true)
    {
        $pathIds = $album->getPathIds();

        $albums = $album->getCollection()
            ->addFieldToFilter($this->getIdFieldName(), array('in' => $pathIds));

        if ($notIncludeLevel1) {
            $albums->addFieldToFilter('level', array('neq' => 0));
        }

        return $albums->getItems();
    }

    /**
     * @param Mageplace_Gallery_Model_Album $album
     *
     * @return Mageplace_Gallery_Model_Mysql4_Album_Collection
     */
    public function getActiveParentCollection(Mageplace_Gallery_Model_Album $album)
    {
        return $album->getCollection()
            ->addIsActiveFilter()
            ->addStoreFilter()
            ->addCustomerGroupFilter()
            ->addIdFilter($album->getPathIds());
    }

    public function getActiveParentAlbums(Mageplace_Gallery_Model_Album $album)
    {
        return $this->getActiveParentCollection($album)
            ->addOrder('level', 'ASC')
            ->getItems();
    }

    public function getActiveParentAlbumIds(Mageplace_Gallery_Model_Album $album)
    {
        $select = $this->getActiveParentCollection($album)
            ->getSelect()
            ->columns($this->getIdFieldName(), 'main_table')
            ->order('level ASC');

        return $this->_getReadAdapter()->fetchCol($select);
    }

    public function getParentAlbumDesign($album)
    {
        return $this->_getParentSettings($album, 'design_use_parent_settings');
    }

    public function getParentAlbumDisplay($album)
    {
        return $this->_getParentSettings($album, 'display_use_parent_settings');
    }

    public function getParentAlbumSize($album)
    {
        return $this->_getParentSettings($album, 'size_use_parent_settings');
    }

    protected function _getParentSettings(Mageplace_Gallery_Model_Album $album, $field)
    {
        $pathIds = array_reverse($album->getPaths());

        $collection = $album->getCollection()
            ->addFieldToFilter($this->getIdFieldName(), array('in' => $pathIds))
            ->addFieldToFilter($field, array(array('eq' => 0), array('null' => 0)))
            ->setOrder('level', 'DESC');

        return $collection->getFirstItem();
    }


    public function saveProductRelation($productId, $albumIds)
    {
        if (!$productId) {
            return false;
        }

        $condition = $this->_getWriteAdapter()->quoteInto('product_id = ?', $productId);
        $this->_getWriteAdapter()->delete($this->_productTable, $condition);

        if (!is_array($albumIds)) {
            $albumIds = explode(',', $albumIds);
        }

        $albumIds = array_unique($albumIds);
        foreach ($albumIds as $albumId) {
            $albumId = (int)$albumId;
            if (!$albumId) {
                continue;
            }

            $this->_getWriteAdapter()->insert(
                $this->_productTable,
                array(
                    $this->getIdFieldName() => $albumId,
                    'product_id'            => $productId,
                )
            );
        }

    }
}
