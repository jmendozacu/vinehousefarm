<?php

$installer = $this;

$installer->startSetup();
//Add new field to the order
//Will store the source as text to maintain integrity incase the source is deleted by admin
$installer->getConnection()->addColumn(
	$this->getTable("sales/order"),
	'order_moto_source',
	"varchar(255) not null default 'Website'"
);

$installer->getConnection()->addColumn(
	$this->getTable("sales_flat_quote"),
	'order_moto_source',
	"varchar(255) not null default 'Website'"
);

//add key to field to speed up searching if search by source is ever implemented.
$installer->getConnection()->addKey(
	$this->getTable("sales/order"),
	'order_moto_source',
	'order_moto_source'
);

$installer->getConnection()->addKey(
	$this->getTable("sales_flat_quote"),
	'order_moto_source',
	'order_moto_source'
);

$sales_setup =  new Mage_Sales_Model_Mysql4_Setup('sales_setup');

$attribute  = array(
	'type'          => 'varchar',
	'backend_type' => 'varchar',
	'frontend_input' => 'varchar',
	'label'         => 'Order Source',
	'default'       => '',
	'visible'       => false,
	'required'      => false,
	'searchable'    => false,
	'filterable'    => false,
	'comparable'    => false );

$sales_setup->addAttribute('quote', 'order_moto_source', $attribute);

$sales_setup->addAttribute('order', 'order_moto_source', $attribute);


//
//$installer->addAttribute('order', 'order_moto_source', array(
//	'label' => 'MOTO Source',
//	'default' => '',
//	'type' => 'varchar',
//	'input' => 'text',
//	'visible' => true,
//	'required' => false,
//	'position' => 1,
//	'visible_on_front'  => false,
//	'default' => array('')
//));


//need to create the table of sources.
$table = $installer->getConnection()
	->newTable($installer->getTable('newedge_ordersource/source'))
	->addColumn(
		'order_moto_source_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
		array(
			'identity' => true,
			'unsigned' => true,
			'nullable' => false,
			'primary'  => true,
		), 'Unique identifier'
	)
	->addColumn(
		'title', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(), 'Order Source'
	);

if (!$installer->getConnection()->isTableExists($table->getName())) {
	$installer->getConnection()->createTable($table);
}

$this->endSetup();