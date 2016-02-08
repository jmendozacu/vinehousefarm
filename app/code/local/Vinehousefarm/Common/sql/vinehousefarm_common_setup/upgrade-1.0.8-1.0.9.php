<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

$installer = $this;

$installer->startSetup();

$installer->removeAttribute('catalog_product', 'video_link');

$installer->endSetup();

