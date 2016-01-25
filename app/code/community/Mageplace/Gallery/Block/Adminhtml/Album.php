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
 * Class Mageplace_Gallery_Block_Adminhtml_Album
 */
class Mageplace_Gallery_Block_Adminhtml_Album extends Mage_Adminhtml_Block_Template
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return Mageplace_Gallery_Model_Album
     */
    public function getAlbum()
    {
        return Mage::registry('album');
    }

    public function isRoot()
    {
        return $this->getAlbum()->isRoot();
    }

    public function getAlbumId()
    {
        if ($this->getAlbum()) {
            return (int)$this->getAlbum()->getId();
        }

        return (int)Mageplace_Gallery_Model_Album::TREE_ROOT_ID;
    }

    public function getRealAlbumId()
    {
        return (int)$this->getAlbum()->getId();
    }

    public function getAlbumName($id = null)
    {
        if (null === $id) {
            return $this->getAlbum()->getName();
        }

        $album = Mage::getModel('mpgallery/album')->load($id);

        return $album->getId() > 0 ? $album->getName() : '';
    }

    public function getAlbumPath()
    {
        if ($this->getAlbum()) {
            return $this->getAlbum()->getPath();
        }

        return Mageplace_Gallery_Model_Album::TREE_ROOT_ID;
    }

    public function getRoot($parentNode = null, $recLevel = 3)
    {
        if (!is_null($parentNode) && $parentNode->getId()) {
            return $this->getNode($parentNode, $recLevel);
        }

        $root = Mage::registry('root');
        if (is_null($root)) {
            $rootId = Mageplace_Gallery_Model_Album::TREE_ROOT_ID;

            $tree = Mage::getResourceSingleton('mpgallery/album_tree')
                ->load(null, $recLevel);

            if ($this->getAlbum()) {
                $tree->loadEnsuredNodes($this->getAlbum(), $tree->getNodeById($rootId));
            }

            $tree->addCollectionData($this->getAlbumCollection());

            $root = $tree->getNodeById($rootId);

            //$root->setName($this->__('Root Album'));
            $root->setIsVisible(true);

            Mage::register('root', $root);
        }

        return $root;
    }

    public function getRootByIds($ids)
    {
        $root = Mage::registry('root');
        if (null === $root) {
            $treeResource = Mage::getResourceSingleton('mpgallery/album_tree');
            $ids          = $treeResource->getExistingAlbumIdsBySpecifiedIds($ids);
            $tree         = $treeResource->loadByIds($ids);
            $rootId       = Mageplace_Gallery_Model_Album::TREE_ROOT_ID;
            $root         = $tree->getNodeById($rootId);
            $root->setName($this->__('Root Album'));
            $root->setIsVisible(false);

            $tree->addCollectionData($this->getAlbumCollection());
            Mage::register('root', $root);
        }

        return $root;
    }

    public function getNode($parentNodeAlbum, $recursionLevel = 2)
    {
        $tree = Mage::getResourceModel('mpgallery/album_tree');

        $nodeId   = $parentNodeAlbum->getId();
        $parentId = $parentNodeAlbum->getParentId();

        $node = $tree->loadNode($nodeId);
        $node->loadChildren($recursionLevel);

        if ($node && $nodeId != Mageplace_Gallery_Model_Album::TREE_ROOT_ID) {
            $node->setIsVisible(true);
        } elseif ($node && $node->getId() == Mageplace_Gallery_Model_Album::TREE_ROOT_ID) {
            $node->setName($this->__('Root Album'));
            $node->setIsVisible(false);
        }

        $tree->addCollectionData($this->getAlbumCollection());

        return $node;
    }

    public function getSaveUrl(array $args = array())
    {
        $params = array('_current' => true);
        $params = array_merge($params, $args);

        return $this->getUrl('*/gallery_album/save', $params);
    }

    public function getEditUrl()
    {
        return $this->getUrl("*/gallery_album/edit", array('_current' => true, '_query' => false, 'id' => null, 'parent' => null));
    }

    public function getRootIds()
    {
        $ids = $this->getData('root_ids');
        if (is_null($ids)) {
            $ids = array();
            foreach (Mage::app()->getGroups() as $store) {
                $ids[] = $store->getRootCategoryId();
            }
            $this->setData('root_ids', $ids);
        }

        return $ids;
    }
}
