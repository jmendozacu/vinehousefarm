<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('customer_group'), 'minimal_order', 'varchar(100)');

$installer->endSetup();

