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
 * Class Mageplace_Gallery_Block_Adminhtml_Multiupload_Form
 */
class Mageplace_Gallery_Block_Adminhtml_Multiupload_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $form->addField('album_id',
            'note',
            array(
                'name' => 'album_id',
                'text' => $this->getBlockHtml('mp.multiupload.albums'),
            )
        );

        $fieldset = $form->addFieldset('parameters_fieldset',
            array(
                'legend' => $this->__('Upload Parameters')
            )
        );

        $pageTitleType = $fieldset->addField('type_page_title',
            'select',
            array(
                'name'     => 'type_page_title',
                'label'    => $this->__('Title for photos'),
                'title'    => $this->__('Title for photos'),
                'options' => array(
                    'filename' => $this->__('Use image filename'),
                    'input'  => $this->__('Enter a title'),
                )
            )
        );

        $pageTitle = $fieldset->addField('page_title',
            'text',
            array(
                'name'     => 'page_title',
                'required' => true,
            )
        );

        $type = $fieldset->addField('source_type',
            'select',
            array(
                'name'    => 'source_type',
                'label'   => $this->__('Multiupload type'),
                'title'   => $this->__('Multiupload type'),
                'options' => array(
                    'file' => $this->__('Upload package file'),
                    'dir'  => $this->__('Create from directory'),
                )
            )
        );

        $maxSize = Mage::helper('mpgallery')->getUploadFileMaxSize();
        $package = $fieldset->addField('upload_package',
            'file',
            array(
                'name'  => 'upload_package',
                'label' => $this->__('Package File'),
                'note'  => $this->__('Select zip file.') . ($maxSize !== '' ? ' ' . $this->__('Upload file max size: %s.', $maxSize) : '')
            )
        );


        $directory = $fieldset->addField('input_dir',
            'text',
            array(
                'name'     => 'input_dir',
                'label'    => $this->__('Directory'),
                'title'    => $this->__('Directory'),
                'value'    => 'media/',
                'disabled' => true
            )
        );

        $delete = $fieldset->addField('delete_files',
            'checkbox',
            array(
                'name'     => 'delete_files',
                'label'    => $this->__('Delete source files from directory after upload'),
                'title'    => $this->__('Delete source files from directory after upload'),
                'value'    => 1,
            )
        );

        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setMethod('post');
        $form->setEnctype('multipart/form-data');
        $form->setAction($this->getSaveUrl());

        $this->setForm($form);

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->addFieldMap($type->getHtmlId(), $type->getName())
                ->addFieldMap($package->getHtmlId(), $package->getName())
                ->addFieldMap($directory->getHtmlId(), $directory->getName())
                ->addFieldMap($delete->getHtmlId(), $delete->getName())
                ->addFieldMap($pageTitleType->getHtmlId(), $pageTitleType->getName())
                ->addFieldMap($pageTitle->getHtmlId(), $pageTitle->getName())
                ->addFieldDependence($package->getName(), $type->getName(), 'file')
                ->addFieldDependence($directory->getName(), $type->getName(), 'dir')
                ->addFieldDependence($delete->getName(), $type->getName(), 'dir')
                ->addFieldDependence($pageTitle->getName(), $pageTitleType->getName(), 'input')
        );
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save');
    }
}
