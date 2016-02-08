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
 * Class Mageplace_Gallery_Model_Source_Sortavailableall
 */
class Mageplace_Gallery_Model_Source_Sortavailableall extends Mageplace_Gallery_Model_Source_Sortavailable
{
    public function toOptionArray()
    {
        return parent::toOptionArray(true);
    }
}