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
 * Class Mageplace_Gallery_Block_Photo_List_Carousel
 *
 * @method Mageplace_Gallery_Block_Photo_List_Carousel setPhotoPerPage
 * @method Mageplace_Gallery_Block_Photo_List_Carousel setDisplayButtons
 * @method Mageplace_Gallery_Block_Photo_List_Carousel setButtonsPosition
 * @method Mageplace_Gallery_Block_Photo_List_Carousel setPhotoSize
 * @method Mageplace_Gallery_Block_Photo_List_Carousel setDisplayName
 * @method Mageplace_Gallery_Block_Photo_List_Carousel setDisplayShortDescription
 * @method Mageplace_Gallery_Block_Photo_List_Carousel setDisplayUpdateDate
 * @method Mageplace_Gallery_Block_Photo_List_Carousel setDisplayShowLink
 * @method string getPhotoPerPage
 *
 */
abstract class Mageplace_Gallery_Block_Photo_List_Abstract extends Mageplace_Gallery_Block_Abstract
{
    protected $_defaultPhotoPerPage = 5;
    protected $_pageVarName = 'gallery_page';
    protected $_displayButtons = true;
    protected $_buttonsPosition = 'bottom';
    protected $_photoSize = '135x135';
    protected $_displayName = true;
    protected $_displayDescription = true;
    protected $_displayUpdateDate = true;
    protected $_displayShowLink = true;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($this->isEnabled() && $this->getCollection()) {
            $this->getCollection()->setCurPage($this->getCurrentPage());

            $limit = $this->getLimit();
            if ($limit) {
                $this->getCollection()->setPageSize($limit);
            }

            $sortBy = $this->getSortBy();
            if ($sortBy) {
                if ($dir = $this->getSortDirection()) {
                    $this->getCollection()->setOrder($sortBy, $dir);
                } else {
                    $this->getCollection()->setOrder($sortBy);
                }
            }
        }

        return $this;
    }

    abstract public function getCollection();

    public function isEnabled()
    {
        return true;
    }

    public function getPhotos()
    {
        return $this->getCollection();
    }

    public function getPageVarName()
    {
        return $this->_pageVarName;
    }

    public function getCurrentPage()
    {
        if ($page = (int)$this->getRequest()->getParam($this->getPageVarName())) {
            return $page;
        }

        return 1;
    }

    public function getLimitPerPage()
    {
        return $this->_defaultPhotoPerPage;
    }

    public function getLimit()
    {
        $limit = $this->getLimitPerPage();
        if ($limit < 1) {
            $limit = (int)$this->getPhotoPerPage();
        }

        if ($limit > 0) {
            return $limit;
        }

        return $this->_defaultPhotoPerPage;
    }

    public function getNextPageUrl()
    {
        $url = Mage::helper('core/url')->getCurrentUrl();

        $param = $this->getPageVarName() . '=' . $this->getCollection()->getCurPage(1);

        if (false !== strpos($url, $this->getPageVarName())) {
            return preg_replace('/(\&|\?)' . preg_quote($this->getPageVarName()) . '\=\d/i', '$1' . $param, $url);
        }

        $lastChar = substr($url, -1);
        if ($lastChar != '?' && $lastChar != '&') {
            if (false === strpos($url, '?')) {
                $param = '?' . $param;
            } else {
                $param = '&' . $param;
            }
        }

        return $url . $param;
    }

    public function getPreviousPageUrl()
    {
        $pageNumber = $this->getCollection()->getCurPage(-1);
        $url        = Mage::helper('core/url')->getCurrentUrl();
        $param      = $this->getPageVarName() . '=' . $pageNumber;

        if (false !== strpos($url, $this->getPageVarName())) {
            return preg_replace('/(\&|\?)' . preg_quote($this->getPageVarName()) . '\=\d/i', '$1' . $param, $url);
        }

        $lastChar = substr($url, -1);
        if ($lastChar != '?' && $lastChar != '&') {
            if (false === strpos($url, '?')) {
                $param = '?' . $param;
            } else {
                $param = '&' . $param;
            }
        }

        return $url . $param;
    }

    public function isFirstPage()
    {
        return $this->getCurrentPage() == 1;
    }

    public function isLastPage()
    {
        return $this->getCurrentPage() == $this->getCollection()->getLastPageNumber();
    }

    public function getDisplayButtons()
    {
        if ($this->hasData('display_buttons')) {
            $displayButtons = $this->_getData('display_buttons');
        } else {
            $displayButtons = $this->_displayButtons;
        }

        return $displayButtons && $this->getCollection()->getLastPageNumber() > 1;
    }

    public function getButtonsPosition()
    {
        return $this->_buttonsPosition;
    }

    public function isTopButtonsPosition()
    {
        return $this->getButtonsPosition() == 'top';
    }

    public function isBottomButtonsPosition()
    {
        return $this->getButtonsPosition() == 'bottom';
    }

    public function getPhotoSize()
    {
        if ($this->hasData('photo_size')) {
            return $this->_getData('photo_size');
        }

        return $this->_photoSize;
    }

    public function getDisplayName()
    {
        if ($this->hasData('display_name')) {
            return $this->_getData('display_name');
        }

        return $this->_displayName;
    }

    public function getDisplayShortDescription()
    {
        if ($this->hasData('display_description')) {
            return $this->_getData('display_description');
        }

        return $this->_displayDescription;
    }

    public function getDisplayUpdateDate()
    {
        if ($this->hasData('display_update_date')) {
            return $this->_getData('display_update_date');
        }

        return $this->_displayUpdateDate;
    }

    public function getDisplayShowLink()
    {
        if ($this->hasData('display_show_link')) {
            return $this->_getData('display_show_link');
        }

        return $this->_displayShowLink;
    }

    protected function _beforeToHtml()
    {
        if ($this->isEnabled() && $this->getCollection()) {
            $this->getCollection()->load();
        }

        return parent::_beforeToHtml();
    }

    protected function _toHtml()
    {
        if ($this->isEnabled() && $this->getCollection() && $this->getCollection()->getSize() > 0) {
            return parent::_toHtml();
        }

        return '';
    }

    protected function getSortBy()
    {
        return false;
    }


    protected function getSortDirection()
    {
        return false;
    }
}