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
 * Class Mageplace_Gallery_Adminhtml_GalleryController
 */
class Mageplace_Gallery_Adminhtml_GalleryController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_forward('albums');
    }

    public function albumsAction()
    {
        $this->_forward('index', 'gallery_album');
    }

    public function photosAction()
    {
        $this->_forward('index', 'gallery_photo');
    }

    public function multiuploadAction()
    {
        $this->_forward('index', 'gallery_multiupload');
    }

    public function reviewsAction()
    {
        $this->_forward('index', 'gallery_review');
    }

    public function productAction()
    {
        $this->_forward('index', 'gallery_product');
    }
}