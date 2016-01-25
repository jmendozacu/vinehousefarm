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
 * Class Mageplace_Gallery_Block_Customer_Photo_List
 *
 * @method Mageplace_Gallery_Block_Customer_Photo_List setPhotos
 * @method Mageplace_Gallery_Model_Mysql4_Photo_Collection getPhotos
 */
class Mageplace_Gallery_Block_Customer_Photo_List extends Mageplace_Gallery_Block_Customer_Photo_Abstract
{
    protected $_defaultThumbSize = '50x50';

    public function __construct()
    {
        parent::__construct();

        if ($this->isEnable()) {
             $this->setData('photos',
                Mage::getModel('mpgallery/photo')->getCollection()
                    ->addCustomerFilter($this->getCustomer()->getId())
                    ->setOrder('creation_date', 'DESC')
            );
        }
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()
            ->createBlock('mpgallery/customer_photo_pager', 'mpgallery_photos_pager')
            ->setCollection($this->getPhotos());

        $this->setChild('pager', $pager);

        $this->getPhotos()->load();

        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function isUploadButtonVisible()
    {
        return Mage::helper('mpgallery/photo')->canUpload();
    }

    public function getPhotoSize()
    {
        if($size = $this->_configHelper->getCustomerPhotoListThumbSize()) {
            return $size;
        }

        return $this->_defaultThumbSize;
    }

    public function getPhotoSrc($photo)
    {
        return $this->getImage($photo, 'thumbnail', $this->getPhotoSize())->__toString();
    }

    public function getStatusName($status)
    {
        if(!$this->hasData('status_name' . $status)) {
            $statuses = Mage::getSingleton('mpgallery/source_photostatus')->toOptionHash();
            if(array_key_exists($status, $statuses)) {
                $this->setData('status_name' . $status, $statuses[$status]);
            } else {
                $this->setData('status_name' . $status, $this->__('Unknown'));
            }
        }

        return $this->_getData('status_name' . $status);
    }

    protected function _toHtml()
    {
        if ($this->isEnable()) {
            return parent::_toHtml();
        }

        return '';
    }
}
