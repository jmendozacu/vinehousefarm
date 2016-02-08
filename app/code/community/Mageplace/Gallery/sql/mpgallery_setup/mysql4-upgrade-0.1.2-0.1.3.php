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

$photoTable = $installer->getTable('mpgallery/photo');

$installer->startSetup();

$installer->getConnection()->addColumn($photoTable, 'design_use_parent_settings', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'design_custom', 'varchar(255)');
$installer->getConnection()->addColumn($photoTable, 'page_layout', 'varchar(255)');

$installer->endSetup();