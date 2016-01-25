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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Form
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('photo');

        $form = new Varien_Data_Form();
        $form->setId(Mageplace_Gallery_Block_Adminhtml_Photo_Edit::EDIT_FORM_ID);
        $form->setAction($this->getSaveUrl());
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setEnctype('multipart/form-data');

        $form->addField('photo_id',
            'hidden',
            array(
                'name' => 'photo_id',
                'value' => $model->getid()
            )
        );


        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save');
    }
}