<?php
/**
 * @package Default (Template) Project.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

	CREATE TABLE IF NOT EXISTS `{$installer->getTable('oldorders/products')}` (
	  `entity_id` int(10) unsigned NOT NULL auto_increment,
	  `order_id` int(10) NULL DEFAULT NULL,
	  `description` VARCHAR(255) NULL,
	  `sku` VARCHAR(255) NULL,
	  PRIMARY KEY  (`entity_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();