<?php

/**
 * MagePlace Gallery Extension
 *
 * @category    Mageplace_Gallery
 * @package     Mageplace_Gallery
 * @copyright   Copyright (c) 2014 Mageplace. (http://www.mageplace.com)
 * @license     http://www.mageplace.com/disclaimer.html
 */
abstract class Mageplace_Gallery_Model_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * @return Mageplace_Gallery_Helper_Item
     */
    abstract public function helper();
}
