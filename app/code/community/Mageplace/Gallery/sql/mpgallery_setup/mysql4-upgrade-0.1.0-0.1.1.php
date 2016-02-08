<?php
/**
 * MagePlace Gallery Extension
 *
 * @category    Mageplace_Gallery
 * @package     Mageplace_Gallery
 * @copyright   Copyright (c) 2014 Mageplace. (http://www.mageplace.com)
 * @license     http://www.mageplace.com/disclaimer.html
 */

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$albumTable = $installer->getTable('mpgallery/album');

$installer->startSetup();

$installer->getConnection()->addColumn($albumTable, 'display_use_parent_settings', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'display_mode', 'tinyint(2)');
$installer->getConnection()->addColumn($albumTable, 'display_order', 'tinyint(2)');
$installer->getConnection()->addColumn($albumTable, 'cms_block', 'int(11)');
$installer->getConnection()->addColumn($albumTable, 'album_display_type', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_available_sort_by', 'varchar(255)');
$installer->getConnection()->addColumn($albumTable, 'album_default_sort_by', 'tinyint(2)');
$installer->getConnection()->addColumn($albumTable, 'album_grid_column_count', 'tinyint(2)');
$installer->getConnection()->addColumn($albumTable, 'album_simple_column_count', 'tinyint(2)');
$installer->getConnection()->addColumn($albumTable, 'photo_display_type', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_available_sort_by', 'varchar(255)');
$installer->getConnection()->addColumn($albumTable, 'photo_default_sort_by', 'varchar(255)');
$installer->getConnection()->addColumn($albumTable, 'photo_grid_per_page', 'int(5)');
$installer->getConnection()->addColumn($albumTable, 'photo_grid_column_count', 'tinyint(2)');
$installer->getConnection()->addColumn($albumTable, 'photo_grid_pager_limit', 'varchar(255)');
$installer->getConnection()->addColumn($albumTable, 'photo_list_per_page', 'int(5)');
$installer->getConnection()->addColumn($albumTable, 'photo_list_pager_limit', 'varchar(255)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_per_page', 'int(5)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_column_count', 'tinyint(2)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_pager_limit', 'varchar(255)');
$installer->getConnection()->addColumn($albumTable, 'design_use_parent_settings', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'design_custom', 'varchar(255)');
$installer->getConnection()->addColumn($albumTable, 'page_layout', 'varchar(255)');

$installer->endSetup();