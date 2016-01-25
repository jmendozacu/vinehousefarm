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

$installer->getConnection()->addColumn($albumTable, 'size_use_parent_settings', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_size', 'varchar(20)');
$installer->getConnection()->addColumn($albumTable, 'album_grid_thumb_size', 'varchar(20)');
$installer->getConnection()->addColumn($albumTable, 'album_list_thumb_size', 'varchar(20)');
$installer->getConnection()->addColumn($albumTable, 'album_simple_thumb_size', 'varchar(20)');
$installer->getConnection()->addColumn($albumTable, 'photo_size', 'varchar(20)');
$installer->getConnection()->addColumn($albumTable, 'photo_grid_thumb_size', 'varchar(20)');
$installer->getConnection()->addColumn($albumTable, 'photo_list_thumb_size', 'varchar(20)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_thumb_size', 'varchar(20)');
$installer->getConnection()->addColumn($albumTable, 'photo_carousel_thumb_size', 'varchar(20)');

$installer->getConnection()->addColumn($photoTable, 'size_use_parent_settings', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_size', 'varchar(20)');
$installer->getConnection()->addColumn($photoTable, 'photo_carousel_thumb_size', 'varchar(20)');


$installer->endSetup();