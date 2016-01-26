<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");

class Vinehousefarm_Productvideo_Adminhtml_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    public function videosGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('catalog.product.edit.tab.videos')
            ->setProductBirds($this->getRequest()->getPost('product_videos', null));
        $this->renderLayout();
    }

    protected function _isAllowed()
    {
        return true;
    }
}