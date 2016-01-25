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
 * Class Mageplace_Gallery_Block_List_Toolbar
 *
 * @method Mageplace_Gallery_Block_List_Toolbar setToolbarBlockPagerName
 * @method string|nul getToolbarBlockPagerName
 */
abstract class Mageplace_Gallery_Block_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    /**
     * @var Mageplace_Gallery_Helper_Url
     */
    protected $_urlHelper;

    /**
     * @var Mageplace_Gallery_Helper_Config
     */
    protected $_configHelper;

    protected $_availableModeTranslates;

    protected $_defaultPagerName = 'mpgallery.list.toolbar.pager';

    /**
     * @var bool $_paramsMemorizeAllowed
     */
    protected $_paramsMemorizeAllowed = true;


    protected function _construct()
    {
        parent::_construct();

        $this->_urlHelper    = Mage::helper('mpgallery/url');
        $this->_configHelper = Mage::helper('mpgallery/config');

        $this->_availableModeTranslates = array(
            Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_GRID   => Mage::helper('mpgallery')->__('Grid'),
            Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_LIST   => Mage::helper('mpgallery')->__('List'),
            Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_SIMPLE => Mage::helper('mpgallery')->__('Simple'),
        );

        $this->_modeVarName      = $this->getGalleryObjectName() . '_' . $this->_modeVarName;
        $this->_orderVarName     = $this->getGalleryObjectName() . '_' . $this->_orderVarName;
        $this->_directionVarName = $this->getGalleryObjectName() . '_' . $this->_directionVarName;
        $this->_pageVarName      = $this->getGalleryObjectName() . '_' . $this->_pageVarName;
        $this->_limitVarName     = $this->getGalleryObjectName() . '_' . $this->_limitVarName;
    }

    abstract function getGalleryObjectName();

    /**
     * @return Mageplace_Gallery_Model_Album
     */
    public function getCurrentAlbum()
    {
        if (!$this->hasData('current_album')) {
            $this->setData('current_album', Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM));
        }

        return $this->_getData('current_album');
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getSettings()
    {
        if (!$this->hasData('settings')) {
            $this->setData('settings', $this->getCurrentAlbum()->getDisplaySettings());
        }

        return $this->_getData('settings');
    }

    public function initAvailableOrder($availableOrder)
    {
        $allAvailableOrders = Mage::getSingleton('mpgallery/source_sortavailabledefault')->toOptionHash();
        if (empty($availableOrder) || !is_array($availableOrder) || in_array('0', $availableOrder, true)) {
            $this->_availableOrder = $allAvailableOrders;
        } else {
            $this->_availableOrder = array();
            foreach ($availableOrder as $order) {
                if (array_key_exists($order, $allAvailableOrders)) {
                    $this->_availableOrder[$order] = $allAvailableOrders[$order];
                }
            }
        }
    }

    public function getDefaultPerPageValue()
    {
        $objectName  = $this->getGalleryObjectName();
        $currentMode = $this->getCurrentMode();

        if (($perPage = $this->getSettings()->getData($objectName . '_' . $currentMode . '_per_page')) > 0) {
            return (int)$perPage;
        } elseif (($perPage = $this->getData('default_' . $currentMode . '_per_page')) > 0) {
            return (int)$perPage;
        }

        return 0;
    }

    public function getCurrentMode()
    {
        $mode = $this->_getData('_current_grid_mode');
        if ($mode) {
            return $mode;
        }

        $defaultMode = current($this->_availableMode);

        if (!$mode = $this->getRequest()->getParam($this->getModeVarName())) {
            $mode = Mage::getSingleton('mpgallery/session')->getDisplayMode($this->getGalleryObjectName());
        }

        if (!$mode || !in_array($mode, $this->_availableMode)) {
            $mode = $defaultMode;
        }

        if ($this->_paramsMemorizeAllowed) {
            Mage::getSingleton('mpgallery/session')->setDisplayMode($this->getGalleryObjectName(), $mode);
        } else {
            Mage::getSingleton('mpgallery/session')->unsetDisplayMode($this->getGalleryObjectName());
        }

        $this->setData('_current_grid_mode', $mode);

        return $mode;
    }

    public function getCurrentOrder()
    {
        $order = $this->_getData('_current_grid_order');
        if ($order) {
            return $order;
        }

        $orders       = $this->getAvailableOrders();
        $defaultOrder = $this->_orderField;

        if (!isset($orders[$defaultOrder])) {
            $keys         = array_keys($orders);
            $defaultOrder = $keys[0];
        }

        if (!$order = $this->getRequest()->getParam($this->getOrderVarName())) {
            $order = Mage::getSingleton('mpgallery/session')->getSortOrder($this->getGalleryObjectName());
        }

        if (!$order || !isset($orders[$order])) {
            $order = $defaultOrder;
        }

        if ($this->_paramsMemorizeAllowed) {
            Mage::getSingleton('mpgallery/session')->setSortOrder($this->getGalleryObjectName(), $order);
        } else {
            Mage::getSingleton('mpgallery/session')->unsetSortOrder($this->getGalleryObjectName());
        }

        $this->setData('_current_grid_order', $order);

        return $order;
    }

    public function getCurrentDirection()
    {
        $dir = $this->_getData('_current_grid_direction');
        if ($dir) {
            return $dir;
        }

        $directions = array('asc', 'desc');

        if (!$dir = strtolower($this->getRequest()->getParam($this->getDirectionVarName()))) {
            $dir = Mage::getSingleton('mpgallery/session')->getSortDir($this->getGalleryObjectName());
        }

        if (!$dir || !in_array($dir, $directions)) {
            $dir = $this->_direction;
        }

        if ($this->_paramsMemorizeAllowed) {
            Mage::getSingleton('mpgallery/session')->setSortDir($this->getGalleryObjectName(), $dir);
        } else {
            Mage::getSingleton('mpgallery/session')->unsetSortDir($this->getGalleryObjectName());
        }

        $this->setData('_current_grid_direction', $dir);

        return $dir;
    }

    public function getLimit()
    {
        if(!$this->isLimitEnable()) {
            return 0;
        }

        $limit = $this->_getData('_current_limit');
        if ($limit) {
            return $limit;
        }

        $limits       = $this->getAvailableLimit();
        $defaultLimit = $this->getDefaultPerPageValue();

        if (!$defaultLimit || !isset($limits[$defaultLimit])) {
            $keys         = array_keys($limits);
            $defaultLimit = $keys[0];
        }

        if (!$limit = $this->getRequest()->getParam($this->getLimitVarName())) {
            $limit = Mage::getSingleton('mpgallery/session')->getLimitPage($this->getGalleryObjectName());
        }

        if (!$limit || !isset($limits[$limit])) {
            $limit = $defaultLimit;
        }

        if ($this->_paramsMemorizeAllowed) {
            Mage::getSingleton('mpgallery/session')->setLimitPage($this->getGalleryObjectName(), $limit);
        } else {
            Mage::getSingleton('mpgallery/session')->unsetLimitPage($this->getGalleryObjectName());
        }

        $this->setData('_current_limit', $limit);

        return $limit;
    }

    public function getAvailableLimit()
    {
        $currentMode = $this->getCurrentMode();

        if (in_array($currentMode, array_keys(Mageplace_Gallery_Helper_Const::$DISPLAY_TYPES_BY_MODE))) {
            $pagerLimit = $this->getSettings()->getData($this->getGalleryObjectName() . '_' . $currentMode . '_pager_limit');

            if ($pagerLimit) {
                $limits     = explode(',', $pagerLimit);
                $pagerLimit = array();
                foreach ($limits as $limit) {
                    $limit = trim($limit);

                    if (intval($limit) > 0) {
                        $limit = intval($limit);
                    } else {
                        $limit = $this->__($limit);
                    }

                    $pagerLimit[is_int($limit) ? $limit : 0] = $limit;
                }

                return $pagerLimit;
            }


            if (isset($this->_availableLimit[$currentMode])) {
                return $this->_availableLimit[$currentMode];
            }
        }

        return $this->_defaultAvailableLimit;
    }

    public function isLimitEnable()
    {
        return true;
    }

    public function getModeLabel($mode)
    {
        return isset($this->_availableModeTranslates[$mode]) ? $this->_availableModeTranslates[$mode] : '';
    }

    public function getPagerUrl($params = array())
    {
        return $this->_urlHelper->getAlbumUrl($this->getCurrentAlbum(), $params);
    }

    public function getPagerHtml()
    {
        if ($blockName = $this->getToolbarBlockPagerName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }

        $pagerBlock = $this->getChild('mpgallery.' . $this->getGalleryObjectName() . '.list.toolbar.pager');
        if (!$pagerBlock instanceof Varien_Object) {
            $pagerBlock = $this->_defaultPagerName;
        }

        if ($pagerBlock instanceof Varien_Object) {
            /* @var $pagerBlock Mage_Page_Block_Html_Pager */
            $pagerBlock->setAvailableLimit($this->getAvailableLimit());

            $pagerBlock->setUseContainer(false)
                ->setShowPerPage(false)
                ->setShowAmounts(false)
                ->setLimitVarName($this->getLimitVarName())
                ->setPageVarName($this->getPageVarName())
                ->setFrameLength(Mage::getStoreConfig('design/pagination/pagination_frame'))
                ->setJump(Mage::getStoreConfig('design/pagination/pagination_frame_skip'))
                ->setCollection($this->getCollection());


            if($this->isLimitEnable()) {
                $pagerBlock->setLimit($this->getLimit());
            }

            return $pagerBlock->toHtml();
        }

        return '';
    }
}