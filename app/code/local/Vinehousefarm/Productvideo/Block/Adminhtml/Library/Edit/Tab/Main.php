<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Productvideo_Block_Adminhtml_Library_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _getModel()
    {
        return Mage::registry('current_data');
    }

    protected function _getHelper()
    {
        return Mage::helper('productvideo');
    }

    protected function _getModelTitle()
    {
        return 'Video';
    }

    protected function _prepareForm()
    {
        $model  = $this->_getModel();

        $model->setInProducts(implode(',', $model->getInProducts()));

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('video_main_');
        $form->setFieldNameSuffix('main');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $this->_getHelper()->__('Item Info'),
            'class'     => 'fieldset-wide',
        ));

        if ($model && $model->getId()) {
            $modelPk = $model->getResource()->getIdFieldName();
            $fieldset->addField($modelPk, 'hidden', array(
                'name' => $modelPk,
            ));
        }

        $fieldset->addField('video_name', 'text', array(
            'name'      => 'video_name',
            'label'     => $this->_getHelper()->__('Name'),
            'required'  => true,
        ));

        $fieldset->addField('video_code', 'text', array(
            'name'      => 'video_code',
            'label'     => $this->_getHelper()->__('YouTube Video ID'),
            'required'  => true,
        ));

        $fieldset->addField('in_products', 'hidden', array(
            'name' => 'in_products',
        ));

        if($model){
            $form->setValues($model->getData());
        }
        //$form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('productvideo')->__('Video Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('productvideo')->__('Video Info');
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
}