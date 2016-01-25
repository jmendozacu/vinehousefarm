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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Sizes
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Sizes extends Mageplace_Gallery_Block_Adminhtml_Album_Tab_Abstract
{
    protected function _prepareForm()
    {
        $isNew = !$this->getAlbum()->getId();

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('sizes_fieldset', array(
            'legend' => $this->__('Sizes Settings'),
            'class'  => 'fieldset-wide',
        ));

        $fieldsetRenderer = Mage::getBlockSingleton('mpgallery/adminhtml_system_config_form_fieldset');
        $fieldsetRenderer->setForm($form);

        $parentSettings = $fieldset->addField('size_use_parent_settings',
            'select',
            array(
                'name'   => 'size_use_parent_settings',
                'label'  => $this->__('Use Parent Album Settings'),
                'title'  => $this->__('Use Parent Album Settings'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
                'disabled' => $this->getAlbum()->getId() == Mageplace_Gallery_Model_Album::TREE_ROOT_ID,
            )
        );

        if ($isNew && $this->getAlbum()->getId() != Mageplace_Gallery_Model_Album::TREE_ROOT_ID) {
            $this->getAlbum()->setData('size_use_parent_settings', 1);
        }

        /**
         * Album sizes settings
         */
        $albumSizesFieldset = $form->addFieldset('album_sizes_fieldset', array(
            'legend'                => $this->__('Album Sizes'),
            'name'                  => 'album_sizes_fieldset',
            'fieldset_container_id' => 'album_sizes_fieldset_container',
            'class'                 => 'fieldset-wide',
        ));

        $albumSizesFieldset->setRenderer($fieldsetRenderer)
            ->addType('image_size', Mage::getConfig()->getBlockClassName('mpgallery/varien_data_form_element_image_size'));

        $albumSize = $albumSizesFieldset->addField('album_size',
            'image_size',
            array(
                'name'  => 'album_size',
                'label' => $this->__('Album Size On View Page (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $albumGridThumbSize = $albumSizesFieldset->addField('album_grid_thumb_size',
            'image_size',
            array(
                'name'  => 'album_grid_thumb_size',
                'label' => $this->__('Album Thumbnail Size On Grid (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $albumListThumbSize = $albumSizesFieldset->addField('album_list_thumb_size',
            'image_size',
            array(
                'name'  => 'album_list_thumb_size',
                'label' => $this->__('Album Thumbnail Size On List (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $albumSimpleThumbSize = $albumSizesFieldset->addField('album_simple_thumb_size',
            'image_size',
            array(
                'name'  => 'album_simple_thumb_size',
                'label' => $this->__('Album Thumbnail Size On Simple (WxH) px'),
                'class' => 'validate-number',
            )
        );

        /**
         * Photo sizes settings
         */
        $photoSizesFieldset = $form->addFieldset('photo_sizes_fieldset', array(
            'legend'                => $this->__('Photo Sizes'),
            'name'                  => 'photo_sizes_fieldset',
            'fieldset_container_id' => 'photo_sizes_fieldset_container',
            'class'                 => 'fieldset-wide',
        ));

        $photoSizesFieldset->setRenderer($fieldsetRenderer)
            ->addType('image_size', Mage::getConfig()->getBlockClassName('mpgallery/varien_data_form_element_image_size'));

        $photoSize = $photoSizesFieldset->addField('photo_size',
            'image_size',
            array(
                'name'  => 'photo_size',
                'label' => $this->__('Photo Size On View Page (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $photoGridThumbSize = $photoSizesFieldset->addField('photo_grid_thumb_size',
            'image_size',
            array(
                'name'  => 'photo_grid_thumb_size',
                'label' => $this->__('Photo Thumbnail Size On Grid (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $photoListThumbSize = $photoSizesFieldset->addField('photo_list_thumb_size',
            'image_size',
            array(
                'name'  => 'photo_list_thumb_size',
                'label' => $this->__('Photo Thumbnail Size On List (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $photoSimpleThumbSize = $photoSizesFieldset->addField('photo_simple_thumb_size',
            'image_size',
            array(
                'name'  => 'photo_simple_thumb_size',
                'label' => $this->__('Photo Thumbnail Size On Simple (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $photoCarouselThumbSize = $photoSizesFieldset->addField('photo_carousel_thumb_size',
            'image_size',
            array(
                'name'  => 'photo_carousel_thumb_size',
                'label' => $this->__('Photo Thumbnail Size On List of View Page (WxH) px'),
                'class' => 'validate-number',
            )
        );

        $form->addValues($this->getAlbum()->getData());
        $form->setFieldNameSuffix('sizes');

        $this->setForm($form);

        Mage::getBlockSingleton('mpgallery/adminhtml_helper_form_config')
            ->processFieldsetElements($albumSizesFieldset)
            ->processFieldsetElements($photoSizesFieldset);

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->addConfigOptions(array('levels_up' => 0))
                ->addFieldMap($parentSettings->getHtmlId(), $parentSettings->getName())
                ->addFieldMap($albumSizesFieldset->getHtmlId(), $albumSizesFieldset->getName())
                ->addFieldMap($photoSizesFieldset->getHtmlId(), $photoSizesFieldset->getName())
                ->addFieldDependence(
                    $albumSizesFieldset->getName(),
                    $parentSettings->getName(),
                    0
                )
                ->addFieldDependence(
                    $photoSizesFieldset->getName(),
                    $parentSettings->getName(),
                    0
                )
        );

        return parent::_prepareForm();
    }
}

