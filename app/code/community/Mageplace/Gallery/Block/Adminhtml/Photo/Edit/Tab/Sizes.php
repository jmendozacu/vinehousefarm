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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Sizes
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Sizes extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('photo');

        $isNew = !$model->getId();

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('sizes_fieldset', array(
            'legend' => $this->__('Sizes Settings'),
            'class'  => 'fieldset-wide',
        ));

        $parentSettings = $fieldset->addField('size_use_parent_settings',
            'select',
            array(
                'name'   => 'size_use_parent_settings',
                'label'  => $this->__('Use Parent Album Settings'),
                'title'  => $this->__('Use Parent Album Settings'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        if ($isNew) {
            $model->setData('size_use_parent_settings', 1);
        }

        $sizesFieldset = $form->addFieldset('photo_sizes_fieldset', array(
            'legend'                => $this->__('Sizes'),
            'name'                  => 'photo_sizes_fieldset',
            'fieldset_container_id' => 'photo_sizes_fieldset_container',
            'class'                 => 'fieldset-wide',
        ));
        $sizesFieldset->addType('image_size', Mage::getConfig()->getBlockClassName('mpgallery/varien_data_form_element_image_size'));

        $photoSize = $sizesFieldset->addField('photo_size',
            'image_size',
            array(
                'name'  => 'photo_size',
                'label' => $this->__('Photo Size (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $photoCarouselThumbSize = $sizesFieldset->addField('photo_carousel_thumb_size',
            'image_size',
            array(
                'name'  => 'photo_carousel_thumb_size',
                'label' => $this->__('Photo Thumbnail Size On List (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $form->addValues($model->getData());

        $this->setForm($form);

        Mage::getBlockSingleton('mpgallery/adminhtml_helper_form_config')
            ->processElement($photoSize)
            ->processElement($photoCarouselThumbSize);

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->addConfigOptions(array('levels_up' => 0))
                ->addFieldMap($parentSettings->getHtmlId(), $parentSettings->getName())
                ->addFieldMap($sizesFieldset->getHtmlId(), $sizesFieldset->getName())
                ->addFieldDependence(
                    $sizesFieldset->getName(),
                    $parentSettings->getName(),
                    0
                )
        );

        return parent::_prepareForm();
    }
}

