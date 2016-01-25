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
 * Class Mageplace_Gallery_Block_Adminhtml_Catalog_Product_Edit_Tab_Albums
 *
 * @method Mageplace_Gallery_Block_Adminhtml_Catalog_Product_Edit_Tab_Albums setHidePositions
 * @method bool|null getHidePositions
 */
class Mageplace_Gallery_Block_Adminhtml_Catalog_Product_Edit_Tab_Albums extends Mageplace_Gallery_Block_Adminhtml_Album_Tree_Checkboxes
{
    public function __construct()
    {
        parent::__construct();

        $id = $this->getRequest()->getParam('id', false);

        if($id) {
            $albumIds = Mage::getModel('mpgallery/album')
                ->getCollection()
                ->addProductFilter($id)
                ->getIds();

            //var_dump($albumIds); die;
        } else {
            $albumIds = array();
        }

        $this->addAlbumIds($albumIds);
    }

    public function getLoadTreeUrl($expanded = null)
    {
        return $this->getUrl('*/gallery_product/albumJson', array('_current' => true));
    }
}
