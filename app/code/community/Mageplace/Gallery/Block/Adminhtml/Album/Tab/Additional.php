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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Additional
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Additional extends Mageplace_Gallery_Block_Adminhtml_Album_Tab_Abstract
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('additional_fieldset', array(
            'legend' => $this->__('Additional Settings'),
            'class'  => 'fieldset-wide',
        ));

        $form->addValues($this->getAlbum()->getData());

        $form->setFieldNameSuffix('additional');
        $this->setForm($form);

        return parent::_prepareForm();
    }
}

