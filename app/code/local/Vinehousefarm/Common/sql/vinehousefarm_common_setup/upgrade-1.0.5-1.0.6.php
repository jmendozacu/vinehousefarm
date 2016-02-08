<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->addAttribute('catalog_product', 'supplier', array(
    'group'             => 'Product Supplier',
    'label'             => 'Supplier',
    'note'              => '',
    'type'              => 'int',
    'input'             => 'select',
    'frontend_class'	=> '',
    'source'			=> 'vinehousefarm_common/source_supplier',
    'backend'           => '',
    'frontend'          => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'required'          => false,
    'visible_on_front'  => false,
    'apply_to'          => 'simple,configurable',
    'is_configurable'   => false,
    'used_in_product_listing'	=> false,
    'sort_order'        => 5,
));

$installer->endSetup();