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
 * Class Mageplace_Gallery_Block_Adminhtml_Review
 */
class Mageplace_Gallery_Block_Adminhtml_Review extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup     = 'mpgallery';
        $this->_controller     = 'adminhtml_review';
        $this->_headerText     = $this->__('Manage Reviews');
        $this->_addButtonLabel = $this->__('Add New Review');

        parent::__construct();
    }

    public function getHeaderCssClass()
    {
        return '';
    }
}
