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
 * Class Mageplace_Gallery_Block_Album_List
 *
 */
class Mageplace_Gallery_Block_Photo_List extends Mageplace_Gallery_Block_List
{
    public function getGalleryObjectName()
    {
        return Mageplace_Gallery_Helper_Const::PHOTO;
    }

    public function initCollection()
    {
        $this->setCollection(Mage::getModel('mpgallery/photo')->getAlbumPhotos($this->getCurrentAlbum()));
    }

    public function getPhotos()
    {
        return $this->getCollection();
    }

    public function getImagesJson()
    {
        if (!$this->hasData('images_json')) {
            if ($this->_configHelper->isLightboxPagePhotos()) {
                $photos = $this->getCollection();
            } else {
                $photos = Mage::getModel('mpgallery/photo')->getAlbumPhotos($this->getCurrentAlbum())
                    ->addOrder($this->getOrder(), $this->getDir());
            }

            $photosImgs = array();
            foreach ($photos as $photo) {
                $photosImgs[] = array(
                    'url'   => $this->getImage($photo, 'image', $this->getAlbumSizes()->getPhotoSize())->__toString(),
                    'title' => $this->stripTags($photo->getName(), null, true),
                    'id'    => $photo->getUrlKey()
                );
            }


            $this->setData('images_json', Zend_Json::encode($photosImgs));
        }

        return $this->_getData('images_json');
    }

    public function getPhotoAvgRate($photo)
    {
        return Mage::getModel('mpgallery/review')->getPhotoAverageRate($photo->getId());
    }
}
