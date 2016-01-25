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
 * Class Mageplace_Gallery_Model_Source_Sortdir
 */
class Mageplace_Gallery_Model_Source_Sortdir extends Mageplace_Gallery_Model_Source_Abstract
{
    public function toOptionArray($includeAll = false)
    {
        $options = array(
            array('value' => Varien_Data_Collection_Db::SORT_ORDER_ASC, 'label' => $this->_helper()->__('ASC')),
            array('value' => Varien_Data_Collection_Db::SORT_ORDER_DESC, 'label' => $this->_helper()->__('DESC')),
        );

        return $options;
    }
}