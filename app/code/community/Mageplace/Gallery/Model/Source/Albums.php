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
 * Class Mageplace_Gallery_Model_Source_Albums
 */
class Mageplace_Gallery_Model_Source_Albums extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        return Mage::getModel('mpgallery/album')->getLevelNames();
    }
}