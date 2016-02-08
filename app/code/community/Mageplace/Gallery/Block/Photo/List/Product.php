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
 * Class Mageplace_Gallery_Block_Photo_List_Product
 */
class Mageplace_Gallery_Block_Photo_List_Product extends Mageplace_Gallery_Block_Photo_List_Abstract
{
    protected function _construct()
    {
        parent::_construct();

        $this->_photoSize          = $this->getPhotoSizes()->getData('photo_product_thumb_size');
        $this->_displayName        = $this->getSettings()->getData('photo_product_display_name');
        $this->_displayDescription = $this->getSettings()->getData('photo_product_display_short_descr');
        $this->_displayUpdateDate  = $this->getSettings()->getData('photo_product_display_update_date');
        $this->_displayShowLink    = $this->getSettings()->getData('photo_product_display_show_link');
    }

    public function isEnabled()
    {
        if (!$this->hasData('is_enabled')) {
            $this->setData('is_enabled', $this->getSettings()->getData('photo_product_enable') && $this->getProductAlbums());
        }

        return $this->_getData('is_enabled');
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * @return Mageplace_Gallery_Model_Mysql4_Photo_Collection|null
     */
    public function getCollection()
    {
        if (!$this->hasData('collection')) {
            $this->setData('collection',
                Mage::getModel('mpgallery/photo')->getCollection()
                    ->addProductFilter($this->getProduct()->getId())
                    ->addIsActiveFilter()
                    ->addStoreFilter()
                    ->addCustomerGroupFilter()
            );
        }

        return $this->_getData('collection');
    }

    public function getPhotos()
    {
        return $this->getCollection();
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getSettings()
    {
        if (!$this->hasData('settings')) {
            $settings = new Mageplace_Gallery_Model_Settings();
            $this->setData('settings', $settings->setGroup('product_view_display'));
        }

        return $this->_getData('settings');
    }

    /**
     * @return Mageplace_Gallery_Model_Settings
     */
    public function getPhotoSizes()
    {
        if (!$this->hasData('photo_sizes')) {
            $settings = new Mageplace_Gallery_Model_Settings();
            $this->setData('photo_sizes', $settings->setGroup('sizes'));
        }

        return $this->_getData('photo_sizes');
    }

    public function getDisplayButtonArea()
    {
        return parent::getDisplayButtons() || $this->getDisplayUploadButton();
    }

    public function getLimitPerPage()
    {
        return (int)$this->getSettings()->getData('photo_product_per_page');
    }

    public function getScrollToPhotos()
    {
        return false !== strpos(Mage::helper('core/url')->getCurrentUrl(), $this->getPageVarName());
    }

    protected function getSortBy()
    {
        return $this->getSettings()->getData('photo_product_sort_by');
    }

    protected function getSortDirection()
    {
        return $this->getSettings()->getData('photo_product_sort_dir');
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
            foreach ($photos as $photo) {
                if (!is_object($photo)) {
                    $photo = Mage::getModel('mpgallery/photo')->load((int)$photo);
                }

                $photosImgs[] = array(
                    'url'   => $this->getImage($photo, 'image', $this->getPhotoSizes()->getPhotoSize())->__toString(),
                    'title' => $this->stripTags($photo->getName(), null, true),
                    'id'    => $photo->getUrlKey()
                );
            }

            $this->setData('images_json', Zend_Json::encode($photosImgs));
        }

        return $this->_getData('images_json');
    }

    public function getPhotoAvgRate($photo)
    {
        return Mage::getModel('mpgallery/review')->getPhotoAverageRate($photo->getId());
    }

    public function getUploadUrl()
    {
        return Mage::helper('mpgallery/url')->getGalleryUrl(array(
            'upload'     => 'photo',
            'product_id' => $this->getProduct()->getId(),
            'back'       => base64_encode(Mage::helper('core/url')->getCurrentUrl())
        ));
    }

    protected function getProductAlbums()
    {
        if (!$this->hasData('product_albums')) {
            $this->setData('product_albums', Mage::getModel('mpgallery/album')->getAlbumIdsByProduct($this->getProduct()->getId()));
        }

        return $this->_getData('product_albums');
    }

    public function getDisplayUploadButton()
    {
        return Mage::helper('mpgallery/photo')->canUpload()
        && Mage::helper('mpgallery/config')->isProductPhotoUploadEnable();
    }

    protected function _toHtml()
    {
        if ($this->isEnabled()
            && (($this->getCollection() && $this->getCollection()->getSize() > 0) || $this->getDisplayUploadButton())
        ) {
            return Mage_Core_Block_Template::_toHtml();
        }

        return '';
    }
}