<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Adminhtml_Library_Edit_Tab_Gallery
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('birdlibrary')->__('Gallery');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('birdlibrary')->__('Gallery');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {
        $model  = $this->_getModel();

        $model->setGallery(implode(',', $model->getGallery()));

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('bird_links_');
        $form->setFieldNameSuffix('links');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'class'     => 'fieldset-wide',
        ));

        if ($model && $model->getId()) {
            $modelPk = $model->getResource()->getIdFieldName();
            $fieldset->addField($modelPk, 'hidden', array(
                'name' => $modelPk,
            ));
        }

        $fieldset->addField('gallery', 'gallery', array(
            'name'      => 'gallery',
            'label'     => $this->_getHelper()->__('Gallery'),
            'required'  => true,
        ));

        if($model){
            $form->setValues($model->getData());
        }
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _getModel()
    {
        return Mage::registry('current_data');
    }

    protected function _getHelper()
    {
        return Mage::helper('birdlibrary');
    }
}