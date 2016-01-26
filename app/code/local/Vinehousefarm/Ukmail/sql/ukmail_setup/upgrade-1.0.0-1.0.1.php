<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ukmail_labels')};
CREATE TABLE {$this->getTable('ukmail_labels')} (
  `entity_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `order_id` INT(10) NOT NULL COMMENT '',
  `consignment_number` VARCHAR(128) NULL COMMENT '',
  PRIMARY KEY (`entity_id`)  COMMENT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();