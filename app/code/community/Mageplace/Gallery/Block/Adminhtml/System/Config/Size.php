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
 * Class Mageplace_Gallery_Block_Adminhtml_System_Config_Size
 */
class Mageplace_Gallery_Block_Adminhtml_System_Config_Size extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $width = $height = '';
        if (($value = $element->getValue()) && strpos($value, Mageplace_Gallery_Model_System_Config_Backend_Size::DELIMITER) > 0) {
            list($width, $height) = explode(Mageplace_Gallery_Model_System_Config_Backend_Size::DELIMITER, $value);
        }

        $html = '<input type="text" name="' . $element->getName() . '[' . Mageplace_Gallery_Model_System_Config_Backend_Size::WIDTH . ']"
            value="' . $width . '" class="qty validate-number" id="' . $element->getHtmlId() . '_width"' . ($element->getDisabled() ? ' disabled="disabled"' : '') . '/>';

        $html .= '&nbsp;x&nbsp;';

        $html .= '<input type="text" name="' . $element->getName() . '[' . Mageplace_Gallery_Model_System_Config_Backend_Size::HEIGHT . ']"
            value="' . $height . '" class="qty validate-number" id="' . $element->getHtmlId() . '_height"' . ($element->getDisabled() ? ' disabled="disabled"' : '') . '/>';

        return $html;
    }
}
