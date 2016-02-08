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
 * Class Mageplace_Gallery_Model_Review
 *
 * @method Mageplace_Gallery_Model_Review setName
 * @method Mageplace_Gallery_Model_Review setRate
 * @method Mageplace_Gallery_Model_Review setEmail
 * @method Mageplace_Gallery_Model_Review setComment
 * @method Mageplace_Gallery_Model_Review setUpdateDate
 * @method Mageplace_Gallery_Model_Review setPhotoId
 * @method Mageplace_Gallery_Model_Review setStatus
 * @method int getPhotoId
 * @method string getName
 * @method int getRate
 * @method string getEmail
 * @method string getComment
 * @method datetime getUpdateDate
 * @method datetime getCreationDate
 */
class Mageplace_Gallery_Model_Review extends Mage_Core_Model_Abstract
{
    const PENDING  = 0;
    const APPROVED = 1;
    const DISABLED = 2;

    const MULTIPLIER = 20;


    protected function _construct()
    {
        parent::_construct();

        $this->_init('mpgallery/review');
    }

    public function getRating()
    {
        return intval($this->getRate()) * self::MULTIPLIER;
    }

    /**
     * @param int $photoId
     *
     * @return int
     */
    public function getPhotoAverageRate($photoId)
    {
        if (!$this->hasData('photo_average_rate' . $photoId)) {
            $this->setData('photo_average_rate' . $photoId, intval($this->getResource()->getProductRating($photoId) * self::MULTIPLIER));
        }

        return $this->_getData('photo_average_rate' . $photoId);
    }

    /**
     * @param int $photoId
     *
     * @return int
     */
    public function getPhotoReviewCount($photoId)
    {
        if (!$this->hasData('photo_review_count' . $photoId)) {
            $this->setData('photo_review_count' . $photoId, intval($this->getResource()->getProductReviewCount($photoId)));
        }

        return $this->_getData('photo_review_count' . $photoId);
    }
}
