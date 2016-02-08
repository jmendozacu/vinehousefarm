<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Common_Helper_Catalog_Product_Options extends Mage_Core_Helper_Abstract
{
    const BLOCK_TYPE = 'Mage_Catalog_Block_Product_View_Options';

    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $product;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var Mage_Catalog_Block_Product_View_Options
     */
    protected $block;

    /**
     * @return Mage_Catalog_Block_Product_View_Options
     */
    public function getOptionsBlock()
    {
        if (!$this->block) {
            $this->block = Mage::app()->getLayout()->createBlock(self::BLOCK_TYPE);

            if ($this->block) {
                $this->block->addOptionRenderer("default","catalog/product_view_options_type_default","catalog/product/view/options/type/default.phtml");
            }
        }

        return $this->block;
    }
}