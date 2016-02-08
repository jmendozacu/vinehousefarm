<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Common_Block_Catalog_Product_View_Icons extends Mage_Catalog_Block_Product_View
{
    const ICONS_PATH = 'images/vinehousefarm/products/suitable/';
    const ICONS_EXT = '.png';
    const ATTRIBUTE_CODE = 'suitable_for';

    protected $values;

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param mixed $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getProductIcons()
    {
        $result = array();

        if (!$this->getValues()) {
            $values = $this->getProduct()->getAttributeText(self::ATTRIBUTE_CODE);

            if (!$values) {
                return $result;
            }

            if (!is_array($values)) {
                $this->setValues(array($values));
            } else {
                $this->setValues($values);
            }
        }

        foreach ($this->getValues() as $value) {
            $result[] = array(
                'code' => trim(strtolower(str_replace(' ','_', $value))),
                'label' => $value,
            );
        }

        return $result;
    }

    /**
     * @param $code
     * @return string
     */
    public function getIconUrl($code)
    {
        if ($code) {
            return $this->getSkinUrl(self::ICONS_PATH . $code . self::ICONS_EXT , array('_secure'=>true));
        }

        return '';
    }

    /**
     * @param $code
     * @return string
     */
    public function getContentTab($code)
    {
        if ($code) {
            $block = $this->getLayout()->createBlock('cms/block')->setBlockId(self::ATTRIBUTE_CODE . '_' . $code);

            if ($block) {
                return $block->toHtml();
            }
        }

        return '';
    }
}