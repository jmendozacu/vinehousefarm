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
 * Class Mageplace_Gallery_Block_Varien_Data_Form_Element_Image_Size
 */
class Mageplace_Gallery_Block_Varien_Data_Form_Element_Image_Size extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $width = $height = '';

        $value = $this->getValue();
        if (is_string($value) && strpos($value, Mageplace_Gallery_Helper_Const::WIDTH_HEIGHT_DELIMITER) > 0) {
            list($width, $height) = explode(Mageplace_Gallery_Helper_Const::WIDTH_HEIGHT_DELIMITER, $value);
        } elseif (is_array($value)) {
            list($width, $height) = $value;
        }

        $this->setType('text');
        $this->addClass('qty');

        $html = '<input type="text" name="' . $this->getName() . '[' . Mageplace_Gallery_Helper_Const::WIDTH . ']"
            value="' . $width . '" id="' . $this->getHtmlId() . '_width" ' . $this->serialize($this->getHtmlAttributes()) . '/>' . "\n";

        $html .= '&nbsp;x&nbsp;';

        $html .= '<input type="text" name="' . $this->getName() . '[' . Mageplace_Gallery_Helper_Const::HEIGHT . ']"
            value="' . $height . '" id="' . $this->getHtmlId() . '_height" ' . $this->serialize($this->getHtmlAttributes()) . '/>' . "\n";

        $html .= $this->getAfterElementHtml();

        return $html;
    }

    /* public function getName()
     {
         return $this->getData('name');
     }*/
}
