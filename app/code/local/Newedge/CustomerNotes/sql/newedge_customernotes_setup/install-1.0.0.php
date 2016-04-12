<?php
$installer = new Mage_Customer_Model_Entity_Setup('core_setup');

$installer->startSetup();

$vCustomerEntityType = $installer->getEntityTypeId('customer');
$vCustAttributeSetId = $installer->getDefaultAttributeSetId($vCustomerEntityType);
$vCustAttributeGroupId = $installer->getDefaultAttributeGroupId($vCustomerEntityType, $vCustAttributeSetId);

$installer->addAttribute('customer', 'customernotes', array(
	'label' => 'Customer Notes',
	'input' => 'textarea',
	'type'  => 'text',
	'forms' => array('customer_account_edit','customer_account_create','adminhtml_customer', 'adminhtml_checkout'),
	'required' => 0,
	'user_defined' => 1,
));

$installer->addAttributeToGroup($vCustomerEntityType, $vCustAttributeSetId, $vCustAttributeGroupId, 'customernotes', 0);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'customernotes');
$oAttribute->setData('used_in_forms', array('customer_account_edit','customer_account_create','adminhtml_customer', 'adminhtml_checkout'));
$oAttribute->setData("is_used_for_customer_segment", true)
	->setData("is_system", 0)
	->setData("is_user_defined", 1)
	->setData("is_visible", 1)
	->setData("sort_order", 100);
$oAttribute->save();

$installer->endSetup();