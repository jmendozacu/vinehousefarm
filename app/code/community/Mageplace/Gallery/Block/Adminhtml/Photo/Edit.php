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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Edit
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    const EDIT_FORM_ID = 'photo_edit_form';

    protected $_objectId = 'id';
    protected $_blockGroup = 'mpgallery';
    protected $_controller = 'adminhtml_photo';

    public function __construct()
    {
        parent::__construct();

        $this->_addButton('saveandcontinue',
            array(
                'label'   => $this->__('Save and continue'),
                'onclick' => 'saveAndContinueEdit(\'' . $this->getSaveAndContinueUrl() . '\')',
                'class'   => 'save'
            ),
            -100
        );

        $this->addFormScripts("
            editForm = new varienForm('photo_edit_form', '" . $this->getValidationUrl() . "');

			function saveAndContinueEdit(urlTemplate){
				var urlTemplateSyntax = /(^|.|\\r|\\n)({{(\\w+)}})/;
                var template = new Template(urlTemplate, urlTemplateSyntax);
                var url = template.evaluate({tab_id:" . Mageplace_Gallery_Block_Adminhtml_Photo_Edit_Tabs::TABS_BLOCK_ID . "JsTabs.activeTab.id});
        		editForm.submit(url);
			}
        ");
    }

    public function addFormScripts($js)
    {
        $this->_formScripts[] = $js;
    }

    public function getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'   => true,
            'back'       => 'edit',
            'tab'        => '{{tab_id}}',
            'active_tab' => null
        ));
    }

    public function getHeaderText()
    {
        if ($id = Mage::registry('photo')->getId()) {
            return $this->__('Edit Photo (ID: %s)', $id);
        } else {
            return $this->__('New Photo');
        }
    }

    public function getHeaderCssClass()
    {
        return '';
    }
}
