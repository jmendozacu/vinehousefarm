<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Adminhtml_Library_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _getModel()
    {
        return Mage::registry('current_data');
    }

    protected function _getHelper()
    {
        return Mage::helper('birdlibrary');
    }

    protected function _getModelTitle()
    {
        return 'Bird';
    }

    protected function _prepareForm()
    {
        $model  = $this->_getModel();

        if ($model->getInProducts()) {
            $model->setInProducts(implode(',', $model->getInProducts()));
        }

        if ($model->getInLinks()) {
            $model->setInLinks(implode(',', $model->getInLinks()));
        }

        //$model->setDistributionMap(array('value' => $model->getDistributionMap()));
        //$model->setEggNest(array('value' => $model->getEggNest()));
        //$model->setAudioFile(array('value' => $model->getAudioFile()));

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('bird_main_');
        $form->setFieldNameSuffix('main');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $this->_getHelper()->__('Item Info'),
            'class'     => 'fieldset-wide',
        ));

        $fieldset->addType('imagemap', 'Vinehousefarm_Birdlibrary_Block_Adminhtml_Library_Renderer_Image');
        $fieldset->addType('sound', 'Vinehousefarm_Birdlibrary_Block_Adminhtml_Library_Renderer_Sound');

        if ($model && $model->getId()) {
            $modelPk = $model->getResource()->getIdFieldName();
            $fieldset->addField($modelPk, 'hidden', array(
                'name' => $modelPk,
            ));
        }

        $fieldset->addField('bird_name', 'text', array(
            'name'      => 'bird_name',
            'label'     => $this->_getHelper()->__('Name'),
            'required'  => true,
        ));

        $fieldset->addField('latin_name', 'text', array(
            'name'      => 'latin_name',
            'label'     => $this->_getHelper()->__('Latin Name'),
            'required'  => true,
        ));

        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => $this->_getHelper()->__('META Title'),
            'required'  => true,
        ));

        $fieldset->addField('description', 'textarea', array(
            'name'      => 'description',
            'label'     => $this->_getHelper()->__('META Description'),
            'required'  => true,
        ));

        $fieldset->addField('keywords', 'text', array(
            'name'      => 'keywords',
            'label'     => $this->_getHelper()->__('META Keywords'),
            'required'  => true,
        ));

        $fieldset->addField('url', 'text', array(
            'name'      => 'url',
            'label'     => $this->_getHelper()->__('URL'),
            'class'     => 'validate-identifier',
            'required'  => true,
        ));


        $fieldset->addField('family', 'textarea', array(
            'name'      => 'family',
            'label'     => $this->_getHelper()->__('Family'),
            'required'  => true,
        ));

        $fieldset->addField('overview', 'textarea', array(
            'name'      => 'overview',
            'label'     => $this->_getHelper()->__('Overview'),
        ));

        $fieldset->addField('distribution_map', 'imagemap', array(
            'name'      => 'distribution_mapi',
            'label'     => $this->_getHelper()->__('Distribution Map and Info'),
            'path'      => 'bird' . DS . 'maps' . DS,
            //'required'  => true,
        ));

        $fieldset->addField('egg_nest', 'imagemap', array(
            'name'      => 'egg_nesti',
            'label'     => $this->_getHelper()->__('Eggs & Nest'),
            'path'      => 'bird' . DS . 'eggnest' . DS,
            //'required'  => true,
        ));

        $fieldset->addField('habitat', 'textarea', array(
            'name'      => 'habitat',
            'label'     => $this->_getHelper()->__('Habitat'),
            'required'  => true,
        ));

        $fieldset->addField('population', 'textarea', array(
            'name'      => 'population',
            'label'     => $this->_getHelper()->__('UK Breeding Population'),
            'required'  => true,
        ));

        $fieldset->addField('breeding', 'textarea', array(
            'name'      => 'breeding',
            'label'     => $this->_getHelper()->__('Breeding'),
            'required'  => true,
        ));

        $fieldset->addField('food_diet', 'textarea', array(
            'name'      => 'food_diet',
            'label'     => $this->_getHelper()->__('Food/Diet'),
            'required'  => true,
        ));

        $fieldset->addField('trends', 'textarea', array(
            'name'      => 'trends',
            'label'     => $this->_getHelper()->__('Trends'),
            'required'  => true,
        ));

        $fieldset->addField('behaviour', 'textarea', array(
            'name'      => 'behaviour',
            'label'     => $this->_getHelper()->__('Behaviour'),
            'required'  => true,
        ));

        $fieldset->addField('audio_file', 'sound', array(
            'name'      => 'audio_filei',
            'label'     => $this->_getHelper()->__('Song/Audio Files'),
            //'required'  => true,
        ));

        $fieldset->addField('video_file', 'text', array(
            'name'      => 'video_file',
            'label'     => $this->_getHelper()->__('Video excerpts'),
            'required'  => true,
        ));

        $fieldset->addField('in_garden', 'checkbox', array(
            'name'      => 'in_garden',
            'label'     => $this->_getHelper()->__('Seen in VHF Garden'),
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
        ))->setIsChecked($model->getInGarden());

        $fieldset->addField('in_products', 'hidden', array(
            'name' => 'in_products',
        ));

        $fieldset->addField('in_links', 'hidden', array(
            'name' => 'in_links',
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
        return Mage::helper('birdlibrary')->__('Bird Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('birdlibrary')->__('Bird Info');
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