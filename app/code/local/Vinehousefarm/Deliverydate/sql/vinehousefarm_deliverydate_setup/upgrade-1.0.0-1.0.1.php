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

ALTER TABLE {$this->getTable('vinehousefarm_deliverydate')}
DROP COLUMN `content`,
ADD COLUMN `holiday_time` DATETIME NULL DEFAULT NULL AFTER `title`;

");

$installer->endSetup();