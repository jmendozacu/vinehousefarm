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
 * Class Mageplace_Gallery_Block_Breadcrumbs
 *
 * @method string getCustomTitle
 */
class Mageplace_Gallery_Block_Breadcrumbs extends Mage_Core_Block_Template
{
    protected $_breadcrumbs = array();

    public function __construct(array $args = array())
    {
        parent::__construct($args);

        if(!empty($args['custom_title'])) {
            unset($args['custom_title']);
        }

        if(!empty($args)) {
            $this->_breadcrumbs = $args;
        }
    }

    public function getTitleSeparator($store = null)
    {
        return ' ' . Mage::helper('mpgallery/config')->getTitleSeparator($store) . ' ';
    }

    protected function _prepareLayout()
    {
        /** @var Mage_Page_Block_Html_Breadcrumbs $breadcrumbsBlock */
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            if (Mage::helper('mpgallery/config')->showBreadcrumbsHomePage()) {
                $breadcrumbsBlock->addCrumb('home', array(
                    'label' => Mage::helper('catalog')->__('Home'),
                    'title' => Mage::helper('catalog')->__('Go to Home Page'),
                    'link'  => Mage::getBaseUrl()
                ));
            }

            $path = Mage::helper('mpgallery')->getBreadcrumbPath();
            if (count($this->_breadcrumbs)) {
                $path = array_merge($path, $this->_breadcrumbs);
            }

            $title = array();

            $root    = Mage::getModel('mpgallery/album')->load(Mageplace_Gallery_Model_Album::TREE_ROOT_ID);
            $title[] = $root->getName();
            if (Mage::helper('mpgallery/config')->showBreadcrumbsGalleryTitle()) {
                $crumbInfo = array(
                    'label' => $root->getName(),
                    'title' => $this->__('Go to Gallery Home Page'),
                );

                if (count($path) > 0) {
                    $crumbInfo['link'] = Mage::helper('mpgallery/url')->getGalleryUrl();
                }

                $breadcrumbsBlock->addCrumb('gallery', $crumbInfo);
            }

            $counter   = 0;
            $countPath = count($path);
            foreach ($path as $name => $breadcrumb) {
                if ($countPath == ++$counter) {
                    $breadcrumb['link'] = false;
                    $breadcrumb['last'] = true;
                }
                $breadcrumbsBlock->addCrumb($name, $breadcrumb);
                $title[] = $breadcrumb['label'];
            }

            if ($headBlock = $this->getLayout()->getBlock('head')) {
                if($customTitle = $this->getCustomTitle()) {
                    $title = $customTitle;
                } else {
                    $title = join($this->getTitleSeparator(), array_reverse($title));
                }

                $headBlock->setTitle($title);
            }
        }

        return parent::_prepareLayout();
    }
}
