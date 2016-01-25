<?php

/**
 * MagePlace Gallery Extension
 *
 * @category    Mageplace_Gallery
 * @package     Mageplace_Gallery
 * @copyright   Copyright (c) 2014 Mageplace. (http://www.mageplace.com)
 * @license     http://www.mageplace.com/disclaimer.html
 */
class Mageplace_Gallery_Block_Adminhtml_Photo extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup     = 'mpgallery';
        $this->_controller     = 'adminhtml_photo';
        $this->_headerText     = $this->__('Manage Photos');
        $this->_addButtonLabel = $this->__('Add New Photo');

        $this->_addButton('multiupload', array(
            'label'   => $this->__('Multiupload'),
            'onclick' => 'setLocation(\'' . $this->getUrl('*/gallery/multiupload') . '\')',
            'class'   => 'add',
        ));

        parent::__construct();
    }

    public function getHeaderCssClass()
    {
        return '';
    }
}
