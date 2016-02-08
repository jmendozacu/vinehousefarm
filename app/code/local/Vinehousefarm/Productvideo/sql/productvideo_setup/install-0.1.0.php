<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('productvideo/video'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Video Id')
    ->addColumn('video_code', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(), 'Video Code')
    ->addColumn('video_name', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(), 'Video Name')
    ->setComment('Video Library');

if (!$installer->getConnection()->isTableExists($installer->getTable('productvideo/video'))) {
    $installer->getConnection()->createTable($table);
}

$table = $installer->getConnection()
    ->newTable($installer->getTable('productvideo/video_product'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity Id')
    ->addColumn('video_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Bird ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Product ID')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Position')
    ->addIndex($installer->getIdxName('productvideo/video_product', array('product_id')),
        array('product_id'))
    ->addForeignKey($installer->getFkName('productvideo/video_product', 'video_id', 'productvideo/video',
        'entity_id'),
        'video_id', $installer->getTable('productvideo/video'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('productvideo/video_product', 'product_id', 'catalog/product',
        'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Video library To Product Linkage Table');

if (!$installer->getConnection()->isTableExists($installer->getTable('productvideo/video_product'))) {
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();