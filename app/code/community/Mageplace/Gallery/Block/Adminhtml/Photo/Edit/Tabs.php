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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tabs
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    const TABS_BLOCK_ID = 'photo_tabs';

    public function __construct()
    {
        parent::__construct();

        $this->setId(self::TABS_BLOCK_ID);
        $this->setDestElementId(Mageplace_Gallery_Block_Adminhtml_Photo_Edit::EDIT_FORM_ID);
        $this->setTitle($this->__('Photo Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('details_section', array(
            'label'   => $this->__('General'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_photo_edit_tab_details', 'photo.details')->toHtml(),
        ));

        $this->addTab('albums_section', array(
            'label' => $this->__('Albums'),
            'url'   => $this->getUrl('*/*/albums', array('_current' => true)),
            'class' => 'ajax',
        ));

        $this->addTab('meta_section', array(
            'label'   => Mage::helper('adminhtml')->__('Photo Meta'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_photo_edit_tab_meta', 'photo.meta')->toHtml(),
        ));

        $this->addTab('display_section', array(
            'label'   => $this->__('Display Settings'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_photo_edit_tab_display', 'photo.display')->toHtml(),
        ));

        $this->addTab('design_section', array(
            'label'   => Mage::helper('adminhtml')->__('Design'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_photo_edit_tab_design', 'photo.design')->toHtml(),
        ));

        $this->addTab('sizes', array(
            'label'   => $this->__('Sizes'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_photo_edit_tab_sizes', 'photo.sizes')->toHtml(),
        ));

        $this->_updateActiveTab();

        return parent::_beforeToHtml();
    }

    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if ($tabId) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if ($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }
}