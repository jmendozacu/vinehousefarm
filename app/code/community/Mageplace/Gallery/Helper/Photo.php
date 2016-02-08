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
 * Class Mageplace_Gallery_Helper_Photo
 */
class Mageplace_Gallery_Helper_Photo extends Mageplace_Gallery_Helper_Item
{
    const PHOTO_IMAGE_PATH = 'photo';

    protected function getItemPath()
    {
        return self::PHOTO_IMAGE_PATH;
    }

    public function canShow($photo, $edit = false)
    {
        if (!$photo instanceof Mageplace_Gallery_Model_Photo) {
            $photo = Mage::getModel('mpgallery/photo')->load(intval($photo));
        }

        if($edit && $photo->isOwner(Mage::getSingleton('customer/session')->getCustomerId())) {
            return true;
        }

        return parent::canShow($photo);
    }

    public function canUpload()
    {
        return Mage::helper('mpgallery/config')->isPhotoUploadEnable()
        && (!Mage::helper('mpgallery/config')->isPhotoUploadOnlyRegistered()
            || Mage::getSingleton('customer/session')->isLoggedIn());
    }

    public function canEdit()
    {
        return $this->canUpload()
        && Mage::helper('mpgallery/config')->isPhotoUploadCustomerView()
        && Mage::helper('mpgallery/config')->isPhotoUploadCustomerEdit();
    }

    public function canDelete()
    {
        return $this->canUpload()
        && Mage::helper('mpgallery/config')->isPhotoUploadCustomerView()
        && Mage::helper('mpgallery/config')->isPhotoUploadCustomerDelete();
    }

    public function canReview()
    {
        return Mage::helper('mpgallery/config')->isReviewEnabled();
    }
}