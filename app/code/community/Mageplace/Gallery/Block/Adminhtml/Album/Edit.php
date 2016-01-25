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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Edit
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'album_id';
        $this->_blockGroup = 'mpgallery';
        $this->_controller = 'adminhtml_album';
        $this->_mode       = 'edit';

        parent::__construct();

        $this->setTemplate('mpgallery/album/edit.phtml');
    }
}
