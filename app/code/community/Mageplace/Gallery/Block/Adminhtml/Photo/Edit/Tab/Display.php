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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Display
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Display extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('photo');

        $isNew = !$model->getId();

        $form = new Varien_Data_Form();

        $fieldsetRenderer = Mage::getBlockSingleton('mpgallery/adminhtml_system_config_form_fieldset');
        $fieldsetRenderer->setForm($form);

        $fieldset = $form->addFieldset('display_fieldset', array(
            'legend' => $this->__('Display Settings'),
            'class'  => 'fieldset-wide',
        ));

        $parentSettings = $fieldset->addField('display_use_parent_settings',
            'select',
            array(
                'name'   => 'display_use_parent_settings',
                'label'  => $this->__('Use Parent Album Settings'),
                'title'  => $this->__('Use Parent Album Settings'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        if ($isNew) {
            $model->setData('display_use_parent_settings', 1);
        }

        /**
         * Photo View fieldset
         */

        $fieldsetPhotoView = $form->addFieldset('display_photo_view_fieldset', array(
            'legend'                => $this->__('Photo View Page Settings'),
            'name'                  => 'photo_view_fieldset',
            'fieldset_container_id' => 'display_photo_view_fieldset_container',
            'class'                 => 'fieldset-wide',
        ));
        $fieldsetPhotoView->setRenderer($fieldsetRenderer);

        $displayMode = $fieldsetPhotoView->addField('photo_view_display_mode',
            'select',
            array(
                'name'   => 'photo_view_display_mode',
                'label'  => $this->__('Display Mode'),
                'title'  => $this->__('Display Mode'),
                'values' => Mage::getSingleton('mpgallery/source_photodisplaymode')->toOptionArray()
            )
        );

        $displayName = $fieldsetPhotoView->addField('photo_view_display_name',
            'select',
            array(
                'name'   => 'photo_view_display_name',
                'label'  => $this->__('Display Name'),
                'title'  => $this->__('Display Name'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $displayReview = $fieldsetPhotoView->addField('photo_view_display_review',
            'select',
            array(
                'name'   => 'photo_view_display_review',
                'label'  => $this->__('Display Review and Submit Review Form'),
                'title'  => $this->__('Display Review and Submit Review Form'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $displayUpdateDate = $fieldsetPhotoView->addField('photo_view_display_update_date',
            'select',
            array(
                'name'   => 'photo_view_display_update_date',
                'label'  => $this->__('Display Update Date'),
                'title'  => $this->__('Display Update Date'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $displayShortDescription = $fieldsetPhotoView->addField('photo_view_display_short_descr',
            'select',
            array(
                'name'   => 'photo_view_display_short_descr',
                'label'  => $this->__('Display Short Description'),
                'title'  => $this->__('Display Short Description'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $displayDescription = $fieldsetPhotoView->addField('photo_view_display_descr',
            'select',
            array(
                'name'   => 'photo_view_display_descr',
                'label'  => $this->__('Display Description'),
                'title'  => $this->__('Display Description'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $displayBackLink = $fieldsetPhotoView->addField('photo_view_display_back_url',
            'select',
            array(
                'name'   => 'photo_view_display_back_url',
                'label'  => $this->__('Display Back To Album Link'),
                'title'  => $this->__('Display Back To Album Link'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        /**
         *  Photo listing fieldset
         */
        $fieldsetPhoto = $form->addFieldset('display_photo_fieldset', array(
            'legend'                => $this->__('Photo Listing Settings'),
            'name'                  => 'photo_fieldset',
            'fieldset_container_id' => 'display_photo_fieldset_container',
            'class'                 => 'fieldset-wide',
        ));
        $fieldsetPhoto->setRenderer($fieldsetRenderer);

        $sortBy = $fieldsetPhoto->addField('photo_view_list_sort_by',
            'select',
            array(
                'name'   => 'photo_view_list_sort_by',
                'label'  => $this->__('List Sort By'),
                'title'  => $this->__('List Sort By'),
                'values' => Mage::getSingleton('mpgallery/source_sortavailable')->toOptionArray(),
            )
        );

        $sortDir = $fieldsetPhoto->addField('photo_view_list_sort_dir',
            'select',
            array(
                'name'   => 'photo_view_list_sort_dir',
                'label'  => $this->__('List Sort Direction'),
                'title'  => $this->__('List Sort Direction'),
                'values' => Mage::getSingleton('mpgallery/source_sortdir')->toOptionArray()
            )
        );

        $perPage = $fieldsetPhoto->addField('photo_view_list_per_page',
            'text',
            array(
                'name'  => 'photo_view_list_per_page',
                'label' => $this->__('Photo Per Page On List'),
                'title' => $this->__('Photo Per Page On List'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
            )
        );

        $photoCarouselDisplayName = $fieldsetPhoto->addField('photo_carousel_display_name',
            'select',
            array(
                'name'   => 'photo_carousel_display_name',
                'label'  => $this->__('Display Name On List'),
                'title'  => $this->__('Display Name On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayRate = $fieldsetPhoto->addField('photo_carousel_display_rate',
            'select',
            array(
                'name'   => 'photo_carousel_display_rate',
                'label'  => $this->__('Display Rate On List'),
                'title'  => $this->__('Display Rate On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayUpdateDate = $fieldsetPhoto->addField('photo_carousel_display_update_date',
            'select',
            array(
                'name'   => 'photo_carousel_display_update_date',
                'label'  => $this->__('Display Update Date On List'),
                'title'  => $this->__('Display Update Date On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayShortDescription = $fieldsetPhoto->addField('photo_carousel_display_short_descr',
            'select',
            array(
                'name'   => 'photo_carousel_display_short_descr',
                'label'  => $this->__('Display Short Description On List'),
                'title'  => $this->__('Display Short Description On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayShowLink = $fieldsetPhoto->addField('photo_carousel_display_show_link',
            'select',
            array(
                'name'   => 'photo_carousel_display_show_link',
                'label'  => $this->__('Display Show Link On List'),
                'title'  => $this->__('Display Show Link On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $form->addValues($model->getData());

        $this->setForm($form);

        Mage::getBlockSingleton('mpgallery/adminhtml_helper_form_config')
            ->processFieldsetElements($fieldsetPhotoView)
            ->processFieldsetElements($fieldsetPhoto)
            ->processElement($displayMode);

        $listMode = array_map('strval', array(
            Mageplace_Gallery_Model_Photo::DISPLAY_MODE_PHOTO_LIST,
            Mageplace_Gallery_Model_Photo::DISPLAY_MODE_LIST_PHOTO
        ));

        $fieldsetDependence = $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
            ->addConfigOptions(array('levels_up' => 0))
            ->addFieldMap($parentSettings->getHtmlId(), $parentSettings->getName())
            ->addFieldMap($fieldsetPhotoView->getHtmlId(), $fieldsetPhotoView->getName())
            ->addFieldMap($fieldsetPhoto->getHtmlId(), $fieldsetPhoto->getName())
            ->addFieldMap($displayMode->getHtmlId(), $displayMode->getName())
            ->addFieldDependence(
                $fieldsetPhotoView->getName(),
                $parentSettings->getName(),
                0
            )
            ->addFieldDependence(
                $fieldsetPhoto->getName(),
                $parentSettings->getName(),
                0
            )
            ->addFieldDependence(
                $fieldsetPhoto->getName(),
                $displayMode->getName(),
                $listMode
            );

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->setAdditionalHtml($fieldsetDependence->toHtml())
                ->addFieldMap($parentSettings->getHtmlId(), $parentSettings->getName())
                ->addFieldMap($displayMode->getHtmlId(), $displayMode->getName())
                ->addFieldDependence(
                    $displayMode->getName(),
                    $parentSettings->getName(),
                    0
                )
        );

        return parent::_prepareForm();
    }
}

