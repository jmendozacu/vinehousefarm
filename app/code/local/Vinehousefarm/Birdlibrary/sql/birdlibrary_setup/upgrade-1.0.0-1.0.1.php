<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()->dropForeignKey($installer->getTable('birdlibrary/bird_product'), 'FK_VINEHOUSEFARM_BIRD_PRD_BIRD_ID_VINEHOUSEFARM_BIRD_ENTT_ID');
$table = $installer->getConnection()->dropForeignKey($installer->getTable('birdlibrary/bird_product'), 'FK_VINEHOUSEFARM_BIRD_PRD_PRD_ID_CAT_PRD_ENTT_ENTT_ID');

$table = $installer->getConnection()->dropForeignKey($installer->getTable('birdlibrary/bird_link'), 'FK_VINEHOUSEFARM_BIRD_LINK_BIRD_ID_VINEHOUSEFARM_BIRD_ENTITY_ID');
$table = $installer->getConnection()->dropForeignKey($installer->getTable('birdlibrary/bird_link'), 'FK_VINEHOUSEFARM_BIRD_LINK_LINK_ID_VINEHOUSEFARM_BIRD_ENTITY_ID');

$installer->endSetup();