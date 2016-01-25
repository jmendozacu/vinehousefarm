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
 * Class Mageplace_Gallery_Helper_Url
 */
class Mageplace_Gallery_Helper_Url extends Mageplace_Gallery_Helper_Data
{
    const URL_PREFIX = 'gallery';

    protected $_configHelper;
    protected $_urlPrefix;
    protected $_urlSuffix;
    protected $_galleryDirectUrl;
    protected $_albumUrlSuffix;
    protected $_photoUrlSuffix;
    protected $_urls;

    public function __construct()
    {
        $this->_configHelper = Mage::helper('mpgallery/config');
        $this->_urlPrefix    = $this->_configHelper->getUrlPrefix();
        if (!$this->_urlPrefix) {
            $this->_urlPrefix = self::URL_PREFIX;
        }
        $this->_urlSuffix        = $this->_configHelper->getUrlSuffix();
        $this->_galleryDirectUrl = $this->_urlPrefix . $this->_urlSuffix;
        $this->_albumUrlSuffix   = $this->_configHelper->getAlbumUrlSuffix();
        $this->_photoUrlSuffix   = $this->_configHelper->getPhotoUrlSuffix();
    }

    public function getUrlPrefix()
    {
        return $this->_urlPrefix;
    }

    public function getUrlSuffix()
    {
        return $this->_urlSuffix;
    }

    public function getAlbumUrlSuffix()
    {
        return $this->_albumUrlSuffix;
    }

    public function getPhotoUrlSuffix()
    {
        return $this->_photoUrlSuffix;
    }

    public function getGalleryDirectUrl()
    {
        return $this->_galleryDirectUrl;
    }

    public function getGalleryUrl(array $queryParams = array())
    {
        $params['_direct'] = $this->getGalleryDirectUrl();
        $params['_query']  = $queryParams;

        $url = Mage::getModel('core/url')->getUrl(null, $params);

        return $url;
    }

    public function getAlbumUrl($album = null, array $params = array())
    {
        if (null === $album) {
            $album = $this->getAlbum();
        } elseif (!$album instanceof Mageplace_Gallery_Model_Album) {
            $album = Mage::getModel('mpgallery/album')->load(intval($album));
        }

        if ($album->isStoreRoot()) {
            return $this->getGalleryUrl($params);
        }

        $url = '';
        if ($this->_configHelper->showPhotoUrlWithParents()) {
            $parentUrls = $this->_getParentAlbumUrlKeys($album);
            $url .= $parentUrls ? $parentUrls : $album->getUrlKey();
        } else {
            $url .= $album->getUrlKey();
        }

        if ($url) {
            return $this->_getGalleryUrl($url . $this->getAlbumUrlSuffix(), $params);
        } else {
            return $this->getGalleryUrl($params);
        }
    }

    public function getPhotoUrl($photo = null, array $params = array())
    {
        if (null === $photo) {
            $photo = $this->getPhoto();
        } elseif (!$photo instanceof Mageplace_Gallery_Model_Photo) {
            $photo = Mage::getModel('mpgallery/photo')->load(intval($photo));
        }

        if (!is_object($photo)) {
            return $this->_getUrl('*/*/*', $params);
        }

        $url = '';
        if ($this->_configHelper->showPhotoUrlWithParents()) {
            $parentUrls = $this->_getParentAlbumUrlKeys($photo->getRealAlbum());
            $url .= $parentUrls ? $parentUrls . '/' : '';
        }

        return $this->_getGalleryUrl($url . $photo->getUrlKey() . $this->getPhotoUrlSuffix(), $params);
    }

    public function getAlbumImageUrl($file)
    {
        return Mage::helper('mpgallery/album')->getImageUrl($file);
    }

    public function getPhotoImageUrl($file)
    {
        return Mage::helper('mpgallery/photo')->getImageUrl($file);
    }

    public function getPhotoEditUrl($photo, array $params = array())
    {
        $params['edit'] = 'photo';

        return $this->getPhotoUrl($photo, $params);
    }

    public function getPhotoEditSaveUrl($photo, array $params = array())
    {
        $params['edit'] = 'photo_save';

        return $this->getPhotoUrl($photo, $params);
    }

    public function getPhotoDeleteUrl($photo, array $params = array())
    {
        $params['edit'] = 'photo_delete';

        return $this->getPhotoUrl($photo, $params);
    }

    public function getCustomerPhotoUrl($action = null, $params = array())
    {
        $params['customer'] = null === $action ? 'photos' : $action;

        return $this->getGalleryUrl($params);
    }

    public function getUrl($path)
    {
        if (empty($this->_urls)) {
            $this->_initUrls();
        }

        if (array_key_exists($path, $this->_urls)) {
            return $this->_urls[$path];
        }

        return $path;
    }

    protected function _initUrls()
    {
        $this->_urls['mpgallery/customer/photos'] = $this->getCustomerPhotoUrl();

        return $this;
    }

    protected function _getGalleryUrl($path, array $queryParams = array())
    {
        $params['_direct'] = $this->getUrlPrefix() . '/' . $path;
        $params['_query']  = $queryParams;

        return Mage::getModel('core/url')->getUrl(null, $params);
    }

    protected function _getParentAlbumUrlKeys($album = null)
    {
        if (null === $album) {
            $album = $this->getAlbum();
        } elseif (!$album instanceof Mageplace_Gallery_Model_Album) {
            $album = Mage::getModel('mpgallery/album')->load(intval($album));
        }

        if (!$album instanceof Mageplace_Gallery_Model_Album) {
            return array();
        }

        static $parentAlbums = array();

        $id = $album->getId();
        if (!array_key_exists($id, $parentAlbums)) {
            $albums  = $album->getParentAlbums();
            $pathIds = $album->getPathIds();
            foreach ($pathIds as $albumId) {
                $parentAlbums[$id][] = $albums[$albumId]->getUrlKey();
            }

            if (isset($parentAlbums[$id])) {
                $parentAlbums[$id] = implode('/', $parentAlbums[$id]);
            } else {
                $parentAlbums[$id] = '';
            }
        }

        return $parentAlbums[$id];
    }
}