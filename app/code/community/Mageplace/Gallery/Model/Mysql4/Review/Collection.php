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
 * Class Mageplace_Gallery_Model_Mysql4_Review_Collection
 */
class Mageplace_Gallery_Model_Mysql4_Review_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_photoTable;

    protected function _construct()
    {
        $this->_init('mpgallery/review');

        if (null === $this->_idFieldName) {
            $this->_setIdFieldName($this->getResource()->getIdFieldName());
        }

        $this->_photoTable = $this->getTable('mpgallery/photo');
    }

    public function addIsActiveFilter()
    {
        return $this->addFieldToFilter('main_table.status', array('eq' => Mageplace_Gallery_Model_Review::APPROVED));
    }

    /**
     * @param array|int $photoId
     *
     * @return $this
     */
    public function addPhotoFilter($photoId)
    {
        return $this->addFieldToFilter('main_table.photo_id', array('in' => $photoId));
    }

    public function joinPhoto()
    {
        $this->getSelect()
            ->join(
                array('photo_table' => $this->_photoTable),
                'main_table.photo_id = photo_table.photo_id',
                array('photo_name' => 'photo_table.name')
            );

        return $this;
    }

    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->reset(Zend_Db_Select::GROUP);

        $countSelect->columns('COUNT(DISTINCT(main_table.' . $this->_idFieldName . '))');

        return $countSelect;
    }
}