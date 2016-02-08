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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tree_Checkboxes
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tree_Checkboxes extends Mageplace_Gallery_Block_Adminhtml_Album_Tree
{
    protected $_albumIds = array();
    protected $_selectedNodes = null;

    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('mpgallery/album/tree/checkboxes.phtml');
    }

    /**
     * @return array
     */
    protected function getAlbumIds()
    {
        if($this->_albumIds) {
            return (array)$this->_albumIds;
        } elseif ($this->hasData('album_ids')) {
            return (array)$this->getData('album_ids');
        } else {
            return array();
        }
    }

    public function addAlbumIds($albumId)
    {
        if(is_array($albumId)) {
            $this->_albumIds = array_merge($this->_albumIds, $albumId);
        } else {
            array_push($this->_albumIds, $albumId);
        }

        return $this;
    }

    public function getIdsString()
    {
        return implode(',', $this->getAlbumIds());
    }

    /**
     * @return Varien_Data_Tree_Node
     */
    public function getRootNode()
    {
        $root = $this->getRoot();
        if ($root && in_array($root->getId(), $this->getAlbumIds())) {
            $root->setChecked(true);
        }

        return $root;
    }

    /**
     * @param Mageplace_Gallery_Model_Album|null $parentNodeAlbum
     * @param int                                $recursionLevel
     *
     * @return Varien_Data_Tree_Node
     */
    public function getRoot($parentNodeAlbum = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeAlbum) && $parentNodeAlbum->getId()) {
            return $this->getNode($parentNodeAlbum, $recursionLevel);
        }

        $root = Mage::registry('root');
        if (is_null($root)) {
            $rootId = Mageplace_Gallery_Model_Album::TREE_ROOT_ID;

            $ids  = $this->getSelectedAlbumsPathIds($rootId);
            $tree = Mage::getResourceSingleton('mpgallery/album_tree')
                ->loadByIds($ids, false, false);

            if ($this->getAlbum()) {
                $tree->loadEnsuredNodes($this->getAlbum(), $tree->getNodeById($rootId));
            }

            $tree->addCollectionData($this->getAlbumCollection());

            $root = $tree->getNodeById($rootId);

            $root->setIsVisible(true);
            //$root->setName($this->__('Root Album'));

            Mage::register('root', $root);
        }

        return $root;
    }

    /**
     * @param Varien_Data_Tree_Node $node
     * @param int                   $level
     *
     * @return array
     */
    protected function _getNodeJson($node, $level = 1)
    {
        $item = parent::_getNodeJson($node, $level);

        if ($this->_isParentSelectedAlbum($node)) {
            $item['expanded'] = true;
        }

        if (in_array($node->getId(), $this->getAlbumIds())) {
            $item['checked'] = true;
        }

        return $item;
    }

    /**
     * @param Varien_Data_Tree_Node $node
     *
     * @return bool
     */
    protected function _isParentSelectedAlbum($node)
    {
        $allChildren = $node->getAllChildren();
        if ($allChildren) {
            $selectedAlbumIds = $this->getAlbumIds();

            $allChildrenArr = explode(',', $allChildren);
            for ($i = 0, $count = count($selectedAlbumIds); $i < $count; $i++) {
                $isSelf = $node->getId() == $selectedAlbumIds[$i];
                if (!$isSelf && in_array($selectedAlbumIds[$i], $allChildrenArr)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function _getSelectedNodes()
    {
        if ($this->_selectedNodes === null) {
            $this->_selectedNodes = array();

            $root = $this->getRoot();
            foreach ($this->getAlbumIds() as $albumId) {
                if ($root) {
                    $this->_selectedNodes[] = $root->getTree()->getNodeById($albumId);
                }
            }
        }

        return $this->_selectedNodes;
    }

    public function getAlbumChildrenJson($albumId)
    {
        $album = Mage::getModel('mpgallery/album')->load($albumId);
        $node  = $this->getRoot($album, 1)->getTree()->getNodeById($albumId);

        if (!$node || !$node->hasChildren()) {
            return '[]';
        }

        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->_getNodeJson($child);
        }

        return Zend_Json::encode($children);
    }

    public function getLoadTreeUrl($expanded = null)
    {
        return $this->getUrl('*/gallery_album/albumCheckboxJson', array('_current' => true));
    }

    public function getSelectedAlbumsPathIds($rootId = false)
    {
        $ids = array();

        $albumIds = $this->getAlbumIds();
        if (empty($albumIds)) {
            return array();
        }

        $collection = Mage::getResourceModel('mpgallery/album_collection');

        if ($rootId) {
            $collection->addFieldToFilter('parent_id', $rootId);
        } else {
            $collection->addFieldToFilter('album_id', array('in' => $albumIds));
        }

        foreach ($collection as $item) {
            if ($rootId && !in_array($rootId, $item->getPathIds())) {
                continue;
            }

            foreach ($item->getPathIds() as $id) {
                if (!in_array($id, $ids)) {
                    $ids[] = $id;
                }
            }
        }

        return $ids;
    }
}
