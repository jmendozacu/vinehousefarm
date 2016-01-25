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
 * Class Mageplace_Gallery_Model_System_Config_Backend_Size
 */
class Mageplace_Gallery_Model_System_Config_Backend_Size extends Mage_Core_Model_Config_Data
{
    const DELIMITER = Mageplace_Gallery_Helper_Const::WIDTH_HEIGHT_DELIMITER;
    const WIDTH     = Mageplace_Gallery_Helper_Const::WIDTH;
    const HEIGHT    = Mageplace_Gallery_Helper_Const::HEIGHT;


    protected function _beforeSave()
    {
        $value = $this->getValue();

        if (!empty($value[self::WIDTH]) || !empty($value[self::HEIGHT])) {
            if(empty($value[self::HEIGHT])) {
                $value[self::HEIGHT] = $value[self::WIDTH];
            }
            if(empty($value[self::WIDTH])) {
                $value[self::WIDTH] = $value[self::HEIGHT];
            }

            $this->setValue($value[self::WIDTH] . self::DELIMITER . $value[self::HEIGHT]);
        } else {
            $this->setValue(null);
        }

        parent::_beforeSave();
    }
}
