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

	CREATE TABLE IF NOT EXISTS `{$installer->getTable('oldorders/oldorders')}` (
	  `entity_id` int(10) unsigned NOT NULL auto_increment,
	  `client_id` int(10) NULL DEFAULT NULL,
	  `order_date` DATETIME NULL,
	  `delivery_date` DATETIME NULL,
	  `payment` VARCHAR(255) NULL,
	  `delivery_name` VARCHAR(255) NULL,
	  `delivery_address1` VARCHAR(255) NULL,
	  `delivery_address2` VARCHAR(255) NULL,
	  `delivery_town` VARCHAR(255) NULL,
	  `delivery_county` VARCHAR(255) NULL,
	  `delivery_postcode` VARCHAR(255) NULL,
	  `delivery_tel` VARCHAR(255) NULL,
	  `total_ex_vat` FLOAT NULL,
	  `total_vat` FLOAT NULL,
	  `total_final` FLOAT NULL,
	  PRIMARY KEY  (`entity_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();