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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Grid_Massaction
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Grid_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Massaction
{
    public function getApplyButtonHtml()
    {
        return $this->getButtonHtml(Mage::helper('adminhtml')->__('Submit'), $this->getJsObjectName() . '.mpPhotoGridApply(\'' . $this->getSavePositionName() . '\')');
    }

    public function getJavaScript()
    {
        return parent::getJavaScript()
        . ($this->getAlbumIdFilterName() ? "\n" . 'MP.albumIdFilterName = "' . $this->getAlbumIdFilterName() . '";' : '')
        . ($this->getPositionFieldName() ? "\n" . 'MP.positionFieldName = "' . $this->getPositionFieldName() . '";' : '');
    }
}