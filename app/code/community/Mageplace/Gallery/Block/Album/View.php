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
 * Class Mageplace_Gallery_Block_Album_View
 *
 * @method Mageplace_Gallery_Block_Album_View setAlbumBlockName
 * @method Mageplace_Gallery_Block_Album_View setPhotoBlockName
 * @method Mageplace_Gallery_Block_Album_View setCmsBlockBlockName
 * @method string getAlbumBlockName
 * @method string getPhotoBlockName
 * @method string getCmsBlockBlockName
 *
 */
class Mageplace_Gallery_Block_Album_View extends Mageplace_Gallery_Block_Abstract
{
    protected $_defaultAlbumListBlock = 'mpgallery/album_list';
    protected $_defaultPhotoListBlock = 'mpgallery/photo_list';

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if (Mage::helper('mpgallery/config')->showBreadcrumbs()) {
            if($customTitle = $this->getCurrentAlbum()->getPageTitle()) {
                $arg = array('custom_title' => $customTitle);
            } else {
                $arg = array();
            }
            $this->getLayout()->createBlock('mpgallery/breadcrumbs', 'album_breadcrumbs', $arg);
        }

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $album = $this->getCurrentAlbum();
            if ($description = $album->getMetaDescription()) {
                $headBlock->setDescription($description);
            }

            if ($keywords = $album->getMetaKeywords()) {
                $headBlock->setKeywords($keywords);
            }
        }

        return $this;
    }

    public function getAlbumBlock()
    {
        if (null === ($block = $this->getBlockByType(Mageplace_Gallery_Model_Album::ALBUM))) {
            $block = $this->getLayout()
                ->createBlock($this->_defaultAlbumListBlock, 'album_list_' . microtime());
        }

        return $block;
    }

    public function getPhotoBlock()
    {
        if (null === ($block = $this->getBlockByType(Mageplace_Gallery_Model_Album::PHOTO))) {
            $block = $this->getLayout()
                ->createBlock($this->_defaultPhotoListBlock, 'photo_list_' . microtime());
        }

        return $block;
    }

    public function getCmsBlockBlock()
    {
        if (null === ($block = $this->getBlockByType(Mageplace_Gallery_Model_Album::BLOCK))) {
            $block = $this->getLayout()
                ->createBlock('cms/block', 'cms_block_' . microtime())
                ->setBlockId($this->getCurrentAlbum()->getCmsBlock());
        }

        return $block;
    }

    public function getBlockByType($type)
    {
        if ($blockName = $this->getData($type . '_block_name')) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }

        return null;
    }

    public function getBlockHtmlByType($type)
    {
        if (!$this->hasData('child_block_html_type_' . $type)) {
            $html = '';

            $getBlockMethodName = 'get' . uc_words($type, '') . 'Block';
            if (method_exists($this, $getBlockMethodName)) {
                $block = $this->$getBlockMethodName();
                if ($block instanceof Mage_Core_Block_Abstract) {
                    $html = $block->toHtml();
                }
            }

            $this->setData('child_block_html_type_' . $type, $html);
        }

        return $this->_getData('child_block_html_type_' . $type);

    }

    public function getBlocksByOrder()
    {
        return $this->getCurrentAlbum()->getDisplayOrderArray();
    }

    public function isAlbumMode()
    {
        return $this->getCurrentAlbum()->getDisplayMode() == Mageplace_Gallery_Model_Album::DISPLAY_MODE_ALBUM_ONLY;
    }

    public function isPhotoMode()
    {
        return $this->getCurrentAlbum()->getDisplayMode() == Mageplace_Gallery_Model_Album::DISPLAY_MODE_PHOTO_ONLY;
    }

    public function isContentMode()
    {
        return $this->getCurrentAlbum()->getDisplayMode() == Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_ONLY;
    }

    public function isMixedMode()
    {
        return $this->getCurrentAlbum()->getDisplayMode() == Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO;
    }

    public function isAlbumPhotoMode()
    {
        return $this->getCurrentAlbum()->getDisplayMode() == Mageplace_Gallery_Model_Album::DISPLAY_MODE_ALBUM_AND_PHOTO;
    }

    public function isContentAlbumMode()
    {
        return $this->getCurrentAlbum()->getDisplayMode() == Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM;
    }

    public function isContentPhotoMode()
    {
        return $this->getCurrentAlbum()->getDisplayMode() == Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_PHOTO;
    }

    public function canShowContent()
    {
        return $this->getCurrentAlbum()->canDisplayCmsBlock();
    }

    public function getDisplayImage()
    {
        return $this->getAlbumSettings()->getAlbumViewDisplayImage();
    }

    public function getDisplayName()
    {
        return $this->getAlbumSettings()->getAlbumViewDisplayName();
    }

    public function getDisplayUpdateDate()
    {
        return $this->getAlbumSettings()->getAlbumViewDisplayUpdateDate();
    }

    public function getDisplayShortDescription()
    {
        return $this->getAlbumSettings()->getAlbumViewDisplayShortDescr();
    }

    public function getDisplayDescription()
    {
        return $this->getAlbumSettings()->getAlbumViewDisplayDescr();
    }

    public function getImageSrc()
    {
        return $this->getImage($this->getCurrentAlbum(), 'image', $this->getAlbumSizes()->getAlbumSize());
    }

    public function isUploadButtonVisible()
    {
        return Mage::helper('mpgallery/config')->isPhotoUploadEnable()
        && (!Mage::helper('mpgallery/config')->isPhotoUploadOnlyRegistered() || Mage::getSingleton('customer/session')->isLoggedIn());
    }

    public function getPhotoUploadUrl()
    {
        if ($this->getCurrentActiveAlbum()) {
            return $this->_urlHelper->getAlbumUrl($this->getCurrentActiveAlbum(), array('upload' => 'photo'));
        }

        return '';
    }
}
