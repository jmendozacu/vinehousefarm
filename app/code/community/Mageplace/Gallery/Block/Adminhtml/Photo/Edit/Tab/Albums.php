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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Albums
 *
 * @method Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Albums setHidePositions
 * @method bool|null getHidePositions
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Albums extends Mageplace_Gallery_Block_Adminhtml_Album_Tree_Checkboxes
{
    public function __construct()
    {
        parent::__construct();

        $this->addAlbumIds((array)$this->getPhoto()->getAlbumIds());

        $this->setTemplate('mpgallery/photo/edit/tab/albums.phtml');
    }

    /**
     * @return Mageplace_Gallery_Model_Photo
     */
    public function getPhoto()
    {
        return Mage::registry('current_photo');
    }

    public function getPositions()
    {
        if ($this->getPhoto() && $this->getPhoto()->hasData('positions')) {
            return $this->getPhoto()->getData('positions');
        } else {
            return array();
        }
    }

    public function getLoadTreeUrl($expanded = null)
    {
        return $this->getUrl('*/*/albumsJson', array('_current' => true));
    }
}
