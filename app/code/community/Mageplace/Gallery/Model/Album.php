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
 * Class Mageplace_Gallery_Model_Album
 *
 * @method Mageplace_Gallery_Model_Album setName
 * @method Mageplace_Gallery_Model_Album setIsActive
 * @method Mageplace_Gallery_Model_Album setDescription
 * @method Mageplace_Gallery_Model_Album setShortDescription
 * @method Mageplace_Gallery_Model_Album setUrlKey
 * @method Mageplace_Gallery_Model_Album setCreationDate
 * @method Mageplace_Gallery_Model_Album setUpdateDate
 * @method Mageplace_Gallery_Model_Album setChildrenCount
 * @method Mageplace_Gallery_Model_Album setLevel
 * @method Mageplace_Gallery_Model_Album setPosition
 * @method Mageplace_Gallery_Model_Album setPath
 * @method Mageplace_Gallery_Model_Album setOnlyForRegistered
 * @method Mageplace_Gallery_Model_Album setParentId
 * @method string getName
 * @method datetime getUpdateDate
 * @method string getDescription
 * @method string getShortDescription
 * @method string getUrlKey
 * @method string getPath
 * @method string getIsActive
 * @method int getOnlyForRegistered
 * @method int getChildrenCount
 * @method int getLevel
 * @method int getPosition
 * @method int getCmsBlock
 * @method int getDisplayMode
 * @method int getDisplayOrder
 * @method int getAlbumColumnCount
 * @method bool hasPhotoCount
 * @method array getStoreId
 * @method array getCustomerGroupIds
 * @method string getMetaDescription
 * @method string getMetaKeywords
 * @method Mageplace_Gallery_Model_Album getDesignUseParentSettings
 * @method Mageplace_Gallery_Model_Album getDesignApplyToPhotos
 * @method Mageplace_Gallery_Model_Album getDisplayUseParentSettings
 * @method string getPageTitle
 */
class Mageplace_Gallery_Model_Album extends Mageplace_Gallery_Model_Abstract
{
    const CACHE_TAG = 'mpgallery_album';

    const TREE_ROOT_ID = 1;

    const BLOCK = 'cms_block';
    const ALBUM = 'album';
    const PHOTO = 'photo';

    const DISPLAY_MODE_ALBUM_AND_PHOTO           = 0;
    const DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO = 1;
    const DISPLAY_MODE_BLOCK_AND_ALBUM           = 2;
    const DISPLAY_MODE_BLOCK_AND_PHOTO           = 3;
    const DISPLAY_MODE_ALBUM_ONLY                = 4;
    const DISPLAY_MODE_PHOTO_ONLY                = 5;
    const DISPLAY_MODE_BLOCK_ONLY                = 6;

    const DISPLAY_POSITION_BLOCK_ALBUM_PHOTO = 0;
    const DISPLAY_POSITION_BLOCK_PHOTO_ALBUM = 1;
    const DISPLAY_POSITION_ALBUM_PHOTO_BLOCK = 2;
    const DISPLAY_POSITION_ALBUM_BLOCK_PHOTO = 3;
    const DISPLAY_POSITION_PHOTO_ALBUM_BLOCK = 4;
    const DISPLAY_POSITION_PHOTO_BLOCK_ALBUM = 5;

    protected static $DISPLAY_MODES = array(
        self::BLOCK => array(
            self::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO,
            self::DISPLAY_MODE_BLOCK_AND_ALBUM,
            self::DISPLAY_MODE_BLOCK_AND_PHOTO,
            self::DISPLAY_MODE_BLOCK_ONLY
        ),

        self::ALBUM => array(
            self::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO,
            self::DISPLAY_MODE_ALBUM_AND_PHOTO,
            self::DISPLAY_MODE_BLOCK_AND_ALBUM,
            self::DISPLAY_MODE_ALBUM_ONLY
        ),

        self::PHOTO => array(
            self::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO,
            self::DISPLAY_MODE_ALBUM_AND_PHOTO,
            self::DISPLAY_MODE_BLOCK_AND_PHOTO,
            self::DISPLAY_MODE_PHOTO_ONLY
        )
    );

