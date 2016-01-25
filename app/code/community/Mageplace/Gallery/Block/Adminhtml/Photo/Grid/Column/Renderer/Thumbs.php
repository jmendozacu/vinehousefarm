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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Grid_Column_Renderer_Thumbs
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Grid_Column_Renderer_Thumbs
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param Mageplace_Gallery_Model_Photo|Mageplace_Gallery_Model_Review|Varien_Object $object
     *
     * @return string
     */
    public function render(Varien_Object $object)
    {
        if ($object instanceof Mageplace_Gallery_Model_Photo) {
            if(!$object->getData($this->getColumn()->getIndex())) {
                return '';
            }

            $photo = $object;
        } elseif($object->getPhotoId()) {
            $photo = Mage::getModel('mpgallery/photo')->load($object->getPhotoId());
        } else {
            return '';
        }

        $image = Mage::helper('mpgallery/image')->initialize($photo, 'thumbnail')->resizeBySize(Mage::helper('mpgallery/config')->getAdminThumbSize());

        return '<img src="' . $image->__toString() . '" />';
    }
}
