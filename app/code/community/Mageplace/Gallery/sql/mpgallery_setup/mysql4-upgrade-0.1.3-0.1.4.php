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
$photoTable = $installer->getTable('mpgallery/photo');

$installer->startSetup();

$installer->getConnection()->addColumn($albumTable, 'photo_view_display_mode', 'tinyint(2)');
$installer->getConnection()->addColumn($albumTable, 'photo_view_list_sort_by', 'varchar(255)');
$installer->getConnection()->addColumn($albumTable, 'photo_view_list_sort_dir', 'varchar(4)');
$installer->getConnection()->addColumn($albumTable, 'photo_view_list_per_page', 'int(5)');

$installer->getConnection()->addColumn($photoTable, 'display_use_parent_settings', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_view_display_mode', 'tinyint(2)');
$installer->getConnection()->addColumn($photoTable, 'photo_view_list_sort_by', 'varchar(255)');
$installer->getConnection()->addColumn($photoTable, 'photo_view_list_sort_dir', 'varchar(4)');
$installer->getConnection()->addColumn($photoTable, 'photo_view_list_per_page', 'int(5)');

$installer->endSetup();