    protected static $DISPLAY_ORDERS = array(
        self::DISPLAY_POSITION_BLOCK_ALBUM_PHOTO => array(self::BLOCK, self::ALBUM, self::PHOTO),
        self::DISPLAY_POSITION_BLOCK_PHOTO_ALBUM => array(self::BLOCK, self::PHOTO, self::ALBUM),
        self::DISPLAY_POSITION_ALBUM_PHOTO_BLOCK => array(self::ALBUM, self::PHOTO, self::BLOCK),
        self::DISPLAY_POSITION_ALBUM_BLOCK_PHOTO => array(self::ALBUM, self::BLOCK, self::PHOTO),
        self::DISPLAY_POSITION_PHOTO_ALBUM_BLOCK => array(self::PHOTO, self::ALBUM, self::BLOCK),
        self::DISPLAY_POSITION_PHOTO_BLOCK_ALBUM => array(self::PHOTO, self::BLOCK, self::ALBUM),
    );


    protected function _construct()
    {
        parent::_construct();

        $this->_init('mpgallery/album');
    }

    public function helper()
    {
        return Mage::helper('mpgallery/album');
    }

    public function isRoot()
    {
        return $this->getId() == self::TREE_ROOT_ID;
    }

    public function isStoreRoot()
    {
        return $this->getId() == Mage::helper('mpgallery/config')->getRootAlbum();
    }

    public function isActive()
    {
        return (bool)$this->getIsActive();
    }

    public function getPathIds()
    {
        $ids = $this->getData('path_ids');
        if (null === $ids) {
            $ids = $this->getPaths();
            array_shift($ids);
            $this->setData('path_ids', $ids);
        }

        return $ids;
    }

    public function getPaths()
    {
        $ids = $this->getData('paths');
        if (null === $ids) {
            $ids = explode('/', $this->getPath());
            $this->setData('paths', $ids);
        }

        return $ids;
    }

    public function getPhotoCount()
    {
        if (!$this->hasPhotoCount()) {
            $count = $this->_getResource()->getPhotoCount($this);
            $this->setData('photo_count', $count);
        }

        return $this->getData('photo_count');
    }

    public function getParentId()
    {
        $parentIds = $this->getParentIds();

        return intval(array_pop($parentIds));
    }

    public function getParentIds()
    {
        return array_diff($this->getPathIds(), array($this->getId()));
    }

    /**
     * @return array
     */
    public function getParentAlbums()
    {
        return $this->getResource()->getParentAlbums($this);
    }

    /**
     * @return array
     */
    public function getActiveParentAlbums()
    {
        return $this->getResource()->getActiveParentAlbums($this);
    }

    public function getActiveParentAlbumIds()
    {
        return $this->getResource()->getActiveParentAlbumIds($this);
    }

