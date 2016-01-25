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
 * Class Mageplace_Gallery_Helper_Data
 */
class Mageplace_Gallery_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_albumPath;

    public function cleanText($text)
    {
        $text = strip_tags($text);
        $text = str_replace("\t", '', $text);
        $text = trim($text);

        return $text;
    }

    /**
     * @param Mageplace_Gallery_Model_Album|Mageplace_Gallery_Model_Photo $model
     *
     * @throws Mageplace_Gallery_Exception
     * @return bool
     */
    public function canShow($model)
    {
        if (!$model instanceof Mage_Core_Model_Abstract) {
            throw new Mageplace_Gallery_Exception('Wrong model');
        }

        if (!$model->getId()) {
            return false;
        }

        if (!$model->isActive()) {
            return false;
        }

        if (!in_array('0', $model->getStoreId(), true) && !in_array(Mage::app()->getStore()->getId(), $model->getStoreId())) {
            return false;
        }

        if ($model->getOnlyForRegistered()) {
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                if (!in_array(Mage::helper('customer')->getCustomer()->getGroupId(), $model->getCustomerGroupIds())) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @return Mageplace_Gallery_Model_Album|null
     */
    public function getAlbum()
    {
        return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ALBUM);
    }

    /**
     * @return Mageplace_Gallery_Model_Album|null
     */
    public function getActiveAlbum()
    {
        return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_ACTIVE_ALBUM);
    }

    /**
     * @return Mageplace_Gallery_Model_Photo|null
     */
    public function getPhoto()
    {
        return Mage::registry(Mageplace_Gallery_Helper_Const::CURRENT_PHOTO);
    }

    public function getBreadcrumbPath()
    {
        if (!$this->_albumPath) {
            $path = array();
            if (($album = $this->getAlbum()) && $album->getId() && $this->getActiveAlbum()) {
                $albums  = $album->getActiveParentAlbums();
                $pathIds = $album->getPathIds();
                foreach ($pathIds as $albumId) {
                    if (isset($albums[$albumId]) && $albums[$albumId]->getName()) {
                        $path['album' . $albumId] = array(
                            'label' => $albums[$albumId]->getName(),
                            'link'  => Mage::helper('mpgallery/album')->canShow($albumId) ? Mage::helper('mpgallery/url')->getAlbumUrl($albums[$albumId]) : ''
                        );
                    }
                }
            }

            if (($photo = $this->getPhoto()) && ($photoId = $photo->getId())) {
                $path['photo' . $photoId] = array(
                    'label' => $photo->getName(),
                    'link'  => Mage::helper('mpgallery/photo')->canShow($photoId) ? Mage::helper('mpgallery/url')->getPhotoUrl($photo) : ''
                );
            }

            $this->_albumPath = $path;
        }

        return $this->_albumPath;
    }

    public function getUploadFileMaxSize()
    {
        $size = max($this->_getBytes(ini_get('upload_max_filesize')), $this->_getBytes(ini_get('post_max_size')));

        if ($size) {
            return $size / 1024 / 1024 . 'Mb';
        } else {
            return $size;
        }
    }

    function returnMIMEType($filename)
    {
        if(function_exists('finfo_file')) {
            $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
        } elseif (function_exists('mime_content_type')) {
            $mimeType = mime_content_type($filename);
        }

        if(isset($mimeType) && $mimeType) {
            return $mimeType;
        }

        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            default :
                if(function_exists("mime_content_type"))
                {
                    $fileSuffix = mime_content_type($filename);
                }

                return "unknown/" . trim($fileSuffix[0], ".");
        }
    }
    protected function _isAlbumLink($albumId)
    {
        if ($this->getPhoto()) {
            return true;
        }

        if (($album = $this->getAlbum()) && ($albumId != $album->getId())) {
            return true;
        }

        return false;
    }

    protected function _getBytes($val)
    {
        $val  = trim($val);
        $last = strtolower(substr($val, -1));
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
}