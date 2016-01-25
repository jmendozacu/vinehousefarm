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
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Design
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Design extends Mageplace_Gallery_Block_Adminhtml_Album_Tab_Abstract
{
    protected function _prepareForm()
    {
        $isNew = !$this->getAlbum()->getId();

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('design_fieldset', array(
            'legend' => Mage::helper('core')->__('Custom Design'),
            'class'  => 'fieldset-wide',
        ));

        $parentSettings = $fieldset->addField('design_use_parent_settings',
            'select',
            array(
                'name'     => 'design_use_parent_settings',
                'label'    => $this->__('Use Parent Album Settings'),
                'title'    => $this->__('Use Parent Album Settings'),
                'values'   => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
                'disabled' => $this->getAlbum()->getId() == Mageplace_Gallery_Model_Album::TREE_ROOT_ID,
            )
        );

        if ($isNew && $this->getAlbum()->getId() != Mageplace_Gallery_Model_Album::TREE_ROOT_ID) {
            $this->getAlbum()->setData('design_use_parent_settings', 1);
        }

        $customDesign = $fieldset->addField('design_custom',
            'select',
            array(
                'name'   => 'design_custom',
                'label'  => Mage::helper('catalog')->__('Custom Design'),
                'title'  => Mage::helper('catalog')->__('Custom Design'),
                'values' => Mage::getSingleton('core/design_source_design')->getAllOptions()
            )
        );

        $pageLayout = $fieldset->addField('page_layout',
            'select',
            array(
                'name'   => 'page_layout',
                'label'  => Mage::helper('catalog')->__('Page Layout'),
                'title'  => Mage::helper('catalog')->__('Page Layout'),
                'values' => Mage::getSingleton('mpgallery/source_pagelayout')->toOptionArray()
            )
        );

        $form->addValues($this->getAlbum()->getData());

        $form->setFieldNameSuffix('design');
        $this->setForm($form);

        $this->setChild('form_after',
            $this->getLayout()->createBlock('mpgallery/adminhtml_widget_form_element_dependence')
                ->addFieldMap($parentSettings->getHtmlId(), $parentSettings->getName())
                ->addFieldMap($customDesign->getHtmlId(), $customDesign->getName())
                ->addFieldMap($pageLayout->getHtmlId(), $pageLayout->getName())
                ->addFieldDependence(
                    $customDesign->getName(),
                    $parentSettings->getName(),
                    0
                )
                ->addFieldDependence(
                    $pageLayout->getName(),
                    $parentSettings->getName(),
                    0
                )
        );

        return parent::_prepareForm();
    }
}

