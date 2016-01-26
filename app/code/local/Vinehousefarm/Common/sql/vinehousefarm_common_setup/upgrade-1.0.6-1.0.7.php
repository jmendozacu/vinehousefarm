<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

$installer = $this;

$installer->startSetup();

$attr = array (
    'group' => 'Product Video',
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Video',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '1',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '0',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'option' =>
        array (
            'values' =>
                array (
                ),
        ),
);

$installer->addAttribute('catalog_product', 'video_link', $attr);

$attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', 'video_link');
$attribute->setStoreLabels(array (
    1 => 'Video',
));
$attribute->save();

$installer->endSetup();

