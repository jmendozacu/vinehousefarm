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
$installer->getConnection()->addColumn($albumTable, 'image', 'varchar(255) NOT NULL AFTER `url_key`');
$installer->getConnection()->addColumn($photoTable, 'image', 'varchar(255) NOT NULL AFTER `url_key`');
$installer->endSetup();