    public function move($parentId, $afterCategoryId)
    {
        $parent = Mage::getModel('mpgallery/album')
            ->load($parentId);

        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('mpgallery')->__('Album move operation is not possible: the new parent album was not found')
            );
        }

        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('mpgallery')->__('Album move operation is not possible: the current album was not found')
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('mpgallery')->__('Album move operation is not possible: parent album is equal to child album')
            );
        }

        $this->_getResource()->beginTransaction();

        try {
            $this->getResource()->changeParent($this, $parent, $afterCategoryId);

            $this->_getResource()->commit();

            $this->setAffectedAlbumIds(array($this->getId(), $this->getParentId(), $parentId));

            $moveComplete = true;
        } catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }

        if ($moveComplete) {
            Mage::app()->cleanCache(array(self::CACHE_TAG));
        }

        return $this;
    }

    public function getLevelNames()
    {
        $names = array();
        $this->_getNodeList($names);

        return $names;
    }

    public function getActiveLevelNames()
    {
        $names = array();
        $this->_getActiveNodeList($names);

        return $names;
    }

    public function getNodes()
    {
        return $this->_getNode();
    }

    public function hasChildren()
    {
        return $this->_getResource()->getChildrenAlbumCount($this) > 0;
    }

    public function getDesignSettings()
    {
        if (null === $this->_getData('design_settings')) {
            if ($this->getDesignUseParentSettings()) {
                $album = $this->getResource()->getParentAlbumDesign($this);
                if ($id = $album->getId()) {
                    $album = Mage::getModel('mpgallery/album')->load($id);
                }
            } else {
                $album = $this;
            }

            $this->setData('design_settings',
                new Varien_Object(array_intersect_key($album->getData(), $this->_getResource()->getDesignFields())));
        }

        return $this->_getData('design_settings');
    }

    public function getDisplaySettings()
    {
        if (null === $this->_getData('display_settings')) {
            if ($this->getDisplayUseParentSettings()) {
                $album = $this->getResource()->getParentAlbumDisplay($this);
            } else {
                $album = $this;
            }

            $this->setData('display_settings',
                new Mageplace_Gallery_Model_Settings(array_intersect_key($album->getData(), $this->_getResource()->getDisplayFields())));
        }

        return $this->_getData('display_settings');
    }

    public function getSizeSettings()
    {
        if (null === $this->_getData('size_settings')) {
            if ($this->getSizeUseParentSettings()) {
                $album = $this->getResource()->getParentAlbumSize($this);
            } else {
                $album = $this;
            }

            $this->setData('size_settings',
                new Mageplace_Gallery_Model_Settings(array_intersect_key($album->getData(), $this->_getResource()->getSizeFields())));
        }

        return $this->_getData('size_settings');
    }

    public function canDisplayCmsBlock()
    {
        return $this->canDisplay(self::BLOCK);
    }

    public function canDisplayAlbum()
    {
        return $this->canDisplay(self::ALBUM);
    }

    public function canDisplayPhoto()
    {
        return $this->canDisplay(self::PHOTO);
    }

    public function canDisplay($object)
    {
        return in_array((int)$this->getData('display_mode'), self::$DISPLAY_MODES[$object]);
    }

    public function getDisplayOrderArray()
    {
        $order = self::$DISPLAY_ORDERS[(int)$this->getData('display_order')];
        foreach ($order as $key => $value) {
            if (!$this->canDisplay($value)) {
                unset($order[$key]);
            }
        }

        return $order;
    }

    public function getAlbumIdsByProduct($productId)
    {
        if(is_object($productId)) {
            $productId = $productId->getId();
        }

        $productId = (int)$productId;

        if(!$productId) {
            return array();
        }

        return $this->getCollection()
            ->addProductFilter($productId)
            ->addIsActiveFilter()
            ->addStoreFilter()
            ->addCustomerGroupFilter()
            ->getIds();
    }

    /**
     * @param Varien_Data_Tree_Node|null $node
     * @param int                        $level
     *
     * @return array
     */
    protected function _getNode($node = null, $level = 0)
    {
        if (null === $node) {
            $node = Mage::getResourceSingleton('mpgallery/album_tree')
                ->load(null)
                ->getNodeById(Mageplace_Gallery_Model_Album::TREE_ROOT_ID);
        }

        $item         = array();
        $item['id']   = $node->getId();
        $item['text'] = $node->getName();

        if ((int)$node->getChildrenCount() > 0) {
            $item['children'] = array();
        }

        if ($node->hasChildren()) {
            $item['children'] = array();
            foreach ($node->getChildren() as $child) {
                $item['children'][] = $this->_getNode($child, $level + 1);
            }
        }

        return $item;
    }

    protected function _getNodeList(array &$item, $node = null, $level = 0)
    {
        if (null === $node) {
            $node = Mage::getResourceSingleton('mpgallery/album_tree')
                ->load(null)
                ->getNodeById(Mageplace_Gallery_Model_Album::TREE_ROOT_ID);
        }

        $item[$node->getId()] = str_repeat('-', $level) . ' ' . $node->getName();

        if ($node->hasChildren()) {
            foreach ($node->getChildren() as $child) {
                $this->_getNodeList($item, $child, $level + 1);
            }
        }

        return $item;
    }

    protected function _getActiveNodeList(array &$item, $node = null, $level = 0)
    {
        if (null === $node) {
            $node = Mage::getResourceSingleton('mpgallery/album_tree')
                ->load(null)
                ->getNodeById(Mageplace_Gallery_Model_Album::TREE_ROOT_ID);
        }

        $album = Mage::getModel('mpgallery/album')->load($node->getAlbumId());
        if(Mage::helper('mpgallery/album')->canShow($album)) {
            $item[$album->getUrlKey()] = str_repeat('--', $level ++) . ' ' . $album->getName();
        }

        if ($node->hasChildren()) {
            foreach ($node->getChildren() as $child) {
                $this->_getActiveNodeList($item, $child, $level);
            }
        }

        return $item;
    }
}
