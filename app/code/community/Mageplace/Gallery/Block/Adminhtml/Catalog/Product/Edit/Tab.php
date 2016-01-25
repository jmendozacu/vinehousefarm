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
 * Class Mageplace_Gallery_Block_Adminhtml_Catalog_Product_Edit_Tab
 *
 * @method Mageplace_Gallery_Block_Adminhtml_Catalog_Product_Edit_Tab setHidePositions
 * @method bool|null getHidePositions
 */
class Mageplace_Gallery_Block_Adminhtml_Catalog_Product_Edit_Tab
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return $this->__('Gallery Albums');
    }

    public function getTabTitle()
    {
        return $this->__('Gallery Albums');
    }

    public function canShowTab()
    {
        return $this->__('Click here to assign Gallery album(s) to product');
    }

    public function isHidden()
    {
        if (Mage::getSingleton('admin/session')->isAllowed(Mageplace_Gallery_Helper_Const::ACL_PATH_PRODUCT)) {
            return false;
        } else {
            return true;
        }
    }

    public function getTabClass()
    {
        return 'product-gallery-albums-tab ajax';
    }

    public function getSkipGenerateContent()
    {
        return false;
    }

    public function getTabUrl()
    {
        return $this->getUrl('*/gallery/product', array('_current' => true));
    }
}
