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

$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('mpgallery/review')}` (
  `review_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
  `photo_id`      int(10) unsigned NOT NULL,
  `email`         varchar(255) NOT NULL,
  `name`          varchar(255) NOT NULL,
  `comment`       text NOT NULL,
  `rate`          smallint(4) unsigned NOT NULL,
  `status`        tinyint(1) NOT NULL DEFAULT 0,
  `creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `update_date`   datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`review_id`),
  CONSTRAINT `FK_MPGALLERY_REVIEW_PHOTO_ID` FOREIGN KEY (`photo_id`) REFERENCES `{$this->getTable('mpgallery/photo')}` (`photo_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='Gallery Reviews';
");

$albumTable = $installer->getTable('mpgallery/album');
$photoTable = $installer->getTable('mpgallery/photo');

$installer->getConnection()->addColumn($albumTable, 'photo_view_display_review', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_grid_display_rate', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_list_display_rate', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_simple_display_rate', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'photo_carousel_display_rate', 'tinyint(1)');
$installer->getConnection()->addColumn($albumTable, 'page_title', 'varchar(255) NOT NULL');

$installer->getConnection()->addColumn($photoTable, 'photo_view_display_review', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'photo_carousel_display_rate', 'tinyint(1)');
$installer->getConnection()->addColumn($photoTable, 'author_name', 'varchar(255) NOT NULL');
$installer->getConnection()->addColumn($photoTable, 'author_email', 'varchar(255) NOT NULL');
$installer->getConnection()->addColumn($photoTable, 'customer_id', 'int(10) unsigned');
$installer->getConnection()->addColumn($photoTable, 'page_title', 'varchar(255) NOT NULL');
$installer->getConnection()->addColumn($photoTable, 'content_heading', 'varchar(255) NOT NULL');

$installer->endSetup();