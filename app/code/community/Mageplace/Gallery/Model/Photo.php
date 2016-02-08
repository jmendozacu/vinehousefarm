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
 * Class Mageplace_Gallery_Model_Photo
 *
 * @method Mageplace_Gallery_Model_Photo setName
 * @method Mageplace_Gallery_Model_Photo setIsActive
 * @method Mageplace_Gallery_Model_Photo setDescription
 * @method Mageplace_Gallery_Model_Photo setUrlKey
 * @method Mageplace_Gallery_Model_Photo setCreationDate
 * @method Mageplace_Gallery_Model_Photo setUpdateDate
 * @method Mageplace_Gallery_Model_Photo setAlbum
 * @method Mageplace_Gallery_Model_Photo setAlbumIds
 * @method Mageplace_Gallery_Model_Photo setRealAlbum
 * @method Mageplace_Gallery_Model_Photo setImageFile
 * @method Mageplace_Gallery_Model_Photo setAuthorName
 * @method Mageplace_Gallery_Model_Photo setAuthorEmail
 * @method Mageplace_Gallery_Model_Photo setStoreId
 * @method Mageplace_Gallery_Model_Photo setCustomerId
 * @method Mageplace_Gallery_Model_Photo setDesignUseParentSettings
 * @method Mageplace_Gallery_Model_Photo setDisplayUseParentSettings
 * @method Mageplace_Gallery_Model_Photo setSizeUseParentSettings
 * @method Mageplace_Gallery_Model_Photo setContentHeading
 * @method string getName
 * @method int getIsActive
 * @method string getDescription
 * @method string getShortDescription
 * @method string getUrlKey
 * @method datetime getCreationDate
 * @method datetime getUpdateDate
 * @method array getStoreId
 * @method array getCustomerGroupIds
 * @method Mageplace_Gallery_Model_Album getRealAlbum
 * @method int getDisplayUseParentSettings
 * @method int getDesignUseParentSettings
 * @method int getSizeUseParentSettings
 * @method array getAlbumIds
 * @method int getCustomerId
 * @method string getAuthorName
 * @method string getAuthorEmail
 * @method string getPageTitle
 * @method string getContentHeading
 */
class Mageplace_Gallery_Model_Photo extends Mageplace_Gallery_Model_Abstract
{
    const CACHE_TAG = 'mpgallery_photo';

    const DISPLAY_MODE_PHOTO_LIST = 0;
    const DISPLAY_MODE_LIST_PHOTO = 1;
    const DISPLAY_MODE_PHOTO      = 2;

    const PENDING  = 0;
    const APPROVED = 1;
    const DISABLED = 2;

    protected function _construct()
    {
        parent::_construct();

        $this->_init('mpgallery/photo');
    }

    public function helper()
    {
        return Mage::helper('mpgallery/photo');
    }

    public function isActive()
    {
        return $this->getIsActive() == self::APPROVED;
    }

    public function setPendingStatus()
    {
        return $this->setIsActive(self::PENDING);
    }

    public function setEnableStatus()
    {
        return $this->setIsActive(self::APPROVED);
    }

    public function setDisableStatus()
    {
        return $this->setIsActive(self::DISABLED);
    }

    /**
     * @return Mageplace_Gallery_Model_Album|null
     */
    public function getAlbum()
    {
        if (!$this->hasData('album')) {
            $ids = $this->getAlbumIds();
            if (is_array($ids) && count($ids) == 1) {
                $album = Mage::getModel('mpgallery/album')->load(array_shift($ids));
                if ($album->getId()) {
                    $this->setData('album', $album);
                }
            }
        }

        return $this->_getData('album');
    }

    public function getDesignSettings()
    {
        if (null === $this->_getData('design_settings')) {
            if ($this->getDesignUseParentSettings()) {
                if ($album = $this->getRealAlbum()) {
                    $settings = $album->getResource()->getParentAlbumDesign($album);
                }
            } else {
                $settings = $this;
            }

            if (isset($settings)) {
                $settings = array_intersect_key($settings->getData(), $this->_getResource()->getDesignFields());
            } else {
                $settings = array();
            }

            $this->setData('design_settings', new Varien_Object($settings));
        }

        return $this->_getData('design_settings');
    }

