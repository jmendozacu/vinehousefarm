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

$installer->getConnection()->addColumn($albumTable, 'album_display_toolbar_top', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_display_toolbar_bottom', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'album_view_display_image', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_view_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_view_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_view_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_view_display_descr', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'album_grid_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_list_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_simple_display_name', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'album_grid_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_list_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_simple_display_short_descr', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'album_grid_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_list_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_simple_display_update_date', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'album_grid_display_show_link', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_list_display_show_link', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'album_simple_display_show_link', 'tinyint(1)');


$installer->getConnection()->addColumn($albumTable, 'photo_display_toolbar_top', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_display_toolbar_bottom', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'photo_view_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_view_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_view_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_view_display_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_view_display_back_url', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'photo_grid_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_list_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_carousel_display_name', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'photo_grid_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_list_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_carousel_display_short_descr', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'photo_grid_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_list_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_carousel_display_update_date', 'tinyint(1)');

$installer->getConnection()->addColumn($albumTable, 'photo_grid_display_show_link', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_list_display_show_link', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_display_show_link', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_carousel_display_show_link', 'tinyint(1)');


$installer->getConnection()->addColumn($photoTable, 'photo_view_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_view_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_view_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_view_display_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_view_display_back_url', 'tinyint(1)');

$installer->getConnection()->addColumn($photoTable, 'photo_carousel_display_name', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_carousel_display_update_date', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_carousel_display_short_descr', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_carousel_display_show_link', 'tinyint(1)');

$installer->endSetup();