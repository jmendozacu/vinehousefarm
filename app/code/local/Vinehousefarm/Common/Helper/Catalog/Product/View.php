<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Common_Helper_Catalog_Product_View extends Mage_Core_Helper_Abstract
{
    const ATTRIBUTE_CODE = 'show_simple_products';
    const ATTRIBUTE_VALUE = 'Yes';

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    public function showSimpleProducts(Mage_Catalog_Model_Product$product)
    {
        $value = $product->getAttributeText(self::ATTRIBUTE_CODE);

        if ($value) {
            if ($value == self::ATTRIBUTE_VALUE) {
                return true;
            }
        }

        return false;
    }
}