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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Design
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Design extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('photo');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('design_fieldset', array(
            'legend' => Mage::helper('core')->__('Custom Design'),
            'class'  => 'fieldset-wide',
        ));

        $parentSettings = $fieldset->addField('design_use_parent_settings',
            'select',
            array(
                'name'   => 'design_use_parent_settings',
                'label'  => $this->__('Use Parent Album Settings'),
                'title'  => $this->__('Use Parent Album Settings'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetDesign = $form->addFieldset('design_photo_fieldset', array(
            'legend'                => $this->__('Photo Design Settings'),
            'name'                  => 'design_photo_fieldset',
            'fieldset_container_id' => 'design_photo_fieldset_container',
            'class'                 => 'fieldset-wide',
        ));

        $fieldsetDesign->addField('design_custom',
            'select',
            array(
                'name'   => 'design_custom',
                'label'  => Mage::helper('catalog')->__('Custom Design'),
                'title'  => Mage::helper('catalog')->__('Custom Design'),
                'values' => Mage::getSingleton('core/design_source_design')->getAllOptions()
            )
        );

        $fieldsetDesign->addField('page_layout',
            'select',
            array(
                'name'   => 'page_layout',
                'label'  => Mage::helper('catalog')->__('Page Layout'),
                'title'  => Mage::helper('catalog')->__('Page Layout'),
                'values' => Mage::getSingleton('mpgallery/source_pagelayout')->toOptionArray()
            )
        );

        $form->addValues($model->getData());

        $this->setForm($form);

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->addConfigOptions(array('levels_up' => 0))
                ->addFieldMap($parentSettings->getHtmlId(), $parentSettings->getName())
                ->addFieldMap($fieldsetDesign->getHtmlId(), $fieldsetDesign->getName())
                ->addFieldDependence(
                    $fieldsetDesign->getName(),
                    $parentSettings->getName(),
                    0
                )
        );

        return parent::_prepareForm();
    }
}

