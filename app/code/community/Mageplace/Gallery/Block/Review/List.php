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
 * Class Mageplace_Gallery_Block_Review_List
 *
 * @method Mageplace_Gallery_Model_Mysql4_Review_Collection getReviews
 * @method Mageplace_Gallery_Block_Review_List setReviews
 * @method Mageplace_Gallery_Block_Review_List setPage
 * @method Mageplace_Gallery_Block_Review_List setLimit
 * @method Mageplace_Gallery_Block_Review_List setDefaultLimit
 * @method Mageplace_Gallery_Block_Review_List setOrder
 * @method Mageplace_Gallery_Block_Review_List setDefaultOrder
 * @method Mageplace_Gallery_Block_Review_List setDirection
 * @method Mageplace_Gallery_Block_Review_List setDefaultDirection
 * @method Mageplace_Gallery_Block_Review_List setDisplayEmptyMessage
 * @method Mageplace_Gallery_Block_Review_List setDisplayWrapper
 * @method Mageplace_Gallery_Block_Review_List setDisplayShowMoreButton
 */
class Mageplace_Gallery_Block_Review_List extends Mage_Core_Block_Template
{
    /**
     * @var Mageplace_Gallery_Helper_Config
     */
    protected $_configHelper;
    /**
     * @var Mageplace_Gallery_Helper_Url
     */
    protected $_urlHelper;

    protected $_limit = 5;
    protected $_order = 'update_date';
    protected $_direction = 'DESC';
    protected $_displayEmptyMessage = true;
    protected $_displayWrapper = true;
    protected $_displayShowMoreButton = true;

    public function __construct()
    {
        parent::__construct();

        $this->_configHelper = Mage::helper('mpgallery/config');
        $this->_urlHelper    = Mage::helper('mpgallery/url');

        $this->setTemplate('mpgallery/review/list.phtml');
    }

    public function getPhoto()
    {
        if (!$this->hasData('photo')) {
            $this->setData('photo', Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_PHOTO));
        }

        return $this->_getData('photo');
    }

    public function getReviewCount()
    {
        return Mage::getModel('mpgallery/review')->getPhotoReviewCount($this->getPhoto()->getId());
    }

    public function getLimit()
    {
        if ($this->hasData('limit')) {
            return $this->getData('limit');
        } elseif ($this->hasData('default_limit')) {
            return $this->getData('default_limit');
        }

        return $this->_limit;
    }

    public function getCurrentPage()
    {
        if ($this->hasData('page')) {
            return $this->getData('page');
        }

        return 1;
    }

    public function getCurrentOrder()
    {
        if ($this->hasData('order')) {
            return $this->getData('order');
        } elseif ($this->hasData('default_order')) {
            return $this->getData('default_order');
        }

        return $this->_order;
    }

    public function getCurrentDirection()
    {
        if ($this->hasData('direction')) {
            return $this->getData('direction');
        } elseif ($this->hasData('default_direction')) {
            return $this->getData('default_direction');
        }

        return $this->_direction;
    }

    public function displayEmptyMessage()
    {
        if ($this->hasData('display_empty_message')) {
            return $this->getData('display_empty_message');
        }

        return $this->_displayEmptyMessage;
    }

    public function displayWrapper()
    {
        if ($this->hasData('display_wrapper')) {
            return $this->getData('display_wrapper');
        }

        return $this->_displayWrapper;
    }

    public function displayShowMoreButton()
    {
        if ($this->hasData('display_show_more_button')) {
            return $this->getData('display_show_more_button');
        }

        return $this->_displayShowMoreButton;
    }

    public function getShowReviewUrl()
    {
        return $this->_urlHelper->getPhotoUrl($this->getPhoto(), array(
            'review' => 'show',
            'limit'  => $this->getLimit(),
            'page'   => 2
        ));
    }

    /**
     * @param Mageplace_Gallery_Model_Review $_review
     *
     * @return string
     */
    public function getReviewHtml($_review)
    {
        return $this->getLayout()->createBlock('mpgallery/review_view')->setReview($_review)->toHtml();
    }

    protected function _beforeToHtml()
    {
        $reviews = Mage::getResourceModel('mpgallery/review_collection')
            ->addPhotoFilter($this->getPhoto()->getId())
            ->addIsActiveFilter();

        $reviews->setCurPage($this->getCurrentPage());

        $reviews->setPageSize((int)$this->getLimit());

        if ($this->getCurrentOrder()) {
            $reviews->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
        }

        $reviews->load();

        $this->setReviews($reviews);

        return parent::_beforeToHtml();
    }
}
