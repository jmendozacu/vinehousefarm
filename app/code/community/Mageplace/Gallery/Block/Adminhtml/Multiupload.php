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
 * Class Mageplace_Gallery_Block_Adminhtml_Multiupload
 */
class Mageplace_Gallery_Block_Adminhtml_Multiupload extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'mpgallery';
        $this->_controller = 'adminhtml';
        $this->_mode       = 'multiupload';

        parent::__construct();

        $this->_removeButton('reset');
        $this->_removeButton('back');
        $this->_updateButton('save', 'label', $this->__('Create photos'));
        $this->_updateButton('save', 'id', 'save_button');
    }

    public function getHeaderText()
    {
        return $this->__('Photos Multiupload');
    }

    public function getHeaderCssClass()
    {
        return 'icon-head head-backups-control';
    }
}