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
 * Class Mageplace_Gallery_Block_Varien_Data_Form_Element_Imagepath
 */
class Mageplace_Gallery_Block_Varien_Data_Form_Element_Imagepath extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $radios = new Varien_Data_Form_Element_Radios(array(
            'id'      => $this->getHtmlId() . '_imageuploadtype',
            'name'    => $this->getHtmlId() . '_imageuploadtype',
            'values'  => Mage::getSingleton('mpgallery/source_imageuploadtype')->toOptionArray(),
            'value'   => 0,
            'onclick' => 'selectImageUploadType(this)'
        ));

        $html = $radios->setForm($this->getForm())->toHtml();

        $html .= '<script type="text/javascript">' . "\n";
        $html .= 'var selectImageUploadType = function() {' . "\n";
        $html .= '      $("' . $this->getHtmlId() . '_upload_area").toggleClassName("no-display");' . "\n";
        $html .= '      $("' . $this->getHtmlId() . '_enter_file_area").toggleClassName("no-display");' . "\n";
        $html .= '  if(this.value) {' . "\n";
        $html .= '  } else {' . "\n";
        $html .= '  }' . "\n";
        $html .= '}' . "\n";
        $html .= '$$(\'input[name="' . $this->getHtmlId() . '_imageuploadtype"]\').invoke("observe", "click", selectImageUploadType);' . "\n";
        $html .= '</script>' . "\n";


        if ((string)$this->getValue()) {
            $url = (string)$this->getValue();

            if (!preg_match("/^http\:\/\/|https\:\/\//", $url)) {
                $url = Mage::getBaseUrl('media') . $url;
            }

            $html .= '<a href="' . $url . '"'
                . ' onclick="imagePreview(\'' . $this->getHtmlId() . '_image\'); return false;">'
                . '<img src="' . $url . '" id="' . $this->getHtmlId() . '_image" title="' . $this->getValue() . '"'
                . ' alt="' . $this->getValue() . '" height="22" width="22" class="small-image-preview v-middle" />'
                . '</a> ' . "\n";
        }

        $this->setClass('input-file');

        $html .= '<span id="' . $this->getHtmlId() . '_upload_area" class="">' . "\n";
        $this->setType('file');
        $html .= '<input id="' . $this->getHtmlId() . '" name="' . $this->getName() . '" value="" ' . $this->serialize($this->getHtmlAttributes()) . '/>' . "\n";
        $html .= '</span>' . "\n";

        $html .= '<span id="' . $this->getHtmlId() . '_enter_file_area" class="no-display">' . "\n";
        $this->setType('text');
        $html .= '<input id="' . $this->getHtmlId() . '" name="' . $this->getName() . '[path]" value="" ' . $this->serialize($this->getHtmlAttributes()) . '/>' . "\n";
        $html .= '<input type="checkbox" name="' . $this->getName() . '[move]" value="1" class="checkbox" id="' . $this->getHtmlId() . '_move" checked="true"/>' . "\n";
        $html .= '<label for="' . $this->getHtmlId() . '_move"> ' . Mage::helper('mpgallery')->__('Move') . '</label>' . "\n";
        $html .= '</span>' . "\n";

        $html .= $this->getAfterElementHtml();

        if ($this->getValue()) {
            $label = Mage::helper('core')->__('Delete Image');
            $html .= '<span class="delete-image">' . "\n";
            $html .= '<input type="checkbox"'
                . ' name="' . $this->getName() . '[delete]" value="1" class="checkbox"'
                . ' id="' . $this->getHtmlId() . '_delete"' . ($this->getDisabled() ? ' disabled="disabled"' : '')
                . '/>' . "\n";
            $html .= '<label for="' . $this->getHtmlId() . '_delete"'
                . ($this->getDisabled() ? ' class="disabled"' : '') . '> ' . $label . '</label>';
            $html .= '<input type="hidden" name="' . $this->getName() . '[value]" value="' . $this->getValue() . '" />' . "\n";
            $html .= '</span>' . "\n";
        }

        return $html;
    }

    public function getName()
    {
        return $this->getData('name');
    }
}
