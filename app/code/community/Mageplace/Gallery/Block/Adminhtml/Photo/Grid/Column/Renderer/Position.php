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
 * Class Mageplace_Gallery_Block_Adminhtml_Photo_Grid_Column_Renderer_Position
 */
class Mageplace_Gallery_Block_Adminhtml_Photo_Grid_Column_Renderer_Position
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
        $positions = $photo->getData('positions');
        if(!is_array($positions) || empty($positions)) {
            return '';
        }

        if (!$albumId = (int)$this->getColumn()->getData('album')) {
            $names = array();
            foreach ($positions as $aid => $position) {
                $album = Mage::getModel('mpgallery/album')->load($aid);
                if ($name = $album->getName()) {
                    $names[] = $position;
                }
            }

            return implode('<br />', $names);
        }

        $value = empty($positions[$albumId]) ? 0 : (int)$positions[$albumId];

        $html = '<input type="text" ';
        $html .= 'name="' . $this->getColumn()->getId() . '[' . $photo->getId() . ']" ';
        $html .= 'value="' . $value . '" ';
        $html .= 'class="input-text ' . $this->getColumn()->getInlineCss() . '"/>';

        return $html;
    }
}
