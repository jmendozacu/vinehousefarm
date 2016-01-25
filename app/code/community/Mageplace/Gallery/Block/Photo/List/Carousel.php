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
 */
class Mageplace_Gallery_Block_Photo_List_Carousel extends Mageplace_Gallery_Block_Photo_List_Abstract
{
    protected $_pageVarName = 'p';

    protected function _construct()
    {
        parent::_construct();

        $this->_photoSize          = $this->getPhotoSizes()->getPhotoCarouselThumbSize();
        $this->_displayName        = $this->getPhotoSettings()->getData('photo_carousel_display_name');
        $this->_displayDescription = $this->getPhotoSettings()->getData('photo_carousel_display_short_descr');
        $this->_displayUpdateDate  = $this->getPhotoSettings()->getData('photo_carousel_display_update_date');
        $this->_displayShowLink    = $this->getPhotoSettings()->getData('photo_carousel_display_show_link');
    }

    /**
     * @return Mageplace_Gallery_Model_Mysql4_Photo_Collection|null
     */
    public function getCollection()
    {
        return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM_PHOTOS);
    }

    public function getPhotos()
    {
        return $this->getCollection();
    }

    /**
     * @return Mageplace_Gallery_Model_Photo
     */
    public function getCurrentPhoto()
    {
        return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_PHOTO);
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getPhotoSettings()
    {
        if (!$this->hasData('photo_settings')) {
            $this->setData('photo_settings', $this->getCurrentPhoto()->getDisplaySettings());
        }

        return $this->_getData('photo_settings');
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getPhotoSizes()
    {
        if (!$this->hasData('photo_sizes')) {
            $this->setData('photo_sizes', $this->getCurrentPhoto()->getSizeSettings());
        }

        return $this->_getData('photo_sizes');
    }

    public function getLimitPerPage()
    {
        return (int)$this->getPhotoSettings()->getData('photo_view_list_per_page');
    }

    protected function getSortBy()
    {
        return $this->getPhotoSettings()->getData('photo_view_list_sort_by');
    }

    protected function getSortDirection()
    {
        return $this->getPhotoSettings()->getData('photo_view_list_sort_dir');
    }

    public function getNextPageUrl()
    {
        return $this->_urlHelper->getPhotoUrl($this->getCurrentPhoto(), array(
            $this->getPageVarName() => $this->getCollection()->getCurPage(1)
        ));
    }

    public function getPreviousPageUrl()
    {
        $pageNumber = $this->getCollection()->getCurPage(-1);
        if ($pageNumber > 1) {
            return $this->_urlHelper->getPhotoUrl($this->getCurrentPhoto(), array(
                $this->getPageVarName() => $pageNumber
            ));
        }

        return $this->_urlHelper->getPhotoUrl($this->getCurrentPhoto());
    }

    public function getImagesJson()
    {
        if (!$this->hasData('images_json')) {
            if ($this->_configHelper->isLightboxPagePhotos()) {
                $photos = $this->getCollection();
            } else {
                $photos = $this->getCollection()->getAllOrderedIds();
            }

            $photosImgs = array();
            if($photos) {
                foreach ($photos as $photo) {
                    if (!is_object($photo)) {
                        $photo = Mage::getModel('mpgallery/photo')->load((int)$photo);
                    }
                    $photosImgs[] = array(
                        'url'   => $this->getImage($photo, 'image', $this->getAlbumSizes()->getPhotoSize())->__toString(),
                        'title' => $this->stripTags($photo->getName(), null, true),
                        'id'    => $photo->getUrlKey()
                    );
                }
            }

            $this->setData('images_json', Zend_Json::encode($photosImgs));
        }

        return $this->_getData('images_json');
    }

    public function getDisplayRate()
    {
        return $this->_configHelper->isReviewEnabled() && $this->getPhotoSettings()->getData('photo_carousel_display_rate');
    }

    public function getPhotoAvgRate($photo)
    {
        return Mage::getModel('mpgallery/review')->getPhotoAverageRate($photo->getId());
    }
}