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
class Mageplace_Gallery_Block_Adminhtml_Album_Tree extends Mageplace_Gallery_Block_Adminhtml_Album
{
    protected $_withPhotoCount = true;

    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('mpgallery/album/tree.phtml');
        $this->setUseAjax(true);
    }

    protected function _prepareLayout()
    {
        $addUrl = $this->getUrl("*/gallery_album/add", array(
            '_current' => true,
            'id'       => null,
            '_query'   => false
        ));

        $this->setChild('add_root_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'   => $this->__('Add Root Album'),
                    'onclick' => "addNewAlbum('" . $addUrl . "', true)",
                    'class'   => 'add',
                    'id'      => 'add_root_album_button'
                ))
        );

        $this->setChild('add_child_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'   => $this->__('Add Child Album'),
                    'onclick' => "addNewAlbum('" . $addUrl . "', false)",
                    'class'   => 'add',
                    'id'      => 'add_child_album_button',
                ))
        );

        return parent::_prepareLayout();
    }

    protected function _getDefaultStoreId()
    {
        return Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID;
    }

    public function getAlbumCollection()
    {
        $collection = $this->getData('album_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('mpgallery/album')->getCollection();

            /** @var $collection Mageplace_Gallery_Model_Mysql4_Album_Collection */
            $collection->setLoadPhotoCount($this->_withPhotoCount);

            $this->setData('album_collection', $collection);
        }

        return $collection;
    }

    public function getAddRootAlbumButtonHtml()
    {
        return $this->getChildHtml('add_root_button');
    }

    public function getAddChildAlbumButtonHtml()
    {
        return $this->getChildHtml('add_child_button');
    }

    public function getLoadTreeUrl($expanded = null)
    {
        $params = array('_current' => true, 'id' => null, 'store' => null);
        if ((null === $expanded && $this->getIsWasExpanded()) || $expanded == true) {
            $params['expand_all'] = true;
        }

        return $this->getUrl('*/gallery_album/albumsJson', $params);
    }

    public function getSwitchTreeUrl()
    {
        return $this->getUrl('*/gallery_album/tree', array('_current' => true, '_query' => false, 'id' => null, 'parent' => null));
    }

    public function getIsWasExpanded()
    {
        return Mage::getSingleton('admin/session')->getIsGalleryTreeWasExpanded();
    }

    public function getMoveUrl()
    {
        return $this->getUrl('*/gallery_album/move');
    }

    /**
     * @param Mageplace_Gallery_Model_Album|null $parentNode
     *
     * @return array
     */
    public function getTree($parentNode = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNode));
        $tree      = isset($rootArray['children']) ? $rootArray['children'] : array();

        return $tree;
    }

    /**
     * @param Mageplace_Gallery_Model_Album|null $parentNode
     *
     * @return string
     */
    public function getTreeJson($parentNode = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNode));
        $json      = Zend_Json::encode(isset($rootArray['children']) ? $rootArray['children'] : array());

        return $json;
    }

    public function getBreadcrumbsJavascript($path, $javascriptVarName)
    {
        if (empty($path)) {
            return '';
        }

        $albums = Mage::getResourceSingleton('mpgallery/album_tree')->loadBreadcrumbsArray($path);

        if (empty($albums)) {
            return '';
        }

        foreach ($albums as $key => $album) {
            $albums[$key] = $this->_getNodeJson($album);
        }

        return
            '<script type="text/javascript">'
            . $javascriptVarName . ' = ' . Zend_Json::encode($albums) . ';'
            . '$("add_child_album_button").show();'
            . '</script>';
    }

    protected function _getNodeJson($node, $level = 0)
    {
        if (is_array($node)) {
            $node = new Varien_Data_Tree_Node($node, 'album_id', new Varien_Data_Tree);
        }

        $item         = array();
        $item['text'] = $this->buildNodeName($node);

        $rootForStores = in_array($node->getAlbumId(), $this->getRootIds());

        $item['id']   = $node->getId();
        $item['path'] = $node->getData('path');

        $item['cls']       = 'folder ' . ($node->getIsActive() ? 'active-album' : 'no-active-album');
        $allowMove         = true;
        $item['allowDrop'] = $allowMove;
        $item['allowDrag'] = $allowMove && (($node->getLevel() == 1 && $rootForStores) ? false : true);

        if ((int)$node->getChildrenCount() > 0) {
            $item['children'] = array();
        }

        $isParent = $this->_isParentSelectedAlbum($node);

        if ($node->hasChildren()) {
            $item['children'] = array();
            if (!($this->getUseAjax() && $node->getLevel() > 1 && !$isParent)) {
                foreach ($node->getChildren() as $child) {
                    $item['children'][] = $this->_getNodeJson($child, $level + 1);
                }
            }
        }

        if ($isParent || $node->getLevel() < 3) {
            $item['expanded'] = true;
        }

        return $item;
    }

    public function buildNodeName($node)
    {
        $result = $this->escapeHtml($node->getName());
        if ($this->_withPhotoCount) {
            $result .= ' (' . $node->getPhotoCount() . ')';
        }

        return $result;
    }

    protected function _isParentSelectedAlbum($node)
    {
        if ($node && $this->getAlbum()) {
            $pathIds = $this->getAlbum()->getPathIds();
            if (in_array($node->getId(), $pathIds)) {
                return true;
            }
        }

        return false;
    }

    public function isClearEdit()
    {
        return (bool)$this->getRequest()->getParam('clear');
    }
}