    public function getDisplaySettings()
    {
        if (null === $this->_getData('display_settings')) {
            if ($this->getDisplayUseParentSettings()) {
                if ($album = $this->getRealAlbum()) {
                    $settings = $album->getResource()->getParentAlbumDisplay($album);
                }
            } else {
                $settings = $this;
            }

            if (isset($settings)) {
                $settings = array_intersect_key($settings->getData(), $this->_getResource()->getDisplayFields());
            } else {
                $settings = array();
            }

            $this->setData('display_settings', new Mageplace_Gallery_Model_Settings($settings));
        }

        return $this->_getData('display_settings');
    }

    public function getSizeSettings()
    {
        if (null === $this->_getData('size_settings')) {
            if ($this->getSizeUseParentSettings()) {
                if ($album = $this->getRealAlbum()) {
                    $settings = $album->getResource()->getParentAlbumSize($album);
                }
            } else {
                $settings = $this;
            }

            if (isset($settings)) {
                $settings = array_intersect_key($settings->getData(), $this->_getResource()->getSizeFields());
            } else {
                $settings = array();
            }

            $this->setData('size_settings', new Mageplace_Gallery_Model_Settings($settings));
        }

        return $this->_getData('size_settings');
    }

    /**
     * @param Mageplace_Gallery_Model_Album|string|int|null $album
     *
     * @return Mageplace_Gallery_Model_Mysql4_Photo_Collection
     */
    public function getAlbumPhotos($album = null)
    {
        if (null === $album) {
            if ($this->getRealAlbum()) {
                $album = $this->getRealAlbum()->getId();
            }
        } elseif ($album instanceof Mageplace_Gallery_Model_Album) {
            $album = $album->getId();
        }

        $album = (int)$album;

        if (!$album) {
            return null;
        }

        if (!$this->hasData('album_photos_' . $album)) {
            $this->setAlbumPhotos(
                $this->getCollection()
                    ->addParentFilter($album)
                    ->addIsActiveFilter()
                    ->addStoreFilter()
                    ->addCustomerGroupFilter(),
                $album
            );
        }

        return $this->_getData('album_photos_' . $album);
    }

    /**
     * @param Mageplace_Gallery_Model_Mysql4_Photo_Collection $collection
     * @param Mageplace_Gallery_Model_Album|string|int|null   $album
     *
     * @return $this
     */
    public function setAlbumPhotos($collection, $album = null)
    {
        if (null === $album) {
            $album = $this->getRealAlbum()->getId();
        } elseif ($album instanceof Mageplace_Gallery_Model_Album) {
            $album = $album->getId();
        }

        $album = (int)$album;

        return $this->setData('album_photos_' . $album, $collection);
    }

    public function getPhotoIds()
    {
        if (!$this->hasData('photo_ids')) {
            $this->setData('photo_ids', $this->getResource()->getPhotoIds($this));
        }

        return $this->_getData('photo_ids');
    }

    public function getPreviousPhoto()
    {
        if (!$this->hasData('previous_photo')) {
            $ids = $this->getPhotoIds();
            if (count($ids) > 0) {
                $pos = array_search($this->getId(), $ids);

                if (!$pos) {
                    $this->setData('previous_photo', null);
                } else {
                    $this->setData('previous_photo', Mage::getModel('mpgallery/photo')->load($ids[$pos - 1]));
                }
            } else {
                $this->setData('previous_photo', false);
            }
        }

        return $this->_getData('previous_photo');
    }

    public function getNextPhoto()
    {
        if (!$this->hasData('next_photo')) {
            $ids = $this->getPhotoIds();
            if (count($ids) > 0) {
                $pos = array_search($this->getId(), $ids);

                if (false !== $pos && ++$pos >= count($ids)) {
                    $this->setData('next_photo', null);
                } else {
                    $this->setData('next_photo', Mage::getModel('mpgallery/photo')->load($ids[$pos]));
                }
            } else {
                $this->setData('next_photo', false);
            }
        }

        return $this->_getData('next_photo');
    }

    public function isOwner($customerId)
    {
        return $this->getCustomerId() > 0 && $this->getCustomerId() == $customerId;
    }

    protected function _extractSettings(Mageplace_Gallery_Model_Album $album, $fields)
    {
        $allAlbumSettings = $album->getData();
        $albumSettings    = array_intersect_key($allAlbumSettings, $fields);
        foreach ($albumSettings as $key => $as) {
            if (!$as) {
                unset($albumSettings[$key]);
            }
        }

        $allPhotoSettings = $this->getData();
        $photoSettings    = array_intersect_key($allPhotoSettings, $fields);
        foreach ($photoSettings as $key => $as) {
            if (!$as) {
                unset($photoSettings[$key]);
            }
        }

        return new Varien_Object(array_merge($albumSettings, $photoSettings));
    }
}
