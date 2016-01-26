<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('shipping_premiumzone')}
CHANGE COLUMN `dest_city` `dest_city` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '' ;

");


$installer->endSetup();

