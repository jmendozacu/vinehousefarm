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
 * Class Mageplace_Gallery_Model_Observer
 */
class Mageplace_Gallery_Model_Observer
{
    static protected $_saved = false;

    public function saveProductAlbums($observer)
    {
        if (!self::$_saved) {
            self::$_saved = true;

            $product = $observer->getEvent()->getProduct();

            try {
                $albumIds = Mage::app()->getRequest()->getPost('album_ids');
                if (null !== $albumIds) {
                    Mage::getResourceModel('mpgallery/album')->saveProductRelation($product->getId(), $albumIds);
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }
}