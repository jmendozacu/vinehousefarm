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

$coreStoreTable          = $installer->getTable('core/store');
$customerGroupTable      = $installer->getTable('customer_group');
$productTable            = $installer->getTable('catalog/product');

$albumTable              = $installer->getTable('mpgallery/album');
$albumCustomerGroupTable = $installer->getTable('mpgallery/album_customer_group');
$albumPhotoTable         = $installer->getTable('mpgallery/album_photo');
$albumProductTable       = $installer->getTable('mpgallery/album_product');
$albumStoreTable         = $installer->getTable('mpgallery/album_store');

$photoTable              = $installer->getTable('mpgallery/photo');
$photoStoreTable         = $installer->getTable('mpgallery/photo_store');
$photoCustomerGroupTable = $installer->getTable('mpgallery/photo_customer_group');

$date = Mage::getSingleton('core/date')->gmtDate();

$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS `$albumTable` (
	`album_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`url_key` varchar(255) NOT NULL,
	`image` varchar(255) NOT NULL DEFAULT '',
	`short_description` text NOT NULL,
	`description` text NOT NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT '0',
	`only_for_registered` tinyint(1) NOT NULL DEFAULT '0',
	`meta_keywords` text NOT NULL,
	`meta_description` text NOT NULL,
	`parent_id` int(10) unsigned NOT NULL DEFAULT '0',
	`path` varchar(255) NOT NULL,
	`level` int(11) DEFAULT '0',
	`position` int(11) DEFAULT '0',
	`children_count` int(11) DEFAULT '0',
	`creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`display_use_parent_settings` tinyint(1) DEFAULT NULL,
	`display_mode` tinyint(2) DEFAULT NULL,
	`display_order` tinyint(2) DEFAULT NULL,
	`cms_block` int(11) DEFAULT NULL,
	`album_display_type` tinyint(1) DEFAULT NULL,
	`album_available_sort_by` varchar(255) DEFAULT NULL,
	`album_default_sort_by` varchar(50) DEFAULT NULL,
	`album_default_sort_dir` varchar(4) DEFAULT NULL,
	`album_grid_column_count` tinyint(2) DEFAULT NULL,
	`album_simple_column_count` tinyint(2) DEFAULT NULL,
	`photo_display_type` tinyint(1) DEFAULT NULL,
	`photo_available_sort_by` varchar(255) DEFAULT NULL,
	`photo_default_sort_by` varchar(50) DEFAULT NULL,
	`photo_default_sort_dir` varchar(4) DEFAULT NULL,
	`photo_grid_per_page` int(5) DEFAULT NULL,
	`photo_grid_column_count` tinyint(2) DEFAULT NULL,
	`photo_grid_pager_limit` varchar(255) DEFAULT NULL,
	`photo_list_per_page` int(5) DEFAULT NULL,
	`photo_list_pager_limit` varchar(255) DEFAULT NULL,
	`photo_simple_per_page` int(5) DEFAULT NULL,
	`photo_simple_column_count` tinyint(2) DEFAULT NULL,
	`photo_simple_pager_limit` varchar(255) DEFAULT NULL,
	`design_use_parent_settings` tinyint(1) DEFAULT NULL,
	`design_custom` varchar(255) DEFAULT NULL,
	`page_layout` varchar(255) DEFAULT NULL,
	`photo_view_display_mode` tinyint(2) DEFAULT NULL,
	`photo_view_list_sort_by` varchar(50) DEFAULT NULL,
	`photo_view_list_sort_dir` varchar(4) DEFAULT NULL,
	`photo_view_list_per_page` int(5) DEFAULT NULL,
	`size_use_parent_settings` tinyint(1) DEFAULT NULL,
	`album_size` varchar(20) DEFAULT NULL,
	`album_grid_thumb_size` varchar(20) DEFAULT NULL,
	`album_list_thumb_size` varchar(20) DEFAULT NULL,
	`album_simple_thumb_size` varchar(20) DEFAULT NULL,
	`photo_size` varchar(20) DEFAULT NULL,
	`photo_grid_thumb_size` varchar(20) DEFAULT NULL,
	`photo_list_thumb_size` varchar(20) DEFAULT NULL,
	`photo_simple_thumb_size` varchar(20) DEFAULT NULL,
	`photo_carousel_thumb_size` varchar(20) DEFAULT NULL,
	`album_view_display_name` tinyint(1) DEFAULT NULL,
	`album_view_display_update_date` tinyint(1) DEFAULT NULL,
	`album_view_display_short_descr` tinyint(1) DEFAULT NULL,
	`album_view_display_descr` tinyint(1) DEFAULT NULL,
	`album_grid_display_name` tinyint(1) DEFAULT NULL,
	`album_list_display_name` tinyint(1) DEFAULT NULL,
	`album_simple_display_name` tinyint(1) DEFAULT NULL,
	`album_grid_display_short_descr` tinyint(1) DEFAULT NULL,
	`album_list_display_short_descr` tinyint(1) DEFAULT NULL,
	`album_simple_display_short_descr` tinyint(1) DEFAULT NULL,
	`album_grid_display_update_date` tinyint(1) DEFAULT NULL,
	`album_list_display_update_date` tinyint(1) DEFAULT NULL,
	`album_simple_display_update_date` tinyint(1) DEFAULT NULL,
	`album_grid_display_show_link` tinyint(1) DEFAULT NULL,
	`album_list_display_show_link` tinyint(1) DEFAULT NULL,
	`album_simple_display_show_link` tinyint(1) DEFAULT NULL,
	`photo_view_display_name` tinyint(1) DEFAULT NULL,
	`photo_view_display_update_date` tinyint(1) DEFAULT NULL,
	`photo_view_display_short_descr` tinyint(1) DEFAULT NULL,
	`photo_view_display_descr` tinyint(1) DEFAULT NULL,
	`photo_view_display_back_url` tinyint(1) DEFAULT NULL,
	`photo_grid_display_name` tinyint(1) DEFAULT NULL,
	`photo_list_display_name` tinyint(1) DEFAULT NULL,
	`photo_simple_display_name` tinyint(1) DEFAULT NULL,
	`photo_carousel_display_name` tinyint(1) DEFAULT NULL,
	`photo_grid_display_short_descr` tinyint(1) DEFAULT NULL,
	`photo_list_display_short_descr` tinyint(1) DEFAULT NULL,
	`photo_simple_display_short_descr` tinyint(1) DEFAULT NULL,
	`photo_carousel_display_short_descr` tinyint(1) DEFAULT NULL,
	`photo_grid_display_update_date` tinyint(1) DEFAULT NULL,
	`photo_list_display_update_date` tinyint(1) DEFAULT NULL,
	`photo_simple_display_update_date` tinyint(1) DEFAULT NULL,
	`photo_carousel_display_update_date` tinyint(1) DEFAULT NULL,
	`photo_grid_display_show_link` tinyint(1) DEFAULT NULL,
	`photo_list_display_show_link` tinyint(1) DEFAULT NULL,
	`photo_simple_display_show_link` tinyint(1) DEFAULT NULL,
	`photo_carousel_display_show_link` tinyint(1) DEFAULT NULL,
	`album_view_display_image` tinyint(1) DEFAULT NULL,
	`album_display_toolbar_top` tinyint(1) DEFAULT NULL,
	`album_display_toolbar_bottom` tinyint(1) DEFAULT NULL,
	`photo_display_toolbar_top` tinyint(1) DEFAULT NULL,
	`photo_display_toolbar_bottom` tinyint(1) DEFAULT NULL,
	PRIMARY KEY (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='Gallery Albums';

INSERT IGNORE INTO `$albumTable`
	(`album_id`, `name`, `url_key`, `is_active`, `path`, `creation_date`, `update_date`, `album_view_display_image`)
VALUES
	(" . Mageplace_Gallery_Model_Album::TREE_ROOT_ID . ", 'Gallery', 'gallery', '1', '1', '".$date."', '".$date."', 0);

CREATE TABLE IF NOT EXISTS `$albumCustomerGroupTable` (
	`album_id`	int(10) unsigned NOT NULL,
	`group_id`	smallint(5) unsigned NOT NULL,
	PRIMARY KEY (`album_id`,`group_id`),
	CONSTRAINT `FK_MPGALLERY_ALBUM_CUSTOMER_GROUP_ALBUM_ID`
	  FOREIGN KEY (`album_id`)
	  REFERENCES `$albumTable` (`album_id`)
	  ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_MPGALLERY_ALBUM_CUSTOMER_GROUP_GROUP_ID`
	  FOREIGN KEY (`group_id`)
	  REFERENCES `$customerGroupTable` (`customer_group_id`)
	  ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Gallery Albums and Customer Groups Relations';

CREATE TABLE IF NOT EXISTS `$albumProductTable` (
  `album_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`album_id`,`product_id`),
  KEY `FK_MPGALLERY_ALBUM_PRODUCT_PRODUCT_ID` (`product_id`),
  CONSTRAINT `FK_MPGALLERY_ALBUM_PRODUCT_ALBUM_ID` FOREIGN KEY (`album_id`) REFERENCES `$albumTable` (`album_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_MPGALLERY_ALBUM_PRODUCT_PRODUCT_ID` FOREIGN KEY (`product_id`) REFERENCES `$productTable` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB COMMENT='Gallery Albums and Products Relations';

CREATE TABLE IF NOT EXISTS `$albumStoreTable` (
	`album_id`  int(10) unsigned NOT NULL,
	`store_id`	smallint(5) unsigned NOT NULL,
	PRIMARY KEY (`album_id`,`store_id`),
	CONSTRAINT `FK_MPGALLERY_ALBUM_STORE_ALBUM_ID`
	  FOREIGN KEY (`album_id`)
	  REFERENCES `$albumTable` (`album_id`)
	  ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_MPGALLERY_ALBUM_STORE_STORE_ID`
      FOREIGN KEY (`store_id`)
      REFERENCES `$coreStoreTable` (`store_id`)
      ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Gallery Albums and Stores Relations';

INSERT IGNORE INTO `$albumStoreTable`
	(`album_id`, `store_id`)
VALUES
	(" . Mageplace_Gallery_Model_Album::TREE_ROOT_ID . ", 0);

CREATE TABLE IF NOT EXISTS `$photoTable` (
	`photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`url_key` varchar(255) NOT NULL,
	`image` varchar(255) NOT NULL DEFAULT '',
	`short_description` text NOT NULL,
	`description` text NOT NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT '0',
	`only_for_registered` tinyint(1) NOT NULL DEFAULT '0',
	`meta_keywords` text NOT NULL,
	`meta_description` text NOT NULL,
	`creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`design_use_parent_settings` tinyint(1) DEFAULT NULL,
	`design_custom` varchar(255) DEFAULT NULL,
	`page_layout` varchar(255) DEFAULT NULL,
	`display_use_parent_settings` tinyint(1) DEFAULT NULL,
	`photo_view_display_mode` tinyint(2) DEFAULT NULL,
	`photo_view_list_sort_by` varchar(50) DEFAULT NULL,
	`photo_view_list_sort_dir` varchar(4) DEFAULT NULL,
	`photo_view_list_per_page` int(5) DEFAULT NULL,
	`size_use_parent_settings` tinyint(1) DEFAULT NULL,
	`photo_size` varchar(20) DEFAULT NULL,
	`photo_carousel_thumb_size` varchar(20) DEFAULT NULL,
	`photo_view_display_name` tinyint(1) DEFAULT NULL,
	`photo_view_display_update_date` tinyint(1) DEFAULT NULL,
	`photo_view_display_short_descr` tinyint(1) DEFAULT NULL,
	`photo_view_display_descr` tinyint(1) DEFAULT NULL,
	`photo_view_display_back_url` tinyint(1) DEFAULT NULL,
	`photo_carousel_display_name` tinyint(1) DEFAULT NULL,
	`photo_carousel_display_short_descr` tinyint(1) DEFAULT NULL,
	`photo_carousel_display_update_date` tinyint(1) DEFAULT NULL,
	`photo_carousel_display_show_link` tinyint(1) DEFAULT NULL,
	PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8 COMMENT='Gallery Photos';

CREATE TABLE IF NOT EXISTS `$photoCustomerGroupTable` (
	`photo_id`	int(10) unsigned NOT NULL,
	`group_id`	smallint(5) unsigned NOT NULL,
	PRIMARY KEY (`photo_id`,`group_id`),
	CONSTRAINT `FK_MPGALLERY_PHOTO_CUSTOMER_GROUP_PHOTO_ID`
	  FOREIGN KEY (`photo_id`)
	  REFERENCES `$photoTable` (`photo_id`)
	  ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_MPGALLERY_PHOTO_CUSTOMER_GROUP_GROUP_ID`
	  FOREIGN KEY (`group_id`)
	  REFERENCES `$customerGroupTable` (`customer_group_id`)
	  ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Gallery Photos and Customer Groups Relations';

CREATE TABLE IF NOT EXISTS `$photoStoreTable` (
	`photo_id`  int(10) unsigned NOT NULL,
	`store_id`	smallint(5) unsigned NOT NULL,
	PRIMARY KEY (`photo_id`,`store_id`),
	CONSTRAINT `FK_MPGALLERY_PHOTO_STORE_PHOTO_ID`
	  FOREIGN KEY (`photo_id`)
	  REFERENCES `$photoTable` (`photo_id`)
	  ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_MPGALLERY_PHOTO_STORE_STORE_ID`
      FOREIGN KEY (`store_id`)
      REFERENCES `$coreStoreTable` (`store_id`)
      ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Gallery Photos and Stores Relations';

CREATE TABLE IF NOT EXISTS `$albumPhotoTable` (
	`album_id`  int(10) unsigned NOT NULL,
	`photo_id`  int(10) unsigned NOT NULL,
	`position`	int(11) DEFAULT 0,
	PRIMARY KEY (`album_id`,`photo_id`,`position`),
	CONSTRAINT `FK_MPGALLERY_ALBUM_PHOTO_ALBUM_ID`
      FOREIGN KEY (`album_id`)
      REFERENCES `$albumTable` (`album_id`)
      ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_MPGALLERY_ALBUM_PHOTO_PHOTO_ID`
	  FOREIGN KEY (`photo_id`)
	  REFERENCES `$photoTable` (`photo_id`)
	  ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Gallery Albums and Photos Relations';
");
$installer->endSetup();
