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
 * Class Mageplace_Gallery_Helper_Config
 */
class Mageplace_Gallery_Helper_Config extends Mageplace_Gallery_Helper_Data
{
    static protected $CONFIG_FIND_GROUPS = array(
        'album_view_display',
        'album_display',
        'photo_view_display',
        'photo_display',
        'product_view_display',
        'sizes',
    );

    public function getRootAlbum($store = null)
    {
        $root = (int)Mage::getStoreConfig('mpgallery/general/root_album', $store);

        return $root < Mageplace_Gallery_Model_Album::TREE_ROOT_ID ? Mageplace_Gallery_Model_Album::TREE_ROOT_ID : $root;
    }

    public function getImagePath($store = null)
    {
        return (string)Mage::getStoreConfig('mpgallery/general/image_path', $store);
    }

    public function isJqueryEnable($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/general/jquery_enable', $store);
    }

    public function isLightboxCategoryPhotos($store = null)
    {
        return Mage::getStoreConfig('mpgallery/general/lightbox_photos', $store) == Mageplace_Gallery_Helper_Const::LIGHTBOX_PHOTOS_CATEGORY;
    }

    public function isLightboxPagePhotos($store = null)
    {
        return Mage::getStoreConfig('mpgallery/general/lightbox_photos', $store) == Mageplace_Gallery_Helper_Const::LIGHTBOX_PHOTOS_PAGE;
    }

    public function isSlideshowAutostart($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/general/slideshow_autostart', $store);
    }

    public function slideshowDelay($store = null)
    {
        return (int)Mage::getStoreConfig('mpgallery/general/slideshow_delay', $store);
    }

    public function isReviewEnabled($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/general/review_enable', $store);
    }

    public function getUrlPrefix($store = null)
    {
        return (string)Mage::getStoreConfig('mpgallery/web/gallery_url_prefix', $store);
    }

    public function getUrlSuffix($store = null)
    {
        return (string)Mage::getStoreConfig('mpgallery/web/gallery_url_suffix', $store);
    }

    public function getAlbumUrlSuffix($store = null)
    {
        return trim(Mage::getStoreConfig('mpgallery/web/album_url_suffix', $store));
    }

    public function getPhotoUrlSuffix($store = null)
    {
        return trim(Mage::getStoreConfig('mpgallery/web/photo_url_suffix', $store));
    }

    public function showAlbumUrlWithParents($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/web/album_url_include_parent', $store);
    }

    public function showPhotoUrlWithParents($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/web/photo_url_include_parent', $store);
    }

    public function showBreadcrumbs($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/web/show_breadcrumbs', $store);
    }

    public function showBreadcrumbsGalleryTitle($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/web/show_breadcrumb_gallery_title', $store);
    }

    public function showBreadcrumbsHomePage($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/web/show_breadcrumb_home_page', $store);
    }

    public function getAdminThumbSize($store = null)
    {
        return Mage::getStoreConfig('mpgallery/sizes/admin_thumb_size', $store);
    }

    public function getCustomerPhotoListThumbSize($store = null)
    {
        return Mage::getStoreConfig('mpgallery/sizes/photo_customer_list_thumb_size', $store);
    }

    public function getTitleSeparator($store = null)
    {
        if (!$titleSeparator = (string)Mage::getStoreConfig('mpgallery/web/title_separator', $store)) {
            $titleSeparator = (string)Mage::getStoreConfig('catalog/seo/title_separator', $store);
        }

        return $titleSeparator;
    }

    public function isPhotoUploadEnable($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/photo_upload/enabled', $store);
    }

    public function isProductPhotoUploadEnable($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/photo_upload/enable_product', $store);
    }

    public function isPhotoUploadOnlyRegistered($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/photo_upload/only_registered', $store);
    }

    public function isPhotoUploadCustomerView($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/photo_upload/photos_view', $store);
    }

    public function isPhotoUploadCustomerEdit($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/photo_upload/photos_edit', $store);
    }

    public function isPhotoUploadCustomerDelete($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/photo_upload/photos_delete', $store);
    }

    public function isPhotoUploadAttachCurrentAlbum($store = null)
    {
        return (bool)Mage::getStoreConfig('mpgallery/photo_upload/attach_current_album', $store);
    }

    public function find($code, $group = null)
    {
        if (null !== $group) {
            $value = Mage::getStoreConfig('mpgallery/' . $group . '/' . $code);
            if (null !== $value) {
                return $value;
            }
        }

        foreach (self::$CONFIG_FIND_GROUPS as $group) {
            $value = Mage::getStoreConfig('mpgallery/' . $group . '/' . $code);
            if (null !== $value) {
                return $value;
            }
        }

        return null;
    }
}