<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Common_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Setup
{
    /**
     * @var array
     */
    protected $_stores = array(0,1);

    /**
     * Create Product attributes for select list
     *
     * @param string $attribute_code
     * @param array $optionsArray
     */
    public function addAttributeOptions($attribute_code, array $optionsArray)
    {
        $tableOptions = $this->getTable('eav_attribute_option');
        $tableOptionValues = $this->getTable('eav_attribute_option_value');
        $attributeId = (int) $this->getAttribute('catalog_product', $attribute_code, 'attribute_id');

        foreach ($optionsArray as $sortOrder => $label) {
            // add option
            $data = array(
                'attribute_id' => $attributeId,
                'sort_order' => $sortOrder,
            );
            $this->getConnection()->insert($tableOptions, $data);
            $optionId = (int) $this->getConnection()->lastInsertId($tableOptions, 'option_id');

            // add option label
            foreach ($this->getStoresId() as $storeId) {
                $data = array(
                    'option_id' => $optionId,
                    'store_id' => $storeId,
                    'value' => $label,
                );
                $this->getConnection()->insert($tableOptionValues, $data);
            }
        }
    }

    public function getStoresId()
    {
        if (!$this->_stores) {
            $stores = Mage::app()->getStores();

            foreach ($stores as $id => $store) {
                $this->_stores[$id] = $store;
            }
        }

        return $this->_stores;
    }
}