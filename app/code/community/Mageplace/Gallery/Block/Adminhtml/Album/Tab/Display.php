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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Display
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Display extends Mageplace_Gallery_Block_Adminhtml_Album_Tab_Abstract
{
    protected function _prepareForm()
    {
        $isNew = !$this->getAlbum()->getId();

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
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
                'disabled' => $this->getAlbum()->getId() == Mageplace_Gallery_Model_Album::TREE_ROOT_ID,
            )
        );

        if ($isNew && $this->getAlbum()->getId() != Mageplace_Gallery_Model_Album::TREE_ROOT_ID) {
            $this->getAlbum()->setData('display_use_parent_settings', 1);
        }

        $displayMode = $fieldset->addField('display_mode',
            'select',
            array(
                'name'   => 'display_mode',
                'label'  => $this->__('Display Mode'),
                'title'  => $this->__('Display Mode'),
                'values' => Mage::getSingleton('mpgallery/source_displaymode')->toOptionArray()
            )
        );

        $displayOrderTitle = $this->__('Display Order');

        $displayOrderAll = $fieldset->addField('display_order',
            'select',
            array(
                'name'   => 'display_order',
                'label'  => $displayOrderTitle,
                'title'  => $displayOrderTitle,
                'values' => Mage::getSingleton('mpgallery/source_displayorder')->toOptionArray()
            )
        );

        $displayOrderExclBlock = $fieldset->addField('display_order_excl_block',
            'select',
            array(
                'name'   => 'display_order_excl_block',
                'label'  => $displayOrderTitle,
                'title'  => $displayOrderTitle,
                'values' => Mage::getSingleton('mpgallery/source_displayorder')->toOptionArrayExclBlock()
            )
        );
        $this->getAlbum()->setData('display_order_excl_block', $this->getAlbum()->getData('display_order'));

        $displayOrderExclAlbum = $fieldset->addField('display_order_excl_album',
            'select',
            array(
                'name'   => 'display_order_excl_album',
                'label'  => $displayOrderTitle,
                'title'  => $displayOrderTitle,
                'values' => Mage::getSingleton('mpgallery/source_displayorder')->toOptionArrayExclAlbum()
            )
        );
        $this->getAlbum()->setData('display_order_excl_album', $this->getAlbum()->getData('display_order'));

        $displayOrderExclPhoto = $fieldset->addField('display_order_excl_photo',
            'select',
            array(
                'name'   => 'display_order_excl_photo',
                'label'  => $displayOrderTitle,
                'title'  => $displayOrderTitle,
                'values' => Mage::getSingleton('mpgallery/source_displayorder')->toOptionArrayExclPhoto()
            )
        );
        $this->getAlbum()->setData('display_order_excl_photo', $this->getAlbum()->getData('display_order'));

        $cmsBlock = $fieldset->addField('cms_block',
            'select',
            array(
                'name'     => 'cms_block',
                'label'    => $this->__('CMS Block'),
                'title'    => $this->__('CMS Block'),
                'values'   => Mage::getSingleton('mpgallery/source_cmsblock')->toOptionArray(),
                'required' => true,
            )
        );

        /**
         * Album View fieldset
         */
        $fieldsetAlbumView = $form->addFieldset('display_album_view_fieldset', array(
            'legend'                => $this->__('Album View Page Settings'),
            'name'                  => 'album_view_fieldset',
            'fieldset_container_id' => 'display_album_view_fieldset_container',
            'class'                 => 'fieldset-wide',
        ));
        $fieldsetAlbumView->setRenderer($fieldsetRenderer);

        $fieldsetAlbumView->addField('album_view_display_name',
            'select',
            array(
                'name'   => 'album_view_display_name',
                'label'  => $this->__('Display Name'),
                'title'  => $this->__('Display Name'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetAlbumView->addField('album_view_display_image',
            'select',
            array(
                'name'   => 'album_view_display_image',
                'label'  => $this->__('Display Image'),
                'title'  => $this->__('Display Image'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetAlbumView->addField('album_view_display_update_date',
            'select',
            array(
                'name'   => 'album_view_display_update_date',
                'label'  => $this->__('Display Update Date'),
                'title'  => $this->__('Display Update Date'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetAlbumView->addField('album_view_display_short_descr',
            'select',
            array(
                'name'   => 'album_view_display_short_descr',
                'label'  => $this->__('Display Short Description'),
                'title'  => $this->__('Display Short Description'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetAlbumView->addField('album_view_display_descr',
            'select',
            array(
                'name'   => 'album_view_display_descr',
                'label'  => $this->__('Display Description'),
                'title'  => $this->__('Display Description'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        /**
         * Album listing fieldset
         */
        $fieldsetAlbum = $form->addFieldset('display_album_fieldset', array(
            'legend'                => $this->__('Album Listing Settings'),
            'name'                  => 'album_fieldset',
            'fieldset_container_id' => 'display_album_fieldset_container',
            'class'                 => 'fieldset-wide',
        ));
        $fieldsetAlbum->setRenderer($fieldsetRenderer);

        $albumDisplayToolbar = $fieldsetAlbum->addField('album_display_toolbar',
            'select',
            array(
                'name'   => 'album_display_toolbar',
                'label'  => $this->__('Display Toolbar'),
                'title'  => $this->__('Display Toolbar'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if (null !== $this->getAlbum()->getData('album_display_toolbar_top')
            || null !== $this->getAlbum()->getData('album_display_toolbar_bottom')
        ) {
            $this->getAlbum()->setData('album_display_toolbar', 1);
        }

        $albumDisplayToolbarTop = $fieldsetAlbum->addField('album_display_toolbar_top',
            'select',
            array(
                'name'   => 'album_display_toolbar_top',
                'label'  => $this->__('Display Top Toolbar'),
                'title'  => $this->__('Display Top Toolbar'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumDisplayToolbarBottom = $fieldsetAlbum->addField('album_display_toolbar_bottom',
            'select',
            array(
                'name'   => 'album_display_toolbar_bottom',
                'label'  => $this->__('Display Bottom Toolbar'),
                'title'  => $this->__('Display Bottom Toolbar'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $displayTypeAlbum = $fieldsetAlbum->addField('album_display_type',
            'select',
            array(
                'name'   => 'album_display_type',
                'label'  => $this->__('Display Type'),
                'title'  => $this->__('Display Type'),
                'values' => Mage::getSingleton('mpgallery/source_displaytype')->toOptionArray()
            )
        );

        $fieldsetAlbum->addField('album_available_sort_by',
            'multiselect',
            array(
                'name'   => 'album_available_sort_by',
                'label'  => $this->__('Available Sort By'),
                'title'  => $this->__('Available Sort By'),
                'values' => Mage::getSingleton('mpgallery/source_sortavailable')->toOptionArray(true),
            )
        );

        $fieldsetAlbum->addField('album_default_sort_by',
            'select',
            array(
                'name'   => 'album_default_sort_by',
                'label'  => $this->__('Default Sort By'),
                'title'  => $this->__('Default Sort By'),
                'values' => Mage::getSingleton('mpgallery/source_sortavailable')->toOptionArray(),
            )
        );

        $fieldsetAlbum->addField('album_default_sort_dir',
            'select',
            array(
                'name'   => 'album_default_sort_dir',
                'label'  => $this->__('Default Sort Direction'),
                'title'  => $this->__('Default Sort Direction'),
                'values' => Mage::getSingleton('mpgallery/source_sortdir')->toOptionArray()
            )
        );

        $albumColumnCountOnGrid = $fieldsetAlbum->addField('album_grid_column_count',
            'text',
            array(
                'name'  => 'album_grid_column_count',
                'label' => $this->__('Column Count On Grid'),
                'title' => $this->__('Column Count On Grid'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
            )
        );

        $albumColumnCountOnSimple = $fieldsetAlbum->addField('album_simple_column_count',
            'text',
            array(
                'name'  => 'album_simple_column_count',
                'label' => $this->__('Column Count On Simple'),
                'title' => $this->__('Column Count On Simple'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
            )
        );


        $albumDisplayName = $fieldsetAlbum->addField('album_display_name',
            'select',
            array(
                'name'   => 'album_display_name',
                'label'  => $this->__('Display Name'),
                'title'  => $this->__('Display Name'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('album_grid_display_name') !== null
            || $this->getAlbum()->getData('album_list_display_name') !== null
            || $this->getAlbum()->getData('album_simple_display_name') !== null
        ) {
            $this->getAlbum()->setData('album_display_name', 1);
        }

        $albumGridDisplayName = $fieldsetAlbum->addField('album_grid_display_name',
            'select',
            array(
                'name'   => 'album_grid_display_name',
                'label'  => $this->__('Display Name On Grid'),
                'title'  => $this->__('Display Name On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumListDisplayName = $fieldsetAlbum->addField('album_list_display_name',
            'select',
            array(
                'name'   => 'album_list_display_name',
                'label'  => $this->__('Display Name On List'),
                'title'  => $this->__('Display Name On LIst'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumSimpleDisplayName = $fieldsetAlbum->addField('album_simple_display_name',
            'select',
            array(
                'name'   => 'album_simple_display_name',
                'label'  => $this->__('Display Name On Simple'),
                'title'  => $this->__('Display Name On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $albumDisplayUpdateDate = $fieldsetAlbum->addField('album_display_update_date',
            'select',
            array(
                'name'   => 'album_display_update_date',
                'label'  => $this->__('Display Update Date'),
                'title'  => $this->__('Display Update Date'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('album_grid_display_update_date') !== null
            || $this->getAlbum()->getData('album_list_display_update_date') !== null
            || $this->getAlbum()->getData('album_simple_display_update_date') !== null
        ) {
            $this->getAlbum()->setData('album_display_update_date', 1);
        }

        $albumGridDisplayUpdateDate = $fieldsetAlbum->addField('album_grid_display_update_date',
            'select',
            array(
                'name'   => 'album_grid_display_update_date',
                'label'  => $this->__('Display Update Date On Grid'),
                'title'  => $this->__('Display Update Date On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumListDisplayUpdateDate = $fieldsetAlbum->addField('album_list_display_update_date',
            'select',
            array(
                'name'   => 'album_list_display_update_date',
                'label'  => $this->__('Display Update Date On List'),
                'title'  => $this->__('Display Update Date On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumSimpleDisplayUpdateDate = $fieldsetAlbum->addField('album_simple_display_update_date',
            'select',
            array(
                'name'   => 'album_simple_display_update_date',
                'label'  => $this->__('Display Update Date On Simple'),
                'title'  => $this->__('Display Update Date On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $albumDisplayShortDescription = $fieldsetAlbum->addField('album_display_short_descr',
            'select',
            array(
                'name'   => 'album_display_short_descr',
                'label'  => $this->__('Display Short Description'),
                'title'  => $this->__('Display Short Description'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('album_grid_display_short_descr') !== null
            || $this->getAlbum()->getData('album_list_display_short_descr') !== null
            || $this->getAlbum()->getData('album_simple_display_short_descr') !== null
        ) {
            $this->getAlbum()->setData('album_display_short_descr', 1);
        }

        $albumGridDisplayShortDescription = $fieldsetAlbum->addField('album_grid_display_short_descr',
            'select',
            array(
                'name'   => 'album_grid_display_short_descr',
                'label'  => $this->__('Display Short Description On Grid'),
                'title'  => $this->__('Display Short Description On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumListDisplayShortDescription = $fieldsetAlbum->addField('album_list_display_short_descr',
            'select',
            array(
                'name'   => 'album_list_display_short_descr',
                'label'  => $this->__('Display Short Description On List'),
                'title'  => $this->__('Display Short Description On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumSimpleDisplayShortDescription = $fieldsetAlbum->addField('album_simple_display_short_descr',
            'select',
            array(
                'name'   => 'album_simple_display_short_descr',
                'label'  => $this->__('Display Short Description On Simple'),
                'title'  => $this->__('Display Short Description On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $albumDisplayShowLink = $fieldsetAlbum->addField('album_display_show_link',
            'select',
            array(
                'name'   => 'album_display_show_link',
                'label'  => $this->__('Display Show Link'),
                'title'  => $this->__('Display Show Link'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('album_grid_display_show_link') !== null
            || $this->getAlbum()->getData('album_list_display_show_link') !== null
            || $this->getAlbum()->getData('album_simple_display_show_link') !== null
        ) {
            $this->getAlbum()->setData('album_display_show_link', 1);
        }

        $albumGridDisplayShowLink = $fieldsetAlbum->addField('album_grid_display_show_link',
            'select',
            array(
                'name'   => 'album_grid_display_show_link',
                'label'  => $this->__('Display Show Link On Grid'),
                'title'  => $this->__('Display Show Link On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumListDisplayShowLink = $fieldsetAlbum->addField('album_list_display_show_link',
            'select',
            array(
                'name'   => 'album_list_display_show_link',
                'label'  => $this->__('Display Show Link On List'),
                'title'  => $this->__('Display Show Link On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $albumSimpleDisplayShowLink = $fieldsetAlbum->addField('album_simple_display_show_link',
            'select',
            array(
                'name'   => 'album_simple_display_show_link',
                'label'  => $this->__('Display Show Link On Simple'),
                'title'  => $this->__('Display Show Link On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


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

        $fieldsetPhotoView->addField('photo_view_display_name',
            'select',
            array(
                'name'   => 'photo_view_display_name',
                'label'  => $this->__('Display Name'),
                'title'  => $this->__('Display Name'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetPhotoView->addField('photo_view_display_review',
            'select',
            array(
                'name'   => 'photo_view_display_review',
                'label'  => $this->__('Display Review and Submit Review Form'),
                'title'  => $this->__('Display Review and Submit Review Form'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetPhotoView->addField('photo_view_display_update_date',
            'select',
            array(
                'name'   => 'photo_view_display_update_date',
                'label'  => $this->__('Display Update Date'),
                'title'  => $this->__('Display Update Date'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetPhotoView->addField('photo_view_display_short_descr',
            'select',
            array(
                'name'   => 'photo_view_display_short_descr',
                'label'  => $this->__('Display Short Description'),
                'title'  => $this->__('Display Short Description'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetPhotoView->addField('photo_view_display_descr',
            'select',
            array(
                'name'   => 'photo_view_display_descr',
                'label'  => $this->__('Display Description'),
                'title'  => $this->__('Display Description'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $fieldsetPhotoView->addField('photo_view_display_back_url',
            'select',
            array(
                'name'   => 'photo_view_display_back_url',
                'label'  => $this->__('Display Back To Album Link'),
                'title'  => $this->__('Display Back To Album Link'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoViewDisplayMode = $fieldsetPhotoView->addField('photo_view_display_mode',
            'select',
            array(
                'name'   => 'photo_view_display_mode',
                'label'  => $this->__('Display Mode'),
                'title'  => $this->__('Display Mode'),
                'values' => Mage::getSingleton('mpgallery/source_photodisplaymode')->toOptionArray()
            )
        );

        $photoViewSortBy = $fieldsetPhotoView->addField('photo_view_list_sort_by',
            'select',
            array(
                'name'   => 'photo_view_list_sort_by',
                'label'  => $this->__('List Sort By'),
                'title'  => $this->__('List Sort By'),
                'values' => Mage::getSingleton('mpgallery/source_sortavailable')->toOptionArray(),
            )
        );

        $photoViewSortDir = $fieldsetPhotoView->addField('photo_view_list_sort_dir',
            'select',
            array(
                'name'   => 'photo_view_list_sort_dir',
                'label'  => $this->__('List Sort Direction'),
                'title'  => $this->__('List Sort Direction'),
                'values' => Mage::getSingleton('mpgallery/source_sortdir')->toOptionArray()
            )
        );

        $photoViewPerPage = $fieldsetPhotoView->addField('photo_view_list_per_page',
            'text',
            array(
                'name'  => 'photo_view_list_per_page',
                'label' => $this->__('Photo Per Page On List'),
                'title' => $this->__('Photo Per Page On List'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
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

        $photoDisplayToolbar = $fieldsetPhoto->addField('photo_display_toolbar',
            'select',
            array(
                'name'   => 'photo_display_toolbar',
                'label'  => $this->__('Display Toolbar'),
                'title'  => $this->__('Display Toolbar'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if (null !== $this->getAlbum()->getData('photo_display_toolbar_top')
            || null !== $this->getAlbum()->getData('photo_display_toolbar_bottom')
        ) {
            $this->getAlbum()->setData('photo_display_toolbar', 1);
        }

        $photoDisplayToolbarTop = $fieldsetPhoto->addField('photo_display_toolbar_top',
            'select',
            array(
                'name'   => 'photo_display_toolbar_top',
                'label'  => $this->__('Display Top Toolbar'),
                'title'  => $this->__('Display Top Toolbar'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoDisplayToolbarBottom = $fieldsetPhoto->addField('photo_display_toolbar_bottom',
            'select',
            array(
                'name'   => 'photo_display_toolbar_bottom',
                'label'  => $this->__('Display Bottom Toolbar'),
                'title'  => $this->__('Display Bottom Toolbar'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $displayTypePhoto = $fieldsetPhoto->addField('photo_display_type',
            'select',
            array(
                'name'   => 'photo_display_type',
                'label'  => $this->__('Display Type'),
                'title'  => $this->__('Display Type'),
                'values' => Mage::getSingleton('mpgallery/source_displaytype')->toOptionArray()
            )
        );

        $fieldsetPhoto->addField('photo_available_sort_by',
            'multiselect',
            array(
                'name'   => 'photo_available_sort_by',
                'label'  => $this->__('Available Sort By'),
                'title'  => $this->__('Available Sort By'),
                'values' => Mage::getSingleton('mpgallery/source_sortavailable')->toOptionArray(true),
            )
        );

        $fieldsetPhoto->addField('photo_default_sort_by',
            'select',
            array(
                'name'   => 'photo_default_sort_by',
                'label'  => $this->__('Default Sort By'),
                'title'  => $this->__('Default Sort By'),
                'values' => Mage::getSingleton('mpgallery/source_sortavailable')->toOptionArray(),
            )
        );

        $fieldsetPhoto->addField('photo_default_sort_dir',
            'select',
            array(
                'name'   => 'photo_default_sort_dir',
                'label'  => $this->__('Default Sort Direction'),
                'title'  => $this->__('Default Sort Direction'),
                'values' => Mage::getSingleton('mpgallery/source_sortdir')->toOptionArray()
            )
        );

        $photoGridColumnCount = $fieldsetPhoto->addField('photo_grid_column_count',
            'text',
            array(
                'name'  => 'photo_grid_column_count',
                'label' => $this->__('Column Count On Grid'),
                'title' => $this->__('Column Count On Grid'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
            )
        );

        $photoGridPerPage = $fieldsetPhoto->addField('photo_grid_per_page',
            'text',
            array(
                'name'  => 'photo_grid_per_page',
                'label' => $this->__('Photo Per Page On Grid'),
                'title' => $this->__('Photo Per Page On Grid'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
            )
        );

        $photoGridPagerLimit = $fieldsetPhoto->addField('photo_grid_pager_limit',
            'text',
            array(
                'name'  => 'photo_grid_pager_limit',
                'label' => $this->__('Allowed Photos Per Page On Grid'),
                'title' => $this->__('Allowed Photos Per Page On Grid'),
                'note'  => $this->__('For example: 9,15,30,All')
            )
        );

        $photoListPerPage = $fieldsetPhoto->addField('photo_list_per_page',
            'text',
            array(
                'name'  => 'photo_list_per_page',
                'label' => $this->__('Photo Per Page On List'),
                'title' => $this->__('Photo Per Page On List'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
            )
        );

        $photoListPagerLimit = $fieldsetPhoto->addField('photo_list_pager_limit',
            'text',
            array(
                'name'  => 'photo_list_pager_limit',
                'label' => $this->__('Allowed Photos Per Page On List'),
                'title' => $this->__('Allowed Photos Per Page On List'),
                'note'  => $this->__('For example: 9,15,30,All')
            )
        );

        $photoSimpleColumnCount = $fieldsetPhoto->addField('photo_simple_column_count',
            'text',
            array(
                'name'  => 'photo_simple_column_count',
                'label' => $this->__('Column Count On Simple'),
                'title' => $this->__('Column Count On Simple'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
            )
        );

        $photoSimplePerPage = $fieldsetPhoto->addField('photo_simple_per_page',
            'text',
            array(
                'name'  => 'photo_simple_per_page',
                'label' => $this->__('Photo Per Page On Simple'),
                'title' => $this->__('Photo Per Page On Simple'),
                'class' => 'validate-number',
                'style' => 'width: 50px!important'
            )
        );

        $photoSimplePagerLimit = $fieldsetPhoto->addField('photo_simple_pager_limit',
            'text',
            array(
                'name'  => 'photo_simple_pager_limit',
                'label' => $this->__('Allowed Photos Per Page On Simple'),
                'title' => $this->__('Allowed Photos Per Page On Simple'),
                'note'  => $this->__('For example: 9,15,30,All')
            )
        );


        $photoDisplayName = $fieldsetPhoto->addField('photo_display_name',
            'select',
            array(
                'name'   => 'photo_display_name',
                'label'  => $this->__('Display Name'),
                'title'  => $this->__('Display Name'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('photo_grid_display_name') !== null
            || $this->getAlbum()->getData('photo_list_display_name') !== null
            || $this->getAlbum()->getData('photo_simple_display_name') !== null
            || $this->getAlbum()->getData('photo_carousel_display_name') !== null
        ) {
            $this->getAlbum()->setData('photo_display_name', 1);
        }

        $photoGridDisplayName = $fieldsetPhoto->addField('photo_grid_display_name',
            'select',
            array(
                'name'   => 'photo_grid_display_name',
                'label'  => $this->__('Display Name On Grid'),
                'title'  => $this->__('Display Name On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoListDisplayName = $fieldsetPhoto->addField('photo_list_display_name',
            'select',
            array(
                'name'   => 'photo_list_display_name',
                'label'  => $this->__('Display Name On List'),
                'title'  => $this->__('Display Name On LIst'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoSimpleDisplayName = $fieldsetPhoto->addField('photo_simple_display_name',
            'select',
            array(
                'name'   => 'photo_simple_display_name',
                'label'  => $this->__('Display Name On Simple'),
                'title'  => $this->__('Display Name On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayName = $fieldsetPhoto->addField('photo_carousel_display_name',
            'select',
            array(
                'name'   => 'photo_carousel_display_name',
                'label'  => $this->__('Display Name On List of View Page'),
                'title'  => $this->__('Display Name On List of View Page'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $photoDisplayRate = $fieldsetPhoto->addField('photo_display_rate',
            'select',
            array(
                'name'   => 'photo_display_rate',
                'label'  => $this->__('Display Rate'),
                'title'  => $this->__('Display Rate'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('photo_grid_display_rate') !== null
            || $this->getAlbum()->getData('photo_list_display_rate') !== null
            || $this->getAlbum()->getData('photo_simple_display_rate') !== null
            || $this->getAlbum()->getData('photo_carousel_display_rate') !== null
        ) {
            $this->getAlbum()->setData('photo_display_rate', 1);
        }

        $photoGridDisplayRate = $fieldsetPhoto->addField('photo_grid_display_rate',
            'select',
            array(
                'name'   => 'photo_grid_display_rate',
                'label'  => $this->__('Display Rate On Grid'),
                'title'  => $this->__('Display Rate On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoListDisplayRate = $fieldsetPhoto->addField('photo_list_display_rate',
            'select',
            array(
                'name'   => 'photo_list_display_rate',
                'label'  => $this->__('Display Rate On List'),
                'title'  => $this->__('Display Rate On LIst'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoSimpleDisplayRate = $fieldsetPhoto->addField('photo_simple_display_rate',
            'select',
            array(
                'name'   => 'photo_simple_display_rate',
                'label'  => $this->__('Display Rate On Simple'),
                'title'  => $this->__('Display Rate On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayRate = $fieldsetPhoto->addField('photo_carousel_display_rate',
            'select',
            array(
                'name'   => 'photo_carousel_display_rate',
                'label'  => $this->__('Display Rate On List of View Page'),
                'title'  => $this->__('Display Rate On List of View Page'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $photoDisplayUpdateDate = $fieldsetPhoto->addField('photo_display_update_date',
            'select',
            array(
                'name'   => 'photo_display_update_date',
                'label'  => $this->__('Display Update Date'),
                'title'  => $this->__('Display Update Date'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('photo_grid_display_update_date') !== null
            || $this->getAlbum()->getData('photo_list_display_update_date') !== null
            || $this->getAlbum()->getData('photo_simple_display_update_date') !== null
            || $this->getAlbum()->getData('photo_carousel_display_update_date') !== null
        ) {
            $this->getAlbum()->setData('photo_display_update_date', 1);
        }

        $photoGridDisplayUpdateDate = $fieldsetPhoto->addField('photo_grid_display_update_date',
            'select',
            array(
                'name'   => 'photo_grid_display_update_date',
                'label'  => $this->__('Display Update Date On Grid'),
                'title'  => $this->__('Display Update Date On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoListDisplayUpdateDate = $fieldsetPhoto->addField('photo_list_display_update_date',
            'select',
            array(
                'name'   => 'photo_list_display_update_date',
                'label'  => $this->__('Display Update Date On List'),
                'title'  => $this->__('Display Update Date On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoSimpleDisplayUpdateDate = $fieldsetPhoto->addField('photo_simple_display_update_date',
            'select',
            array(
                'name'   => 'photo_simple_display_update_date',
                'label'  => $this->__('Display Update Date On Simple'),
                'title'  => $this->__('Display Update Date On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayUpdateDate = $fieldsetPhoto->addField('photo_carousel_display_update_date',
            'select',
            array(
                'name'   => 'photo_carousel_display_update_date',
                'label'  => $this->__('Display Update Date On List of View Page'),
                'title'  => $this->__('Display Update Date On List of View Page'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $photoDisplayShortDescription = $fieldsetPhoto->addField('photo_display_short_descr',
            'select',
            array(
                'name'   => 'photo_display_short_descr',
                'label'  => $this->__('Display Short Description'),
                'title'  => $this->__('Display Short Description'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('photo_grid_display_short_descr') !== null
            || $this->getAlbum()->getData('photo_list_display_short_descr') !== null
            || $this->getAlbum()->getData('photo_simple_display_short_descr') !== null
            || $this->getAlbum()->getData('photo_carousel_display_show_link') !== null
        ) {
            $this->getAlbum()->setData('photo_display_short_descr', 1);
        }

        $photoGridDisplayShortDescription = $fieldsetPhoto->addField('photo_grid_display_short_descr',
            'select',
            array(
                'name'   => 'photo_grid_display_short_descr',
                'label'  => $this->__('Display Short Description On Grid'),
                'title'  => $this->__('Display Short Description On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoListDisplayShortDescription = $fieldsetPhoto->addField('photo_list_display_short_descr',
            'select',
            array(
                'name'   => 'photo_list_display_short_descr',
                'label'  => $this->__('Display Short Description On List'),
                'title'  => $this->__('Display Short Description On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoSimpleDisplayShortDescription = $fieldsetPhoto->addField('photo_simple_display_short_descr',
            'select',
            array(
                'name'   => 'photo_simple_display_short_descr',
                'label'  => $this->__('Display Short Description On Simple'),
                'title'  => $this->__('Display Short Description On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayShortDescription = $fieldsetPhoto->addField('photo_carousel_display_short_descr',
            'select',
            array(
                'name'   => 'photo_carousel_display_short_descr',
                'label'  => $this->__('Display Short Description On List of View Page'),
                'title'  => $this->__('Display Short Description On List of View Page'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $photoDisplayShowLink = $fieldsetPhoto->addField('photo_display_show_link',
            'select',
            array(
                'name'   => 'photo_display_show_link',
                'label'  => $this->__('Display Show Link'),
                'title'  => $this->__('Display Show Link'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );
        if ($this->getAlbum()->getData('photo_grid_display_show_link') !== null
            || $this->getAlbum()->getData('photo_list_display_show_link') !== null
            || $this->getAlbum()->getData('photo_simple_display_show_link') !== null
            || $this->getAlbum()->getData('photo_carousel_display_show_link') !== null
        ) {
            $this->getAlbum()->setData('photo_display_show_link', 1);
        }

        $photoGridDisplayShowLink = $fieldsetPhoto->addField('photo_grid_display_show_link',
            'select',
            array(
                'name'   => 'photo_grid_display_show_link',
                'label'  => $this->__('Display Show Link On Grid'),
                'title'  => $this->__('Display Show Link On Grid'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoListDisplayShowLink = $fieldsetPhoto->addField('photo_list_display_show_link',
            'select',
            array(
                'name'   => 'photo_list_display_show_link',
                'label'  => $this->__('Display Show Link On List'),
                'title'  => $this->__('Display Show Link On List'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoSimpleDisplayShowLink = $fieldsetPhoto->addField('photo_simple_display_show_link',
            'select',
            array(
                'name'   => 'photo_simple_display_show_link',
                'label'  => $this->__('Display Show Link On Simple'),
                'title'  => $this->__('Display Show Link On Simple'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $photoCarouselDisplayShowLink = $fieldsetPhoto->addField('photo_carousel_display_show_link',
            'select',
            array(
                'name'   => 'photo_carousel_display_show_link',
                'label'  => $this->__('Display Show Link On List of View Page'),
                'title'  => $this->__('Display Show Link On List of View Page'),
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );


        $form->addValues($this->getAlbum()->getData());

        $form->setFieldNameSuffix('display');
        $this->setForm($form);

        Mage::getBlockSingleton('mpgallery/adminhtml_helper_form_config')
            ->processFieldsetElements($fieldsetAlbumView)
            ->processFieldsetElements($fieldsetAlbum)
            ->processFieldsetElements($fieldsetPhotoView)
            ->processFieldsetElements($fieldsetPhoto);

        $fieldsetDependence = $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
            ->addConfigOptions(array('levels_up' => 0))
            ->addFieldMap($parentSettings->getHtmlId(), $parentSettings->getName())
            ->addFieldMap($fieldsetAlbumView->getHtmlId(), $fieldsetAlbumView->getName())
            ->addFieldMap($fieldsetAlbum->getHtmlId(), $fieldsetAlbum->getName())
            ->addFieldMap($fieldsetPhotoView->getHtmlId(), $fieldsetPhotoView->getName())
            ->addFieldMap($fieldsetPhoto->getHtmlId(), $fieldsetPhoto->getName())
            ->addFieldMap($displayMode->getHtmlId(), $displayMode->getName())
            ->addFieldDependence(
                $fieldsetAlbumView->getName(),
                $parentSettings->getName(),
                0
            )
            ->addFieldDependence(
                $fieldsetAlbum->getName(),
                $parentSettings->getName(),
                0
            )
            ->addFieldDependence(
                $fieldsetAlbum->getName(),
                $displayMode->getName(),
                array_map('strval', array(
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_ALBUM_AND_PHOTO,
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO,
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM,
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_ALBUM_ONLY
                ))
            )
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
                array_map('strval', array(
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_ALBUM_AND_PHOTO,
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO,
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_PHOTO,
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_PHOTO_ONLY
                ))
            );

        $gridDisplayType   = array_map('strval', Mageplace_Gallery_Helper_Const::$DISPLAY_TYPES_BY_MODE[Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_GRID]);
        $listDisplayType   = array_map('strval', Mageplace_Gallery_Helper_Const::$DISPLAY_TYPES_BY_MODE[Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_LIST]);
        $simpleDisplayType = array_map('strval', Mageplace_Gallery_Helper_Const::$DISPLAY_TYPES_BY_MODE[Mageplace_Gallery_Helper_Const::DISPLAY_TYPE_SIMPLE]);
        $photoViewListMode = array_map('strval', array(
            Mageplace_Gallery_Model_Photo::DISPLAY_MODE_PHOTO_LIST,
            Mageplace_Gallery_Model_Photo::DISPLAY_MODE_LIST_PHOTO
        ));

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->setAdditionalHtml($fieldsetDependence->toHtml())
                ->addFieldMap($parentSettings->getHtmlId(), $parentSettings->getName())
                ->addFieldMap($displayMode->getHtmlId(), $displayMode->getName())
                ->addFieldMap($displayOrderAll->getHtmlId(), $displayOrderAll->getName())
                ->addFieldMap($displayOrderExclBlock->getHtmlId(), $displayOrderExclBlock->getName())
                ->addFieldMap($displayOrderExclAlbum->getHtmlId(), $displayOrderExclAlbum->getName())
                ->addFieldMap($displayOrderExclPhoto->getHtmlId(), $displayOrderExclPhoto->getName())
                ->addFieldMap($cmsBlock->getHtmlId(), $cmsBlock->getName())
                ->addFieldMap($displayTypeAlbum->getHtmlId(), $displayTypeAlbum->getName())
                ->addFieldMap($albumDisplayToolbar->getHtmlId(), $albumDisplayToolbar->getName())
                ->addFieldMap($albumDisplayToolbarTop->getHtmlId(), $albumDisplayToolbarTop->getName())
                ->addFieldMap($albumDisplayToolbarBottom->getHtmlId(), $albumDisplayToolbarBottom->getName())
                ->addFieldMap($albumColumnCountOnGrid->getHtmlId(), $albumColumnCountOnGrid->getName())
                ->addFieldMap($albumColumnCountOnSimple->getHtmlId(), $albumColumnCountOnSimple->getName())
                ->addFieldMap($albumDisplayName->getHtmlId(), $albumDisplayName->getName())
                ->addFieldMap($albumGridDisplayName->getHtmlId(), $albumGridDisplayName->getName())
                ->addFieldMap($albumListDisplayName->getHtmlId(), $albumListDisplayName->getName())
                ->addFieldMap($albumSimpleDisplayName->getHtmlId(), $albumSimpleDisplayName->getName())
                ->addFieldMap($albumDisplayUpdateDate->getHtmlId(), $albumDisplayUpdateDate->getName())
                ->addFieldMap($albumGridDisplayUpdateDate->getHtmlId(), $albumGridDisplayUpdateDate->getName())
                ->addFieldMap($albumListDisplayUpdateDate->getHtmlId(), $albumListDisplayUpdateDate->getName())
                ->addFieldMap($albumSimpleDisplayUpdateDate->getHtmlId(), $albumSimpleDisplayUpdateDate->getName())
                ->addFieldMap($albumDisplayShortDescription->getHtmlId(), $albumDisplayShortDescription->getName())
                ->addFieldMap($albumGridDisplayShortDescription->getHtmlId(), $albumGridDisplayShortDescription->getName())
                ->addFieldMap($albumListDisplayShortDescription->getHtmlId(), $albumListDisplayShortDescription->getName())
                ->addFieldMap($albumSimpleDisplayShortDescription->getHtmlId(), $albumSimpleDisplayShortDescription->getName())
                ->addFieldMap($albumDisplayShowLink->getHtmlId(), $albumDisplayShowLink->getName())
                ->addFieldMap($albumGridDisplayShowLink->getHtmlId(), $albumGridDisplayShowLink->getName())
                ->addFieldMap($albumListDisplayShowLink->getHtmlId(), $albumListDisplayShowLink->getName())
                ->addFieldMap($albumSimpleDisplayShowLink->getHtmlId(), $albumSimpleDisplayShowLink->getName())
                ->addFieldMap($photoDisplayToolbar->getHtmlId(), $photoDisplayToolbar->getName())
                ->addFieldMap($photoDisplayToolbarTop->getHtmlId(), $photoDisplayToolbarTop->getName())
                ->addFieldMap($photoDisplayToolbarBottom->getHtmlId(), $photoDisplayToolbarBottom->getName())
                ->addFieldMap($photoDisplayName->getHtmlId(), $photoDisplayName->getName())
                ->addFieldMap($photoGridDisplayName->getHtmlId(), $photoGridDisplayName->getName())
                ->addFieldMap($photoListDisplayName->getHtmlId(), $photoListDisplayName->getName())
                ->addFieldMap($photoSimpleDisplayName->getHtmlId(), $photoSimpleDisplayName->getName())
                ->addFieldMap($photoCarouselDisplayName->getHtmlId(), $photoCarouselDisplayName->getName())
                ->addFieldMap($photoDisplayUpdateDate->getHtmlId(), $photoDisplayUpdateDate->getName())
                ->addFieldMap($photoGridDisplayUpdateDate->getHtmlId(), $photoGridDisplayUpdateDate->getName())
                ->addFieldMap($photoListDisplayUpdateDate->getHtmlId(), $photoListDisplayUpdateDate->getName())
                ->addFieldMap($photoSimpleDisplayUpdateDate->getHtmlId(), $photoSimpleDisplayUpdateDate->getName())
                ->addFieldMap($photoCarouselDisplayUpdateDate->getHtmlId(), $photoCarouselDisplayUpdateDate->getName())
                ->addFieldMap($photoDisplayShortDescription->getHtmlId(), $photoDisplayShortDescription->getName())
                ->addFieldMap($photoGridDisplayShortDescription->getHtmlId(), $photoGridDisplayShortDescription->getName())
                ->addFieldMap($photoListDisplayShortDescription->getHtmlId(), $photoListDisplayShortDescription->getName())
                ->addFieldMap($photoSimpleDisplayShortDescription->getHtmlId(), $photoSimpleDisplayShortDescription->getName())
                ->addFieldMap($photoCarouselDisplayShortDescription->getHtmlId(), $photoCarouselDisplayShortDescription->getName())
                ->addFieldMap($photoDisplayShowLink->getHtmlId(), $photoDisplayShowLink->getName())
                ->addFieldMap($photoGridDisplayShowLink->getHtmlId(), $photoGridDisplayShowLink->getName())
                ->addFieldMap($photoListDisplayShowLink->getHtmlId(), $photoListDisplayShowLink->getName())
                ->addFieldMap($photoSimpleDisplayShowLink->getHtmlId(), $photoSimpleDisplayShowLink->getName())
                ->addFieldMap($photoCarouselDisplayShowLink->getHtmlId(), $photoCarouselDisplayShowLink->getName())
                ->addFieldMap($displayTypePhoto->getHtmlId(), $displayTypePhoto->getName())
                ->addFieldMap($photoGridColumnCount->getHtmlId(), $photoGridColumnCount->getName())
                ->addFieldMap($photoGridPerPage->getHtmlId(), $photoGridPerPage->getName())
                ->addFieldMap($photoGridPagerLimit->getHtmlId(), $photoGridPagerLimit->getName())
                ->addFieldMap($photoListPerPage->getHtmlId(), $photoListPerPage->getName())
                ->addFieldMap($photoListPagerLimit->getHtmlId(), $photoListPagerLimit->getName())
                ->addFieldMap($photoSimpleColumnCount->getHtmlId(), $photoSimpleColumnCount->getName())
                ->addFieldMap($photoSimplePerPage->getHtmlId(), $photoSimplePerPage->getName())
                ->addFieldMap($photoSimplePagerLimit->getHtmlId(), $photoSimplePagerLimit->getName())
                ->addFieldMap($photoViewDisplayMode->getHtmlId(), $photoViewDisplayMode->getName())
                ->addFieldMap($photoViewSortBy->getHtmlId(), $photoViewSortBy->getName())
                ->addFieldMap($photoViewSortDir->getHtmlId(), $photoViewSortDir->getName())
                ->addFieldMap($photoViewPerPage->getHtmlId(), $photoViewPerPage->getName())
                ->addFieldMap($photoDisplayRate->getHtmlId(), $photoDisplayRate->getName())
                ->addFieldMap($photoGridDisplayRate->getHtmlId(), $photoGridDisplayRate->getName())
                ->addFieldMap($photoListDisplayRate->getHtmlId(), $photoListDisplayRate->getName())
                ->addFieldMap($photoSimpleDisplayRate->getHtmlId(), $photoSimpleDisplayRate->getName())
                ->addFieldMap($photoCarouselDisplayRate->getHtmlId(), $photoCarouselDisplayRate->getName())
                ->addFieldDependence(
                    $displayMode->getName(),
                    $parentSettings->getName(),
                    0
                )
                ->addFieldDependence(
                    $displayOrderAll->getName(),
                    $parentSettings->getName(),
                    0
                )
                ->addFieldDependence(
                    $displayOrderAll->getName(),
                    $displayMode->getName(),
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO
                )
                ->addFieldDependence(
                    $displayOrderExclBlock->getName(),
                    $parentSettings->getName(),
                    0
                )
                ->addFieldDependence(
                    $displayOrderExclBlock->getName(),
                    $displayMode->getName(),
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_ALBUM_AND_PHOTO
                )
                ->addFieldDependence(
                    $displayOrderExclAlbum->getName(),
                    $parentSettings->getName(),
                    0
                )
                ->addFieldDependence(
                    $displayOrderExclAlbum->getName(),
                    $displayMode->getName(),
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_PHOTO
                )
                ->addFieldDependence(
                    $displayOrderExclPhoto->getName(),
                    $parentSettings->getName(),
                    0
                )
                ->addFieldDependence(
                    $displayOrderExclPhoto->getName(),
                    $displayMode->getName(),
                    Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM
                )
                ->addFieldDependence(
                    $cmsBlock->getName(),
                    $parentSettings->getName(),
                    0
                )
                ->addFieldDependence(
                    $cmsBlock->getName(),
                    $displayMode->getName(),
                    array_map('strval', array(
                        Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM_AND_PHOTO,
                        Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_ALBUM,
                        Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_AND_PHOTO,
                        Mageplace_Gallery_Model_Album::DISPLAY_MODE_BLOCK_ONLY
                    ))
                )
                ->addFieldDependence(
                    $albumDisplayToolbarTop->getName(),
                    $albumDisplayToolbar->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumDisplayToolbarBottom->getName(),
                    $albumDisplayToolbar->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumColumnCountOnGrid->getName(),
                    $displayTypeAlbum->getName(),
                    $gridDisplayType
                )
                ->addFieldDependence(
                    $albumColumnCountOnSimple->getName(),
                    $displayTypeAlbum->getName(),
                    $simpleDisplayType
                )
                ->addFieldDependence(
                    $albumGridDisplayName->getName(),
                    $albumDisplayName->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumListDisplayName->getName(),
                    $albumDisplayName->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumSimpleDisplayName->getName(),
                    $albumDisplayName->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumGridDisplayUpdateDate->getName(),
                    $albumDisplayUpdateDate->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumListDisplayUpdateDate->getName(),
                    $albumDisplayUpdateDate->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumSimpleDisplayUpdateDate->getName(),
                    $albumDisplayUpdateDate->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumGridDisplayShortDescription->getName(),
                    $albumDisplayShortDescription->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumListDisplayShortDescription->getName(),
                    $albumDisplayShortDescription->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumSimpleDisplayShortDescription->getName(),
                    $albumDisplayShortDescription->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumGridDisplayShowLink->getName(),
                    $albumDisplayShowLink->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumListDisplayShowLink->getName(),
                    $albumDisplayShowLink->getName(),
                    1
                )
                ->addFieldDependence(
                    $albumSimpleDisplayShowLink->getName(),
                    $albumDisplayShowLink->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoDisplayToolbarTop->getName(),
                    $photoDisplayToolbar->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoDisplayToolbarBottom->getName(),
                    $photoDisplayToolbar->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoGridColumnCount->getName(),
                    $displayTypePhoto->getName(),
                    $gridDisplayType
                )
                ->addFieldDependence(
                    $photoGridPerPage->getName(),
                    $displayTypePhoto->getName(),
                    $gridDisplayType
                )
                ->addFieldDependence(
                    $photoGridPagerLimit->getName(),
                    $displayTypePhoto->getName(),
                    $gridDisplayType
                )
                ->addFieldDependence(
                    $photoListPerPage->getName(),
                    $displayTypePhoto->getName(),
                    $listDisplayType
                )
                ->addFieldDependence(
                    $photoListPagerLimit->getName(),
                    $displayTypePhoto->getName(),
                    $listDisplayType
                )
                ->addFieldDependence(
                    $photoSimpleColumnCount->getName(),
                    $displayTypePhoto->getName(),
                    $simpleDisplayType
                )
                ->addFieldDependence(
                    $photoSimplePerPage->getName(),
                    $displayTypePhoto->getName(),
                    $simpleDisplayType
                )
                ->addFieldDependence(
                    $photoSimplePagerLimit->getName(),
                    $displayTypePhoto->getName(),
                    $simpleDisplayType
                )
                ->addFieldDependence(
                    $photoViewSortBy->getName(),
                    $photoViewDisplayMode->getName(),
                    $photoViewListMode
                )
                ->addFieldDependence(
                    $photoViewSortDir->getName(),
                    $photoViewDisplayMode->getName(),
                    $photoViewListMode
                )
                ->addFieldDependence(
                    $photoViewPerPage->getName(),
                    $photoViewDisplayMode->getName(),
                    $photoViewListMode
                )
                ->addFieldDependence(
                    $photoGridDisplayName->getName(),
                    $photoDisplayName->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoListDisplayName->getName(),
                    $photoDisplayName->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoSimpleDisplayName->getName(),
                    $photoDisplayName->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoCarouselDisplayName->getName(),
                    $photoDisplayName->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoGridDisplayUpdateDate->getName(),
                    $photoDisplayUpdateDate->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoListDisplayUpdateDate->getName(),
                    $photoDisplayUpdateDate->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoSimpleDisplayUpdateDate->getName(),
                    $photoDisplayUpdateDate->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoCarouselDisplayUpdateDate->getName(),
                    $photoDisplayUpdateDate->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoGridDisplayShortDescription->getName(),
                    $photoDisplayShortDescription->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoListDisplayShortDescription->getName(),
                    $photoDisplayShortDescription->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoSimpleDisplayShortDescription->getName(),
                    $photoDisplayShortDescription->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoCarouselDisplayShortDescription->getName(),
                    $photoDisplayShortDescription->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoGridDisplayShowLink->getName(),
                    $photoDisplayShowLink->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoListDisplayShowLink->getName(),
                    $photoDisplayShowLink->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoSimpleDisplayShowLink->getName(),
                    $photoDisplayShowLink->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoCarouselDisplayShowLink->getName(),
                    $photoDisplayShowLink->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoGridDisplayRate->getName(),
                    $photoDisplayRate->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoListDisplayRate->getName(),
                    $photoDisplayRate->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoSimpleDisplayRate->getName(),
                    $photoDisplayRate->getName(),
                    1
                )
                ->addFieldDependence(
                    $photoCarouselDisplayRate->getName(),
                    $photoDisplayRate->getName(),
                    1
                )
        );

        return parent::_prepareForm();
    }
}