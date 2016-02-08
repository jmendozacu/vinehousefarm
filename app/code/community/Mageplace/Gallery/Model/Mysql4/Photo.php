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
 * Class Mageplace_Gallery_Model_Mysql4_Photo
 */
class Mageplace_Gallery_Model_Mysql4_Photo extends Mageplace_Gallery_Model_Mysql4_Abstract
{
    protected static $DISPLAY_FIELDS = array(
        'photo_view_display_mode'            => null,
        'photo_view_slide_photos'            => null,
        'photo_view_list_sort_by'            => null,
        'photo_view_list_sort_dir'           => null,
        'photo_view_list_per_page'           => null,
        'photo_view_display_name'            => null,
        'photo_view_display_update_date'     => null,
        'photo_view_display_short_descr'     => null,
        'photo_view_display_descr'           => null,
        'photo_view_display_back_url'        => null,
        'photo_carousel_display_name'        => null,
        'photo_carousel_display_short_descr' => null,
        'photo_carousel_display_update_date' => null,
        'photo_carousel_display_show_link'   => null,
        'photo_view_display_review'          => null,
        'photo_carousel_display_rate'        => null,
    );

    protected static $DESIGN_FIELDS = array(
        'design_custom' => null,
        'page_layout'   => null
    );

    protected static $SIZE_FIELDS = array(
        'photo_size'                => null,
        'photo_carousel_thumb_size' => null
    );

    protected $_albumPhotoTable;

    protected function _construct()
    {
        $this->_init('mpgallery/photo', 'photo_id');

        $this->_storeTable         = $this->getTable('mpgallery/photo_store');
        $this->_customerGroupTable = $this->getTable('mpgallery/photo_customer_group');
        $this->_albumPhotoTable    = $this->getTable('mpgallery/album_photo');
        $this->_albumTable         = $this->getTable('mpgallery/album');
        $this->_photoTable         = $this->getMainTable();
    }

    protected function _helper()
    {
        return Mage::helper('mpgallery/photo');
    }

    public function getDisplayFields()
    {
        return self::$DISPLAY_FIELDS;
    }

    public function getDesignFields()
    {
        return self::$DESIGN_FIELDS;
    }

    public function getSizeFields()
    {
        return self::$SIZE_FIELDS;
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        parent::_afterLoad($object);

        if (!$object->getId()) {
            return $this;
        }

        // get photo to albums relation
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->_albumPhotoTable, 'album_id')
            ->where($this->getIdFieldName() . ' = ?', $object->getId());

        if ($albumArray = $this->_getReadAdapter()->fetchCol($select)) {
            $object->setData('album_ids', $albumArray);
        } else {
            $object->setData('album_ids', array());
        }

        // get photo positions inside albums
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->_albumPhotoTable, array('album_id', 'position'))
            ->where($this->getIdFieldName() . ' = ?', $object->getId());

        if ($posArray = $this->_getReadAdapter()->fetchPairs($select)) {
            $object->setData('positions', $posArray);
        } else {
            $object->setData('positions', array());
        }

        return $this;
    }

    /**
     * @param Mageplace_Gallery_Model_Album|Mage_Core_Model_Abstract $object
     *
     * @throws Mageplace_Gallery_Exception
     * @return $this
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);

        return $this;
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);

        // process photos to albums relation
        if ($object->hasData('album_ids')) {
            $albumIds = $object->getData('album_ids');
            if (!is_array($albumIds)) {
                $albumIds = explode(',', $albumIds);
            }

            $albumIds = array_unique($albumIds);

            if ($object->getData('only_current_album_ids')) {
                $condition = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . ' = ?', $object->getId())
                    . $this->_getWriteAdapter()->quoteInto(' AND album_id IN (?)', $albumIds);
            } else {
                $condition = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . ' = ?', $object->getId());
            }

            $this->_getWriteAdapter()->delete($this->_albumPhotoTable, $condition);

            $positions = $object->getData('positions');
            if (!is_array($positions)) {
                $positions = array();
            }

            foreach ($albumIds as $albumId) {
                $albumId = (int)$albumId;
                if (!$albumId) {
                    continue;
                }

                $this->_getWriteAdapter()->insert(
                    $this->_albumPhotoTable,
                    array(
                        $this->getIdFieldName() => $object->getId(),
                        'album_id'              => $albumId,
                        'position'              => array_key_exists($albumId, $positions) ? $positions[$albumId] : 0
                    )
                );
            }
        }

        return $this;
    }

    public function getPhotoTitleById($id)
    {
        return $this->getTitleById($id);
    }

    public function getPhotoByUrlKey($urlKey)
    {
        return $this->getIdByUrlKey($urlKey);
    }

    /**
     * @param Mageplace_Gallery_Model_Photo $photo
     *
     * @return array
     */
    public function getPhotoIds($photo)
    {
        if ($photos = $photo->getAlbumPhotos()) {
            return $this->_getReadAdapter()->fetchCol($photos->getSelect()->columns($this->getIdFieldName()));
        }

        return array();
    }
}