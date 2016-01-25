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
 * Class Mageplace_Gallery_Block_Adminhtml_Helper_Form_Config
 */
class Mageplace_Gallery_Block_Adminhtml_Helper_Form_Config
{
    protected $_element;

    public function __construct($element = null)
    {
        $this->_element = $element;
    }

    public function setElement($element)
    {
        $this->_element = $element;

        return $this;
    }

    public function getElement()
    {
        return $this->_element;
    }

    /**
     * @param Varien_Data_Form_Element_Abstract $element
     *
     * @return $this
     */
    public function processElement(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $element->setAfterElementHtml($this->toHtml());

        return $this;
    }

    /**
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     *
     * @return $this
     */
    public function processFieldsetElements(Varien_Data_Form_Element_Fieldset $fieldset)
    {
        foreach ($fieldset->getElements() as $element) {
            $element->setAfterElementHtml($this->toHtml($element));
        }

        return $this;
    }

    /**
     * @param Varien_Data_Form_Element_Abstract|null $element
     *
     * @return string
     */
    public function toHtml($element = null)
    {
        if (null === $element) {
            $element = $this->getElement();
        }

        $value = $element->getValue();
        if (null === $value) {
            $element->setValue($this->_getValueFromConfig());
        }

        $htmlId   = 'use_config_' . $element->getHtmlId();
        $checked  = (null === $value) ? ' checked="checked"' : '';
        $disabled = ($element->getReadonly()) ? ' disabled="disabled"' : '';

        $html = '<input id="' . $htmlId . '" name="use_config[' . $element->getHtmlId() . ']" ' . $disabled . ' value="1" ' . $checked;
        $html .= ' onclick="toggleValueElements(this, this.parentNode);" class="checkbox" type="checkbox" />';
        $html .= ' <label for="' . $htmlId . '">' . Mage::helper('adminhtml')->__('Use Config Settings') . '</label>';
        $html .= '<script type="text/javascript">toggleValueElements($(\'' . $htmlId . '\'), $(\'' . $htmlId . '\').parentNode);</script>';

        return $html;
    }

    protected function _getValueFromConfig()
    {
        return '';
    }
}
