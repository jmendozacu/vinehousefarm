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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tabs
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('albumTabs');
        $this->setDestElementId('album_tab_content');
        $this->setTitle($this->__('Album Information'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    public function getAlbum()
    {
        return Mage::registry('current_album');
    }

    protected function _prepareLayout()
    {
        $this->addTab('general', array(
            'label'   => $this->__('General Settings'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_album_tab_general', 'album.general')->toHtml(),
        ));

        $this->addTab('meta', array(
            'label'   => $this->__('Album Meta'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_album_tab_meta', 'album.meta')->toHtml(),
        ));

        $this->addTab('display', array(
            'label'   => $this->__('Display Settings'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_album_tab_display', 'album.display')->toHtml(),
        ));

        $this->addTab('design', array(
            'label'   => Mage::helper('core')->__('Custom Design'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_album_tab_design', 'album.design')->toHtml(),
        ));

        $this->addTab('sizes', array(
            'label'   => $this->__('Sizes'),
            'content' => $this->getLayout()->createBlock('mpgallery/adminhtml_album_tab_sizes', 'album.sizes')->toHtml(),
        ));

        return parent::_prepareLayout();
    }
}