<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('birdlibrary/bird'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Bird Id')
    ->addColumn('bird_name', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(), 'Bird Name')
    ->addColumn('latin_name', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(), 'Latin Name')
    ->addColumn('family', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(), 'Family bird')
    ->addColumn('overview', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Overview')
    ->addColumn('distribution_map', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(), 'Distribution Map and Info')
    ->addColumn('habitat', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Habitat')
    ->addColumn('population', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'UK Breeding Population')
    ->addColumn('breeding', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Breeding')
    ->addColumn('food_diet', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Food/Diet')
    ->addColumn('trends', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Trends')
    ->addColumn('behaviour', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Behaviour')
    ->addColumn('audio_file', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Song/Audio Files')
    ->addColumn('video_file', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Video excerpts')
    ->addColumn('links', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Link to seed/feeder category')
    ->addColumn('in_garden', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(), 'Seen in VHF Garden')
    ->addColumn('similar_birds', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Similar birds / birds in the same family?')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Main image')
    ->setComment('Bird Library');
if (!$installer->getConnection()->isTableExists($installer->getTable('birdlibrary/bird'))) {
    $installer->getConnection()->createTable($table);
}

$table = $installer->getConnection()
    ->newTable($installer->getTable('birdlibrary/bird_product'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity Id')
    ->addColumn('bird_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
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
    ->addIndex($installer->getIdxName('birdlibrary/bird_product', array('product_id')),
        array('product_id'))
    ->addForeignKey($installer->getFkName('birdlibrary/bird_product', 'bird_id', 'birdlibrary/bird',
        'entity_id'),
        'bird_id', $installer->getTable('birdlibrary/bird'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('birdlibrary/bird_product', 'product_id', 'catalog/product',
        'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Bird library To Product Linkage Table');

if (!$installer->getConnection()->isTableExists($installer->getTable('birdlibrary/bird_product'))) {
    $installer->getConnection()->createTable($table);
}

$table = $installer->getConnection()
    ->newTable($installer->getTable('birdlibrary/bird_link'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity Id')
    ->addColumn('bird_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Bird ID')
    ->addColumn('link_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Link ID')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Position')
    ->addIndex($installer->getIdxName('birdlibrary/bird_link', array('link_id')),
        array('link_id'))
    ->addForeignKey($installer->getFkName('birdlibrary/bird_link', 'bird_id', 'birdlibrary/bird',
        'entity_id'),
        'bird_id', $installer->getTable('birdlibrary/bird'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('birdlibrary/bird_link', 'link_id', 'birdlibrary/bird',
        'entity_id'),
        'link_id', $installer->getTable('birdlibrary/bird'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Bird library To Bird Linkage Table');

if (!$installer->getConnection()->isTableExists($installer->getTable('birdlibrary/bird_link'))) {
    $installer->getConnection()->createTable($table);
}

$table = $installer->getConnection()
    ->newTable($installer->getTable('birdlibrary/bird_gallery'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity Id')
    ->addColumn('bird_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Bird ID')
    ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Image url')
    ->addColumn('file', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Image file')
    ->addColumn('label', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Link ID')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Position')
    ->addColumn('disabled', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Execute')
    ->setComment('Bird library To Bird Linkage Table');

if (!$installer->getConnection()->isTableExists($installer->getTable('birdlibrary/bird_gallery'))) {
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();