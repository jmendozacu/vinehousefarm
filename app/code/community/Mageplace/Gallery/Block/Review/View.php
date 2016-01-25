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
 * Class Mageplace_Gallery_Block_Review_View
 *
 * @method Mageplace_Gallery_Model_Review getReview
 * @method Mageplace_Gallery_Block_Review_View setReview
 */
class Mageplace_Gallery_Block_Review_View extends Mage_Core_Block_Template
{
    /**
     * @var Mageplace_Gallery_Helper_Config
     */
    protected $_configHelper;

    protected function _construct()
    {
        parent::_construct();

        $this->_configHelper = Mage::helper('mpgallery/config');
        $this->setTemplate('mpgallery/review/view.phtml');
    }

    public function getRate()
    {
        return $this->getReview()->getRating();
    }

    protected function _toHtml()
    {
        if (is_object($this->getReview()) && $this->getReview()->getId()) {
            return parent::_toHtml();
        }

        return '';
    }


}
