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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tab_General
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tab_General extends Mageplace_Gallery_Block_Adminhtml_Album_Tab_Abstract
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $this->__('General Settings'),
            'class'  => 'fieldset-wide',
        ));

        if ($this->getAlbum()->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name'  => 'id',
                'value' => $this->getAlbum()->getId()
            ));
            $fieldset->addField('path', 'hidden', array(
                'name'  => 'path',
                'value' => $this->getAlbum()->getPath()
            ));
        } else {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mageplace_Gallery_Model_Album::TREE_ROOT_ID;
            }
            $fieldset->addField('path', 'hidden', array(
                'name'  => 'path',
                'value' => $parentId
            ));
        }

        $fieldset->addField('name',
            'text',
            array(
                'name'     => 'name',
                'label'    => $this->__('Name'),
                'title'    => $this->__('Name'),
                'required' => true,
            )
        );

        if ($this->getAlbum()->getId() != Mageplace_Gallery_Model_Album::TREE_ROOT_ID) {
            $fieldset->addField('url_key',
                'text',
                array(
                    'name'  => 'url_key',
                    'label' => $this->__('URL key'),
                    'title' => $this->__('URL key'),
                )
            );
        }

        $fieldset->addType('image_path', Mage::getConfig()->getBlockClassName('mpgallery/varien_data_form_element_image_path'));

        $fieldset->addField('image',
            'image_path',
            array(
                'name'  => 'image',
                'label' => $this->__('Image'),
                'style' => 'width: 300px'
            )
        );
        if ($imageUrl = $this->getAlbum()->getData('image')) {
            if (is_array($imageUrl)) {
                if (!empty($imageUrl['value'])) {
                    $imageUrl = $imageUrl['value'];
                } else {
                    $imageUrl = '';
                }

                $this->getAlbum()->setData('image', $imageUrl);

            } else {
                $this->getAlbum()->setData('image', Mage::helper('mpgallery/album')->getImageUrl($imageUrl));
            }
        }

        $fieldset->addField('is_active',
            'select',
            array(
                'name'     => 'is_active',
                'label'    => $this->__('Is Active'),
                'title'    => $this->__('Is Active'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
            )
        );

        $onlyForRegistered = $fieldset->addField('only_for_registered',
            'select',
            array(
                'name'   => 'only_for_registered',
                'label'  => $this->__('Only for registered'),
                'title'  => $this->__('Only for registered'),
                'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );

        $customerGroupIds = $fieldset->addField('customer_group_ids',
            'multiselect',
            array(
                'name'   => 'customer_group_ids[]',
                'label'  => $this->__('Customer Groups'),
                'title'  => $this->__('Customer Groups'),
                'values' => Mage::helper('customer')
                        ->getGroups()
                        ->toOptionArray(),
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id',
                'multiselect',
                array(
                    'name'     => 'stores[]',
                    'label'    => Mage::helper('cms')->__('Store view'),
                    'title'    => Mage::helper('cms')->__('Store view'),
                    'required' => true,
                    'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
                )
            );

            if (!$this->getAlbum()->getData('store_id')) {
                $this->getAlbum()->setData('store_id', 0);
            }
        } else {
            $fieldset->addField('store_id',
                'hidden',
                array(
                    'name'  => 'stores[]',
                    'value' => Mage::app()->getStore(true)->getId()
                )
            );

            $this->getAlbum()->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('short_description',
            'textarea',
            array(
                'name'  => 'short_description',
                'label' => $this->__('Short Description'),
                'title' => $this->__('Short Description'),
            )
        );

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $wysiwygButton = $this->getLayout()
                ->createBlock('adminhtml/widget_button', '', array(
                    'label'   => Mage::helper('catalog')->__('WYSIWYG Editor'),
                    'type'    => 'button',
                    'class'   => 'btn-wysiwyg',
                    'onclick' => 'catalogWysiwygEditor.open(\'' . $this->getUrl('*/catalog_category/wysiwyg') . '\', \'short_description\')'
                ))->toHtml();

            $form->getElement('short_description')->setAfterElementHtml($wysiwygButton);
        }

        $fieldset->addField('description',
            'textarea',
            array(
                'name'  => 'description',
                'label' => $this->__('Description'),
                'title' => $this->__('Description'),
            )
        );

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $wysiwygButton = $this->getLayout()
                ->createBlock('adminhtml/widget_button', '', array(
                    'label'   => Mage::helper('catalog')->__('WYSIWYG Editor'),
                    'type'    => 'button',
                    'class'   => 'btn-wysiwyg',
                    'onclick' => 'catalogWysiwygEditor.open(\'' . $this->getUrl('*/catalog_category/wysiwyg') . '\', \'description\')'
                ))->toHtml();

            $form->getElement('description')->setAfterElementHtml($wysiwygButton);
        }


        $form->addValues($this->getAlbum()->getData());

        $form->setFieldNameSuffix('general');
        $this->setForm($form);

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->addFieldMap($onlyForRegistered->getHtmlId(), $onlyForRegistered->getName())
                ->addFieldMap($customerGroupIds->getHtmlId(), $customerGroupIds->getName())
                ->addFieldDependence(
                    $customerGroupIds->getName(),
                    $onlyForRegistered->getName(),
                    1
                )
        );

        return parent::_prepareForm();
    }
}

