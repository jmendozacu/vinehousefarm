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
 * Class Mageplace_Gallery_Block_Photo_View
 *
 * @method Mageplace_Gallery_Block_Photo_View setPhotoListBlockName
 * @method string|null getPhotoListBlockName
 */
class Mageplace_Gallery_Block_Photo_View extends Mageplace_Gallery_Block_Photo_Abstract
{
    protected $_defaultPhotoListBlock = 'mpgallery/photo_list_carousel';
    protected $_displayButtons = true;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if (Mage::helper('mpgallery/config')->showBreadcrumbs()) {
            if($customTitle = $this->getCurrentPhoto()->getPageTitle()) {
                $arg = array('custom_title' => $customTitle);
            } else {
                $arg = array();
            }
            $this->getLayout()->createBlock('mpgallery/breadcrumbs', 'photo_breadcrumbs', $arg);
        }

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $photo = $this->getCurrentPhoto();
            if ($description = $photo->getMetaDescription()) {
                $headBlock->setDescription($description);
            }

            if ($keywords = $photo->getMetaKeywords()) {
                $headBlock->setKeywords($keywords);
            }
        }

        return $this;
    }

    public function isListBlockFirst()
    {
        if ($this->getPhotoSettings()->getPhotoViewDisplayMode() == Mageplace_Gallery_Model_Photo::DISPLAY_MODE_LIST_PHOTO) {
            return true;
        }

        return false;
    }

    public function hideDisplayListBlock()
    {
        return !$this->getCurrentActiveAlbum() || $this->getPhotoSettings()->getPhotoViewDisplayMode() == Mageplace_Gallery_Model_Photo::DISPLAY_MODE_PHOTO;
    }

    /**
     * @return Mageplace_Gallery_Block_Photo_List_Carousel|mixed
     */
    public function getPhotoListBlock()
    {
        if (!$this->hasData('photo_list_block')) {
            if ($blockName = $this->getPhotoListBlockName()) {
                $block = $this->getLayout()->getBlock($blockName);
            }

            if (empty($block)) {
                $block = $this->getLayout()->createBlock($this->_defaultPhotoListBlock, 'gallery_photo_list_carousel_' . microtime());
            }

            $this->setData('photo_list_block', $block);
        }

        return $this->_getData('photo_list_block');
    }

    public function getPhotoCollection()
    {
        return $this->getPhotoListBlock()->getCollection();
    }

    public function getNextPageUrl()
    {
        return $this->_urlHelper->getPhotoUrl($this->getCurrentPhoto()->getNextPhoto());
    }

    public function getPreviousPageUrl()
    {
        return $this->_urlHelper->getPhotoUrl($this->getCurrentPhoto()->getPreviousPhoto());
    }

    public function isFirstPage()
    {
        return $this->getCurrentPhoto()->getPreviousPhoto() === null;
    }

    public function isLastPage()
    {
        return $this->getCurrentPhoto()->getNextPhoto() === null;
    }

    public function canDisplayButtons()
    {
        return $this->_displayButtons
        && !$this->hideDisplayListBlock()
        && $this->getCurrentAlbumPhotos()
        && $this->getCurrentAlbumPhotos()->getSize() > 0
        && !($this->isFirstPage() && $this->isLastPage());
    }

    public function getContentHeading()
    {
        if($this->getCurrentPhoto()->getContentHeading()) {
            return $this->getCurrentPhoto()->getContentHeading();
        }

        return $this->getCurrentPhoto()->getName();
    }

    public function getDisplayName()
    {
        return $this->getPhotoSettings()->getPhotoViewDisplayName();
    }

    public function getDisplayReview()
    {
        return $this->_configHelper->isReviewEnabled() && $this->getPhotoSettings()->getData('photo_view_display_review');
    }

    public function getDisplayUpdateDate()
    {
        return $this->getPhotoSettings()->getPhotoViewDisplayUpdateDate();
    }

    public function getDisplayShortDescription()
    {
        return $this->getPhotoSettings()->getPhotoViewDisplayShortDescr();
    }

    public function getDisplayDescription()
    {
        return $this->getPhotoSettings()->getPhotoViewDisplayDescr();
    }

    public function getDisplayBackUrl()
    {
        if ($this->getCurrentActiveAlbum()) {
            return $this->getPhotoSettings()->getPhotoViewDisplayBackUrl();
        } else {
            return false;
        }
    }

    public function getImageSrc()
    {
        return $this->getImage($this->getCurrentPhoto(), 'image', $this->getPhotoSizes()->getPhotoSize());
    }

    public function getImagesJson()
    {
        return $this->getPhotoListBlock()->getImagesJson();
    }

    public function getCategoryPhotosLimit()
    {
        return $this->getPhotoListBlock()->getLimit();
    }

    public function getCategoryPhotosCount()
    {
        return $this->getPhotoListBlock()->getCollection() ? $this->getPhotoListBlock()->getCollection()->count() : 0;
    }

    public function isSlidePhotos()
    {
        return (bool)$this->getPhotoSettings()->getData('photo_view_slide_photos');
    }

    public function getSaveReviewUrl()
    {
        return $this->_urlHelper->getPhotoUrl($this->getCurrentPhoto(), array('review' => 'save'));
    }

    public function getBackTitle()
    {
        return $this->getCurrentActiveAlbum()->getName();
    }

    public function getBackUrl()
    {
        if ($this->getCurrentActiveAlbum()) {
            return $this->_urlHelper->getAlbumUrl($this->getCurrentActiveAlbum());
        }

        return '';
    }

    public function getAvgRate()
    {
        return Mage::getModel('mpgallery/review')->getPhotoAverageRate($this->getCurrentPhoto()->getId());
    }

    public function getReviewCount()
    {
        return Mage::getModel('mpgallery/review')->getPhotoReviewCount($this->getCurrentPhoto()->getId());
    }
}