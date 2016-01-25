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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Meta
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Meta extends Mageplace_Gallery_Block_Adminhtml_Album_Tab_Abstract
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('album_meta_fieldset', array(
            'legend' => $this->__('Album Meta'),
            'class'  => 'fieldset-wide',
        ));

        $fieldset->addField('page_title',
            'text',
            array(
                'name'     => 'page_title',
                'label'    => $this->helper('cms')->__('Page Title'),
                'title'    => $this->helper('cms')->__('Page Title'),
            )
        );

        $fieldset->addField('meta_keywords',
            'textarea',
            array(
                'name'  => 'meta_keywords',
                'label' => $this->__('Meta Keywords'),
                'title' => $this->__('Meta Keywords'),
            )
        );

        $fieldset->addField('meta_description',
            'textarea',
            array(
                'name'  => 'meta_description',
                'label' => $this->__('Meta Description'),
                'title' => $this->__('Meta Description'),
            )
        );

        $form->addValues($this->getAlbum()->getData());

        $form->setFieldNameSuffix('general');
        $this->setForm($form);

        return parent::_prepareForm();
    }
}

