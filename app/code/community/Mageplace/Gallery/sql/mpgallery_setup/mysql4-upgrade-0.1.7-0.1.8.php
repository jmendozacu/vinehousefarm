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
CREATE TABLE {$this->getTable('mpgallery/album_product')} (
  `album_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`album_id`,`product_id`),
  CONSTRAINT `FK_MPGALLERY_ALBUM_PRODUCT_ALBUM_ID` FOREIGN KEY (`album_id`) REFERENCES {$this->getTable('mpgallery/album')} (`album_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_MPGALLERY_ALBUM_PRODUCT_PRODUCT_ID` FOREIGN KEY (`product_id`) REFERENCES {$this->getTable('catalog/product')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB COMMENT='Gallery Albums and Products Relations';
");

$installer->endSetup();