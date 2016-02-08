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
 * Class Mageplace_Gallery_Model_Source_Imageuploadtype
 */
class Mageplace_Gallery_Model_Source_Imageuploadtype extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray($includeAll = false)
    {
        $options = array(
            array('value' => 0, 'label' => $this->_helper()->__('Upload file')),
            array('value' => 1, 'label' => $this->_helper()->__('Enter file location')),
        );

        return $options;
    }
}