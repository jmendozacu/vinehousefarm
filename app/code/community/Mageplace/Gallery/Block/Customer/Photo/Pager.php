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
 * Class Mageplace_Gallery_Block_Customer_Photo_Pager
 */
class Mageplace_Gallery_Block_Customer_Photo_Pager extends Mage_Page_Block_Html_Pager
{
    public function getPagerUrl($params = array())
    {
        if (!array_key_exists($this->getLimitVarName(), $params)) {
            $params[$this->getLimitVarName()] = $this->getLimit();
        }

        return Mage::helper('mpgallery/url')->getCustomerPhotoUrl(null, $params);
    }
}
