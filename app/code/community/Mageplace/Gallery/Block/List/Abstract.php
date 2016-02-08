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
 * Class Mageplace_Gallery_Block_Album_List
 *
 * @method Mageplace_Gallery_Block_List_Abstract setCollection
 * @method Mageplace_Gallery_Block_List_Abstract setToolbarBlockName
 * @method Mageplace_Gallery_Block_List_Abstract setDisplayToolbarBlock
 * @method Mageplace_Gallery_Model_Mysql4_Album_Collection|Mageplace_Gallery_Model_Mysql4_Photo_Collection getCollection
 * @method string getToolbarBlockName
 * @method bool|int getDisplayToolbarBlock
 *
 */
abstract class Mageplace_Gallery_Block_List_Abstract extends Mageplace_Gallery_Block_Abstract
{
    const DEFAULT_POSITION_LAYOUT = 'mpgallery_default';

    protected $_defaultDisplayToolbar = true;
    protected $_displayEmptyMessage = false;
    protected $_defaultColumnCount = 6;
    protected $_columnCountLayoutDepend = array();
    protected $_toolbarPosition = array();

    public function __construct()
    {
        parent::__construct();

        $this->initCollection();
    }

    abstract function getGalleryObjectName();

    abstract function initCollection();

    function getDefaultToolbarBlock()
    {
        return 'mpgallery/' . $this->getGalleryObjectName() . '_list_toolbar';
    }

    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }

        return $this->getLayout()->createBlock($this->getDefaultToolbarBlock(), 'gallery_' . $this->getGalleryObjectName() . '_list_toolbar_' . microtime());
    }

    public function addToolbarPosition($position, $show = true, $pageLayout = self::DEFAULT_POSITION_LAYOUT)
    {
        $this->_toolbarPosition[$pageLayout][$position] = $show;

        return $this;
    }

    public function getLayoutToolbarPositions($pageLayout)
    {
        if (isset($this->_toolbarPosition[$pageLayout])) {
            return $this->_toolbarPosition[$pageLayout];
        }

        return false;
    }

    public function getDisplayToolbar()
    {
        if (null !== $this->getDisplayToolbarBlock()) {
            return $this->getDisplayToolbarBlock();
        }

        return $this->_defaultDisplayToolbar;
    }

    /**
     * @param string      $position
     * @param string|null $pageLayout
     *
     * @return bool
     */
    public function canDisplayToolbar($position, $pageLayout = null)
    {
        if (!$this->getDisplayToolbar()) {
            return false;
        }

        if (null === $pageLayout) {
            $pageLayout = $this->getPageLayoutCode();
        }

        if (!$pageLayout) {
            $pageLayout = self::DEFAULT_POSITION_LAYOUT;
        }

        if (isset($this->_toolbarPosition[$pageLayout]) && array_key_exists($position, $this->_toolbarPosition[$pageLayout])) {
            return $this->_toolbarPosition[$pageLayout][$position];
        }

        return true;
    }

    /**
     * @param string|null $pageLayout
     *
     * @return bool
     */
    public function canDisplayTopToolbar($pageLayout = null)
    {
        return $this->canDisplayToolbar('top', $pageLayout);
    }

    /**
     * @param string|null $pageLayout
     *
     * @return bool
     */
    public function canDisplayBottomToolbar($pageLayout = null)
    {
        return $this->canDisplayToolbar('bottom', $pageLayout);
    }

    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    public function getMode()
    {
        return $this->getChild('toolbar')->getCurrentMode();
    }

    public function getLimit()
    {
        return $this->getChild('toolbar')->getLimit();
    }

    public function getOrder()
    {
        return $this->getChild('toolbar')->getCurrentOrder();
    }

    public function getDir()
    {
        return $this->getChild('toolbar')->getCurrentDirection();
    }

    public function canDisplayEmptyMessage()
    {
        return $this->_displayEmptyMessage;
    }

    public function setDisplayEmptyMessage($displayEmptyMessage)
    {
        $this->_displayEmptyMessage = $displayEmptyMessage;

        return $this;
    }

    public function getColumnCount()
    {
        $currentMode = $this->getMode();
        if (!$this->_getData($currentMode . '_column_count')) {
            if ($columnCount = $this->getAlbumSettings()->getData($this->getGalleryObjectName() . '_' . $currentMode . '_column_count')) {
            } elseif (($pageLayoutCode = $this->getPageLayoutCode()) && ($columnCount = $this->getColumnCountLayoutDepend($pageLayoutCode, $currentMode))) {
            } else {
                $columnCount = $this->_defaultColumnCount;
            }

            $this->setData($currentMode . '_column_count', (int)$columnCount);
        }

        return (int)$this->_getData($currentMode . '_column_count');
    }

    public function getPageLayout()
    {
        return $this->getLayout()->helper('page/layout')->getCurrentPageLayout();
    }

    public function getPageLayoutCode()
    {
        if ($pageLayout = $this->getPageLayout()) {
            return $pageLayout->getCode();
        }

        return false;
    }

    public function addColumnCountLayoutDepend($pageLayout, $columnCount, $mode = Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_GRID)
    {
        $this->_columnCountLayoutDepend[$pageLayout][$mode] = $columnCount;

        return $this;
    }

    public function removeColumnCountLayoutDepend($pageLayout, $mode = Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_GRID)
    {
        if (isset($this->_columnCountLayoutDepend[$pageLayout][$mode])) {
            unset($this->_columnCountLayoutDepend[$pageLayout][$mode]);
        }

        return $this;
    }

    public function getColumnCountLayoutDepend($pageLayout, $mode = Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_GRID)
    {
        if (isset($this->_columnCountLayoutDepend[$pageLayout][$mode])) {
            return (int)$this->_columnCountLayoutDepend[$pageLayout][$mode];
        }

        return false;
    }

    public function getDisplayName()
    {
        return $this->getAlbumSettings()->getData($this->getGalleryObjectName() . '_' . $this->getMode() . '_display_name');
    }

    public function getDisplayRate()
    {
        return $this->_configHelper->isReviewEnabled()
        && $this->getAlbumSettings()->getData($this->getGalleryObjectName() . '_' . $this->getMode() . '_display_rate');
    }

    public function getDisplayUpdateDate()
    {
        return $this->getAlbumSettings()->getData($this->getGalleryObjectName() . '_' . $this->getMode() . '_display_update_date');
    }

    public function getDisplayShortDescription()
    {
        return $this->getAlbumSettings()->getData($this->getGalleryObjectName() . '_' . $this->getMode() . '_display_short_descr');
    }

    public function getDisplayShowLink()
    {
        return $this->getAlbumSettings()->getData($this->getGalleryObjectName() . '_' . $this->getMode() . '_display_show_link');
    }

    protected function _beforeToHtml()
    {
        $this->getCollection()->load();

        return parent::_beforeToHtml();
    }
}
