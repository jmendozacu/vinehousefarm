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
 * Class Mageplace_Gallery_Model_Source_Photodisplaymode
 */
class Mageplace_Gallery_Model_Source_Photodisplaymode extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array('value' => Mageplace_Gallery_Model_Photo::DISPLAY_MODE_PHOTO_LIST, 'label' => $this->_helper()->__('Photo, List')),
            array('value' => Mageplace_Gallery_Model_Photo::DISPLAY_MODE_LIST_PHOTO, 'label' => $this->_helper()->__('List, Photo')),
            array('value' => Mageplace_Gallery_Model_Photo::DISPLAY_MODE_PHOTO, 'label' => $this->_helper()->__('Only Photo')),
        );
    }
}