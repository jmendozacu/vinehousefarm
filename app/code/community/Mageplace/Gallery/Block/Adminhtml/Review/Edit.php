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
 * Class Mageplace_Gallery_Block_Adminhtml_Review_Edit
 */
class Mageplace_Gallery_Block_Adminhtml_Review_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'review_id';
        $this->_blockGroup = 'mpgallery';
        $this->_controller = 'adminhtml_review';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save review'));
        $this->_updateButton('save', 'id', 'save_button');

        $this->_updateButton('delete', 'label', $this->__('Delete review'));
        $this->_updateButton('delete', 'id', 'delete_button');

        $this->_updateButton('reset', 'id', 'reset_button');

        $this->_addButton('saveandcontinue',
            array(
                'label'   => $this->helper('adminhtml')->__('Save and continue edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
                'id'      => 'saveandcontinue_button'
            ),
            -100
        );

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

        if (!Mage::registry('review')->getId()) {
            $this->_formScripts[] = "
                toggleVis('saveandcontinue_button');
                toggleVis('reset_button');
                toggleVis('save_button');
                toggleVis('edit_form');
            ";
        }
    }

    public function getHeaderText()
    {
        if (Mage::registry('review')->getSurveyId()) {
            return $this->__('Edit Review');
        } else {
            return $this->__('New Review');
        }
    }

    public function getHeaderCssClass()
    {
        return '';
    }
}
