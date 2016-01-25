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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Edit_Form
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Edit_Form extends Mageplace_Gallery_Block_Adminhtml_Album
{
    protected $_additionalButtons = array();

    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('mpgallery/album/edit/form.phtml');
    }

    protected function _prepareLayout()
    {
        $albumId = $this->getAlbumId();

        $this->setChild(
            'tabs',
            $this->getLayout()->createBlock('mpgallery/adminhtml_album_tabs', 'tabs')
        );

        $this->setChild('save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'   => $this->__('Save'),
                    'onclick' => "albumSubmit('" . $this->getSaveUrl() . "', true)",
                    'class'   => 'save'
                ))
        );

        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'   => $this->__('Delete'),
                    'onclick' => "albumDelete('" . $this->getDeleteUrl() . "', true, {$albumId})",
                    'class'   => 'delete'
                ))
        );

        $this->setChild('reset_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'   => $this->__('Reset'),
                    'onclick' => "albumReset('" . $this->getResetUrl() . "',true)"
                ))
        );

        return parent::_prepareLayout();
    }

    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    public function getResetButtonHtml()
    {
        return $this->getChildHtml('reset_button');
    }

    public function getTabsHtml()
    {
        return $this->getChildHtml('tabs');
    }

    public function getHeader()
    {
        if ($this->getAlbumId()) {
            return $this->getAlbumName();
        } else {
            $parentId = (int)$this->getRequest()->getParam('parent');
            if ($parentId && ($parentId != Mage_Catalog_Model_Category::TREE_ROOT_ID)) {
                return Mage::helper('catalog')->__('New Child Album');
            } else {
                return Mage::helper('catalog')->__('New Root Album');
            }
        }
    }

    public function getDeleteUrl(array $params = array())
    {
        $params['_current'] = true;

        return $this->getUrl('*/gallery_album/delete', $params);
    }

    public function getResetUrl(array $params = array())
    {
        $params['_current'] = true;

        return $this->getUrl($this->getAlbumId() > 0 ? '*/gallery_album/edit' : '*/gallery_album/add', $params);
    }

    public function getRefreshPathUrl(array $params = array())
    {
        $params['_current'] = true;

        return $this->getUrl('*/gallery_album/refreshPath', $params);
    }

    public function isAjax()
    {
        return Mage::app()->getRequest()->isXmlHttpRequest() || Mage::app()->getRequest()->getParam('isAjax');
    }
}
