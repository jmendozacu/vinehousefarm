<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ukmail_postcode')};
CREATE TABLE {$this->getTable('ukmail_postcode')} (
  `entity_id` INT NOT NULL auto_increment,
  `postcode` VARCHAR(10) NULL COMMENT '',
  `effective_date_from` DATETIME NULL COMMENT '',
  `effective_date_to` DATETIME NULL COMMENT '',
  `locality` VARCHAR(50) NULL COMMENT '',
  `town` VARCHAR(50) NULL COMMENT '',
  `county` VARCHAR(50) NULL COMMENT '',
  `country` VARCHAR(50) NULL COMMENT '',
  `has_9am` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_1030am` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_am` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_next_day` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_pm` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_evening` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_48hr` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_saturday` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_saturday_9am` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_saturday_1030am` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `has_pallets` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `lnt_time` VARCHAR(10) NULL COMMENT '',
  `lct_time` VARCHAR(10) NULL COMMENT '',
  `primary_sort` VARCHAR(10) NULL COMMENT '',
  `secondary _sort` VARCHAR(30) NULL COMMENT '',
  `location_name` VARCHAR(30) NULL COMMENT '',
  PRIMARY KEY (`entity_id`)  COMMENT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('ukmail_country')};
CREATE TABLE {$this->getTable('ukmail_country')} (
  `entity_id` INT NOT NULL auto_increment,
  `code` VARCHAR(5) NULL COMMENT '',
  `name` VARCHAR(128) NULL COMMENT '',
  PRIMARY KEY (`entity_id`)  COMMENT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$installer->endSetup();