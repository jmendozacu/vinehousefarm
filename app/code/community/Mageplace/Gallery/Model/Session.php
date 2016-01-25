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
 * Class Mageplace_Gallery_Model_Session
 *
 * @method Mageplace_Gallery_Model_Session setLastAlbumId
 * @method int getLastAlbumId
 */
class Mageplace_Gallery_Model_Session extends Mage_Core_Model_Session_Abstract
{
    const DISPLAY_MODE = 'display_mode';
    const SORT_DIR     = 'sort_direction';
    const SORT_ORDER   = 'sort_order';
    const LIMIT_PAGE   = 'limit_page';

    public function __construct()
    {
        $this->init('mpgallery');
    }

    public function setDisplayMode($itemObject, $mode)
    {
        return $this->setData($itemObject . '_' . self::DISPLAY_MODE, $mode);
    }

    public function getDisplayMode($itemObject)
    {
        return $this->getData($itemObject . '_' . self::DISPLAY_MODE);
    }

    public function unsetDisplayMode($itemObject)
    {
        return $this->unsetData($itemObject . '_' . self::DISPLAY_MODE);
    }

    public function setSortOrder($itemObject, $order)
    {
        return $this->setData($itemObject . '_' . self::SORT_ORDER, $order);
    }

    public function getSortOrder($itemObject)
    {
        return $this->getData($itemObject . '_' . self::SORT_ORDER);
    }

    public function unsetSortOrder($itemObject)
    {
        return $this->unsetData($itemObject . '_' . self::SORT_ORDER);
    }

    public function setSortDir($itemObject, $dir)
    {
        return $this->setData($itemObject . '_' . self::SORT_DIR, $dir);
    }

    public function getSortDir($itemObject)
    {
        return $this->getData($itemObject . '_' . self::SORT_DIR);
    }

    public function unsetSortDir($itemObject)
    {
        return $this->unsetData($itemObject . '_' . self::SORT_DIR);
    }

    public function setLimitPage($itemObject, $limit)
    {
        return $this->setData($itemObject . '_' . self::LIMIT_PAGE, $limit);
    }

    public function getLimitPage($itemObject)
    {
        return $this->getData($itemObject . '_' . self::LIMIT_PAGE);
    }

    public function unsetLimitPage($itemObject)
    {
        return $this->unsetData($itemObject . '_' . self::LIMIT_PAGE);
    }

    public function setFieldsetState($container, $containerValue)
    {
        if(empty($container)) {
            return $this;
        }

        $fieldsetState = $this->getData('fieldset_state');
        if(!isset($fieldsetState[$container]) || !is_array($fieldsetState[$container])) {
            if(!is_array($fieldsetState)) {
                $fieldsetState = array();
            }

            $fieldsetState[$container] = array();
        }

        if (is_object(Mage::registry('current_album'))) {
            $albumId = Mage::registry('current_album')->getId();
        }

        if(empty($albumId)) {
            $albumId = (int)$this->getLastAlbumId();
        }

        $fieldsetState[$container][(int)$albumId] = $containerValue;

        $this->setData('fieldset_state', $fieldsetState);

        return $this;
    }

    public function getFieldsetState($container)
    {
        $fieldsetState = $this->getData('fieldset_state');

        if (is_object(Mage::registry('current_album'))) {
            $albumId = Mage::registry('current_album')->getId();
        }

        if(empty($albumId)) {
            $albumId = $this->getLastAlbumId();
        }

        $albumId = (int)$albumId;
        if (!isset($fieldsetState[$container][$albumId])) {
            return null;
        }


        return $fieldsetState[$container][$albumId];
    }
}
