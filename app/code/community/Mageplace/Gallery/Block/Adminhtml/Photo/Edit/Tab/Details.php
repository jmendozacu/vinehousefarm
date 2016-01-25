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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Details
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tab_Details extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('photo');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset',
            array(
                'legend' => $this->__('Photo Details'),
                'class'  => 'fieldset-wide'
            )
        );

        $isNew = !$model->getId() ? true : false;

        $fieldset->addField('name',
            'text',
            array(
                'name'     => 'name',
                'label'    => $this->__('Name'),
                'title'    => $this->__('Name'),
                'required' => true,
            )
        );

        $fieldset->addField('url_key',
            'text',
            array(
                'name'  => 'url_key',
                'label' => $this->__('URL key'),
                'title' => $this->__('URL key'),
            )
        );

        if ($customerId = $model->getCustomerId()) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            if ($customerId = $customer->getId()) {
                $fieldset->addField('author',
                    'note',
                    array(
                        'name'  => 'author',
                        'label' => $this->__('Author'),
                        'title' => $this->__('Author'),
                        'text'  =>
                            '<a href="' . $this->getUrl('*/customer/edit', array('id' => $customerId)) . '" target="_blank">'
                            . $customer->getName()
                            . '</a>'
                    )
                );
            }
        }

        $fieldset->addField('author_name',
            'text',
            array(
                'name'  => 'author_name',
                'label' => $this->__('Author Name'),
                'title' => $this->__('Author Name'),
            )
        );

        $fieldset->addField('author_email',
            'text',
            array(
                'name'  => 'author_email',
                'label' => $this->__('Author Email'),
                'title' => $this->__('Author Email'),
            )
        );

        $fieldset->addType('image_path', Mage::getConfig()->getBlockClassName('mpgallery/varien_data_form_element_image_path'));

        $fieldset->addField('image',
            'image_path',
            array(
                'name'  => 'image',
                'label' => $this->__('Image'),
                'style' => 'width: 300px'
            )
        );
        if ($imageUrl = $model->getData('image')) {
            if (is_array($imageUrl)) {
                if (!empty($imageUrl['value'])) {
                    $imageUrl = $imageUrl['value'];
                } else {
                    $imageUrl = '';
                }

                $model->setData('image', $imageUrl);

            } else {
                $model->setData('image', Mage::helper('mpgallery/photo')->getImageUrl($imageUrl));
            }
        }

        $fieldset->addField('is_active',
            'select',
            array(
                'name'     => 'is_active',
                'label'    => $this->__('Is Active'),
                'title'    => $this->__('Is Active'),
                'required' => true,
                'values'   => Mage::getSingleton('mpgallery/source_photostatus')->toOptionArray()
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
            if (!$model->getData('store_id')) {
                $model->setData('store_id', 0);
            }
        } else {
            $fieldset->addField('store_id',
                'hidden',
                array(
                    'name'  => 'stores[]',
                    'value' => Mage::app()->getStore(true)->getId()
                )
            );

            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('description',
            'editor',
            array(
                'name'   => 'description',
                'label'  => $this->__('Description'),
                'title'  => $this->__('Description'),
                'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(array(
                        'add_variables' => false,
                        'add_widgets'   => false,
                        'add_images'    => false,
                    ))
            )
        );

        $fieldset->addField('short_description',
            'editor',
            array(
                'name'   => 'short_description',
                'label'  => $this->__('Short Description'),
                'title'  => $this->__('Short Description'),
                'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(array(
                        'add_variables' => false,
                        'add_widgets'   => false,
                        'add_images'    => false,
                    ))
            )
        );

        if ($isNew) {
            $model->setAuthorName(Mage::getSingleton('admin/session')->getUser()->getName());
        }

        $form->setValues($model->getData());

        $this->setForm($form);

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->addFieldMap($onlyForRegistered->getHtmlId(), $onlyForRegistered->getName())
                ->addFieldMap($customerGroupIds->getHtmlId(), $customerGroupIds->getName())
                ->addFieldDependence($customerGroupIds->getName(), $onlyForRegistered->getName(), 1)
        );

        return parent::_prepareForm();
    }
}
