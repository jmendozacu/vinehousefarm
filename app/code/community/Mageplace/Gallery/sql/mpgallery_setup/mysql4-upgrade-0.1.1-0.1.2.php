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

$installer->getConnection()->addColumn($albumTable, 'short_description', 'text NOT NULL AFTER `url_key`');
$installer->getConnection()->addColumn($albumTable, 'album_default_sort_dir', 'varchar(4) AFTER `album_default_sort_by`');
$installer->getConnection()->addColumn($albumTable, 'photo_default_sort_dir', 'varchar(4) AFTER `photo_default_sort_by`');

$installer->getConnection()->addColumn($photoTable, 'short_description', 'text NOT NULL AFTER `url_key`');

$installer->endSetup();