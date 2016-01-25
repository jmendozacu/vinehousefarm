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
 * Class Mageplace_Gallery_Block_Adminhtml_Form_Element_Image
 */
class Mageplace_Gallery_Block_Adminhtml_Form_Element_Image extends Varien_Data_Form_Element_Abstract
{

    /**
     * Enter description here...
     *
     * @param array $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('file');
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = '';

        if ($this->getValue()) {
            $url = $this->_getUrl();
            $html = '<a href="'.$url.'">'.($this->getTitle() ? $this->getTitle() : $this->getValue()).'</a> ';
        }
        $this->setClass('input-file');
        $html.= parent::getElementHtml();
        $html.= $this->_getDeleteCheckbox();

        return $html;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        if ($this->getValue()) {
            $html .= '<span class="delete-image">';
            $html .= '<input type="checkbox" name="'.parent::getName().'[delete]" value="1" class="checkbox" id="'.$this->getHtmlId().'_delete"'.($this->getDisabled() ? ' disabled="disabled"': '').'/>';
            $html .= '<label for="'.$this->getHtmlId().'_delete"'.($this->getDisabled() ? ' class="disabled"' : '').'> Delete File</label>';
            $html .= $this->_getHiddenInput();
            $html .= '</span>';
        }

        return $html;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    protected function _getHiddenInput()
    {
        return '<input type="hidden" name="'.parent::getName().'[value]" value="'.$this->getValue().'" />';
    }

    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        return $this->getValue();
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getName()
    {
        return  $this->getData('name');
    }

}
