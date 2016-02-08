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
 * Class Mageplace_Gallery_Block_Adminhtml_Review_Edit_Form
 */
class Mageplace_Gallery_Block_Adminhtml_Review_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $review = Mage::registry('review');

        $photo = Mage::getModel('mpgallery/photo')->load($review->getPhotoId());

        $id = $review->getId();

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset',
            array(
                'legend' => $this->__('Review details'),
                'class'  => 'fieldset-wide'
            )
        );

        $fieldset->addField('photo',
            'note',
            array(
                'label' => $this->__('Photo'),
                'text'  =>
                    '<div id="photo_info">'
                    . ($id ?
                        '<a href="' . $this->getUrl('*/gallery_photo/edit', array('id' => $photo->getId())) . '" onclick="this.target=\'blank\'">'
                        . '<img src="' . Mage::helper('mpgallery/image')->initialize($photo, 'thumbnail')->resizeBySize(Mage::helper('mpgallery/config')->getAdminThumbSize()) . '" />'
                        . '</a>'
                        . '<br />'
                        . '<a href="' . $this->getUrl('*/gallery_photo/edit', array('id' => $photo->getId())) . '" onclick="this.target=\'blank\'">'
                        . $photo->getName()
                        . '</a>'
                        : '')
                    . '</div>'

            )
        );

        $fieldset->addField('status',
            'select',
            array(
                'name'     => 'status',
                'label'    => $this->helper('review')->__('Status'),
                'title'    => $this->helper('review')->__('Status'),
                'required' => true,
                'values'   => Mage::getSingleton('mpgallery/source_reviewstatus')->toOptionArray()
            )
        );

        $fieldset->addField('name',
            'text',
            array(
                'name'     => 'name',
                'label'    => $this->helper('review')->__('Name'),
                'title'    => $this->helper('review')->__('Name'),
                'required' => true,
            )
        );

        $fieldset->addField('email',
            'text',
            array(
                'name'     => 'email',
                'label'    => $this->helper('customer')->__('Email'),
                'title'    => $this->helper('customer')->__('Email'),
                'required' => true,
            )
        );

        $fieldset->addField('rate',
            'select',
            array(
                'name'     => 'rate',
                'label'    => $this->__('Rate'),
                'title'    => $this->__('Rate'),
                'required' => true,
                'options'  => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                )
            )
        );

        $fieldset->addField('comment',
            'textarea',
            array(
                'name'   => 'comment',
                'label'  => $this->__('Review'),
                'title'  => $this->__('Review'),
                'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            )
        );

        if ($id) {
            $fieldset->addField('review_id',
                'hidden',
                array(
                    'name' => 'review_id'
                )
            );
        } else {
            $fieldset->addField('photo_id',
                'hidden',
                array(
                    'name'    => 'photo_id',
                    'html_id' => 'photo_id'
                )
            );
        }


        $form->setValues($review->getData());

        $form->setUseContainer(true);
        $form->setAction($this->getSaveUrl());
        $form->setId('edit_form');
        $form->setMethod('post');

        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save');
    }
}
