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
 * Class Mageplace_Gallery_Model_Mysql4_Review
 */
class Mageplace_Gallery_Model_Mysql4_Review extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_photoTable;

    protected function _construct()
    {
        $this->_init('mpgallery/review', 'review_id');

        $this->_photoTable = $this->getTable('mpgallery/photo');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);

        $id = $object->getId();

        if (!$id) {
            $object->setCreationDate(Mage::getSingleton('core/date')->gmtDate());
        }

        $object->setUpdateDate(Mage::getSingleton('core/date')->gmtDate());

        return $this;
    }

    /**
     * @param int $photoId
     *
     * @return float|int
     */
    public function getProductRating($photoId)
    {
        $photoId = (int)$photoId;
        if (!$photoId) {
            return 0;
        }

        return (float)$this->_getReadAdapter()->fetchOne(
            $this->_getReadAdapter()->select()
                ->from($this->getMainTable(), array(
                    'rateAverage' => new Zend_Db_Expr('AVG(rate)')))
                ->where('photo_id = ?', $photoId)
                ->where('status = ?', 1)
        );
    }

    public function getProductReviewCount($photoId)
    {
        $photoId = (int)$photoId;
        if (!$photoId) {
            return 0;
        }

        return (int)$this->_getReadAdapter()->fetchOne(
            $this->_getReadAdapter()->select()
                ->from($this->getMainTable(), array(
                    'reviewCount' => new Zend_Db_Expr('COUNT(' . $this->getIdFieldName() . ')')))
                ->where('photo_id = ?', $photoId)
                ->where('status = ?', 1)
        );
    }
}