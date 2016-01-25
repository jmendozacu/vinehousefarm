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
 * Class Mageplace_Gallery_Model_Mysql4_Album_Tree
 */
class Mageplace_Gallery_Model_Mysql4_Album_Tree extends Varien_Data_Tree_Dbp
{
    const ID_FIELD    = 'album_id';
    const PATH_FIELD  = 'path';
    const ORDER_FIELD = 'position';
    const LEVEL_FIELD = 'level';

    /**
     * @var Mageplace_Gallery_Model_Mysql4_Album_Collection
     */
    protected $_collection;

    protected $_albumTableName;
    protected $_photoTableName;
    protected $_photoRelationsTableName;

    protected $_isActiveAttributeId = null;
    protected $_inactiveAlbumIds = array();
    protected $_inactiveItems = array();
    protected $_storeId = null;

    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');

        $this->_albumTableName          = $this->_table = $resource->getTableName('mpgallery/album');
        $this->_photoTableName          = $resource->getTableName('mpgallery/photo');
        $this->_photoRelationsTableName = $resource->getTableName('mpgallery/album_photo');

        parent::__construct(
            $resource->getConnection('core_write'),
            $this->_albumTableName,
            array(
                Varien_Data_Tree_Dbp::ID_FIELD    => self::ID_FIELD,
                Varien_Data_Tree_Dbp::PATH_FIELD  => self::PATH_FIELD,
                Varien_Data_Tree_Dbp::ORDER_FIELD => self::ORDER_FIELD,
                Varien_Data_Tree_Dbp::LEVEL_FIELD => self::LEVEL_FIELD,
            )
        );
    }

    /**
     * @param Mageplace_Gallery_Model_Mysql4_Album_Collection|null $collection
     * @param bool                                                 $sorted
     * @param array                                                $exclude
     * @param bool                                                 $toLoad
     * @param bool                                                 $onlyActive
     *
     * @return $this
     */
    public function addCollectionData($collection = null, $sorted = false, $exclude = array(), $toLoad = true, $onlyActive = false)
    {
        if (is_null($collection)) {
            $collection = $this->getCollection($sorted);
        } else {
            $this->setCollection($collection);
        }

        if (!is_array($exclude)) {
            $exclude = array($exclude);
        }

        $nodeIds = array();
        foreach ($this->getNodes() as $node) {
            if (!in_array($node->getId(), $exclude)) {
                $nodeIds[] = $node->getId();
            }
        }

        $collection->addIdFilter($nodeIds);
        if ($onlyActive) {
            $disabledIds = $this->_getDisabledIds($collection);
            if ($disabledIds) {
                $collection->addFieldToFilter('album_id', array('nin' => $disabledIds));
            }
            $collection->addFieldToFilter('is_active', 1);
        }

        if ($toLoad) {
            $collection->load();

            foreach ($collection as $album) {
                if ($this->getNodeById($album->getId())) {
                    $this->getNodeById($album->getId())
                        ->addData($album->getData());
                }
            }

            foreach ($this->getNodes() as $node) {
                if (!$collection->getItemById($node->getId()) && $node->getParent()) {
                    $this->removeNode($node);
                }
            }
        }

        return $this;
    }

    /**
     * @param array $ids
     *
     * @return $this
     */
    public function addInactiveAlbumIds($ids)
    {
        $this->_inactiveAlbumIds = array_merge($ids, $this->_inactiveAlbumIds);

        return $this;
    }

    /**
     * @return array
     */
    public function getInactiveAlbumIds()
    {
        return $this->_inactiveAlbumIds;
    }

    /**
     * @param Mageplace_Gallery_Model_Mysql4_Album_Collection $collection
     *
     * @return array
     */
    protected function _getDisabledIds($collection)
    {
        $this->_inactiveItems = $this->getInactiveAlbumIds();

        $this->_inactiveItems = array_merge(
            $this->_getInactiveItemIds($collection),
            $this->_inactiveItems
        );

        $allIds      = $collection->getAllIds();
        $disabledIds = array();

        foreach ($allIds as $id) {
            $parents = $this->getNodeById($id)->getPath();
            foreach ($parents as $parent) {
                if (!$this->_getItemIsActive($parent->getId())) {
                    $disabledIds[] = $id;
                    continue;
                }
            }
        }

        return $disabledIds;
    }

    /**
     * @param Mageplace_Gallery_Model_Mysql4_Album_Collection $collection
     *
     * @return array
     */
    protected function _getInactiveItemIds($collection)
    {
        return $collection->getAllInactiveIds();
    }

    /**
     * @param int $id
     *
     * @return boolean
     */
    protected function _getItemIsActive($id)
    {
        if (!in_array($id, $this->_inactiveItems)) {
            return true;
        }

        return false;
    }

    /**
     * @param boolean $sorted
     *
     * @return Mageplace_Gallery_Model_Mysql4_Album_Collection
     */
    public function getCollection($sorted = false)
    {
        if (is_null($this->_collection)) {
            $this->_collection = $this->_getDefaultCollection($sorted);
        }

        return $this->_collection;
    }

    /**
     * @param Mageplace_Gallery_Model_Mysql4_Album_Collection $collection
     *
     * @return $this
     */
    public function setCollection($collection)
    {
        if (!is_null($this->_collection)) {
            destruct($this->_collection);
        }

        $this->_collection = $collection;

        return $this;
    }

    /**
     * @param boolean|string $sorted
     *
     * @return Mageplace_Gallery_Model_Mysql4_Album_Collection
     */
    protected function _getDefaultCollection($sorted = false)
    {
        /** @var $collection Mageplace_Gallery_Model_Mysql4_Album_Collection */
        $collection = Mage::getModel('mpgallery/album')->getCollection();

        if ($sorted) {
            if (is_string($sorted)) {
                $collection->addOrder($sorted, Varien_Data_Collection::SORT_ORDER_ASC);
            } else {
                $collection->addOrder('name', Varien_Data_Collection::SORT_ORDER_ASC);
            }
        }

        return $collection;
    }

    /**
     * @param array $ids
     * @param bool  $addCollectionData
     * @param bool  $updatePhotoCount
     *
     * @return $this
     */
    public function loadByIds($ids, $addCollectionData = true, $updatePhotoCount = true)
    {
        $levelField = $this->_conn->quoteIdentifier(self::LEVEL_FIELD);
        $pathField  = $this->_conn->quoteIdentifier(self::PATH_FIELD);

        if (empty($ids)) {
            $select = $this->_conn->select()
                ->from($this->_table, 'album_id')
                ->where($levelField . ' <= 2');

            $ids = $this->_conn->fetchCol($select);
        }

        if (!is_array($ids)) {
            $ids = array($ids);
        }

        foreach ($ids as $key => $id) {
            $ids[$key] = (int)$id;
        }

        $select = $this->_conn->select()
            ->from($this->_table, array(self::PATH_FIELD, self::LEVEL_FIELD))
            ->where('album_id IN (?)', $ids);

        $where[] = $levelField . ' = 0';

        foreach ($this->_conn->fetchAll($select) as $item) {
            $pathIds = explode('/', $item[self::PATH_FIELD]);
            $level   = (int)$item[self::LEVEL_FIELD];
            while ($level > 0) {
                $pathIds[count($pathIds) - 1] = '%';
                $path                         = implode('/', $pathIds);
                $where[]                      = "$levelField = $level AND $pathField LIKE '$path'";
                array_pop($pathIds);
                $level--;
            }
        }

        if ($addCollectionData) {
            $select = $this->_createCollectionDataSelect();
        } else {
            $select = clone $this->_select;
            $select->order($this->_orderField . ' ' . Varien_Db_Select::SQL_ASC);
        }
        $select->where(implode(' OR ', $where));
        $arrNodes = $this->_conn->fetchAll($select);
        if (!$arrNodes) {
            return false;
        }

        /*
        if ($updatePhotoCount) {
            $this->_updatePhotoCount($arrNodes);
        }
        */

        $childrenItems = array();
        foreach ($arrNodes as $key => $nodeInfo) {
            $pathToParent = explode('/', $nodeInfo[$this->_pathField]);
            array_pop($pathToParent);
            $pathToParent = implode('/', $pathToParent);

            $childrenItems[$pathToParent][] = $nodeInfo;
        }
        $this->addChildNodes($childrenItems, '', null);

        return $this;
    }

    /**
     * @param string $path
     * @param bool   $addCollectionData
     * @param bool   $withRootNode
     *
     * @return array
     */
    public function loadBreadcrumbsArray($path, $addCollectionData = true, $withRootNode = false)
    {
        $pathIds = explode('/', $path);
        if (!$withRootNode) {
            array_shift($pathIds);
        }
        $result = array();
        if (!empty($pathIds)) {
            if ($addCollectionData) {
                $select = $this->_createCollectionDataSelect(false);
            } else {
                $select = clone $this->_select;
            }
            $select
                ->where('main_table.album_id IN (?)', $pathIds)
                ->order(new Zend_Db_Expr('LENGTH(main_table.path) ' . Varien_Db_Select::SQL_ASC));
            $result = $this->_conn->fetchAll($select);

            /*$this->_updatePhotoCount($result);*/
        }

        return $result;
    }

    /**
     * @param array $data
     */
    protected function _updatePhotoCount(&$data)
    {
        foreach ($data as $key => $row) {
            $data[$key]['photo_count'] = $row['self_photo_count'];
        }
    }

    /**
     * @param bool $sorted
     *
     * @return Zend_Db_Select
     */
    protected function _createCollectionDataSelect($sorted = true)
    {
        $select = $this->_getDefaultCollection($sorted ? $this->_orderField : false)
            ->getSelect();

        $photoCountSelect = $this->_conn
            ->select()
            ->from(array('at' => $this->_albumTableName), null)
            ->joinLeft(
                array('prt' => $this->_photoRelationsTableName),
                'at.album_id = prt.album_id',
                array(new Zend_Db_Expr('COUNT(DISTINCT prt.photo_id)')))
            ->where('at.album_id = main_table.album_id')
            ->orWhere('at.path LIKE CONCAT(main_table.path, \'/%\')');

        $select->columns(array('photo_count' => new Zend_Db_Expr('(' . $photoCountSelect->__toString() . ')')));

        $selfPhotoCountSelect = $this->_conn->select()
            ->from(array('sprt' => $this->_photoRelationsTableName), 'COUNT(sprt.photo_id)')
            ->where('sprt.album_id = main_table.album_id');

        $select->columns(array('self_photo_count' => new Zend_Db_Expr('(' . $selfPhotoCountSelect->__toString() . ')')));

        return $select;
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    public function getExistingAlbumIdsBySpecifiedIds($ids)
    {
        if (empty($ids)) {
            return array();
        }

        if (!is_array($ids)) {
            $ids = array($ids);
        }

        $select = $this->_conn->select()
            ->from($this->_table, array('album_id'))
            ->where('album_id IN (?)', $ids);

        return $this->_conn->fetchCol($select);
    }
}