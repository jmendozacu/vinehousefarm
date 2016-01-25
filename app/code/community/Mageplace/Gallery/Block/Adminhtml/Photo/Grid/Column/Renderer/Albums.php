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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Grid_Column_Renderer_Albums
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Grid_Column_Renderer_Albums
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param Mageplace_Gallery_Model_Photo|Varien_Object $photo
     *
     * @return string
     */
    public function render(Varien_Object $photo)
    {
        $names = array();
        if ($albums = $photo->getData($this->getColumn()->getIndex())) {
            if(is_array($albums)) {
                foreach($albums as $albumId) {
                    $album = Mage::getModel('mpgallery/album')->load($albumId);
                    if($name = $album->getName()) {
                        $names[] = $name;
                    }
                }
            }
        }

        return implode('<br />', $names);
    }
}
