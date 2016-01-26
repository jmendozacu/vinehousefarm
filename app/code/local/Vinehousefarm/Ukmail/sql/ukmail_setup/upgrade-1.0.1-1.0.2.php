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

ALTER TABLE {$this->getTable('ukmail_labels')}
ADD COLUMN `collection_job_number` VARCHAR(128) NULL DEFAULT NULL COMMENT '' AFTER `consignment_number`;

");

$installer->endSetup();