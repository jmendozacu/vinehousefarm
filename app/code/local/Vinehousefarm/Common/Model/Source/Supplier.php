<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Common_Model_Source_Supplier extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            /* @var $collection MDN_Purchase_Model_Mysql4_Supplier_Collection */
            $collection = Mage::getModel('Purchase/Supplier')->getCollection();

            $this->_options[] = array(
                'label' => Mage::helper('vinehousefarm_common')->__('No Supplier'),
                'value' => 0,
            );

            foreach ($collection as $item) {
                $this->_options[] = array(
                    'label' => $item->getSupName(),
                    'value' => $item->getSupId(),
                );
            }
        }
        return $this->_options;
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    public function getFlatColums()
    {
        $columns = array(
            $this->getAttribute()->getAttributeCode() => array(
                'type'      => 'int',
                'unsigned'  => false,
                'is_null'   => true,
                'default'   => null,
                'extra'     => null
            )
        );
        return $columns;
    }

    public function getFlatUpdateSelect($store)
    {
        return Mage::getResourceModel('eav/entity_attribute')
            ->getFlatUpdateSelect($this->getAttribute(), $store);
    }
}