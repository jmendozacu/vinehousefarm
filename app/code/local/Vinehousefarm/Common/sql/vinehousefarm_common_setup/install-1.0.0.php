<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = new Mage_Customer_Model_Entity_Setup('core_setup');

$installer->startSetup();

$vCustomerEntityType = $installer->getEntityTypeId('customer');
$vCustAttributeSetId = $installer->getDefaultAttributeSetId($vCustomerEntityType);
$vCustAttributeGroupId = $installer->getDefaultAttributeGroupId($vCustomerEntityType, $vCustAttributeSetId);

$installer->addAttribute('customer', 'sageref', array(
    'label' => 'SAGEREF',
    'input' => 'text',
    'type'  => 'varchar',
    'forms' => array('customer_account_edit','customer_account_create','adminhtml_customer','checkout_register'),
    'required' => 0,
    'user_defined' => 1,
));

$installer->addAttributeToGroup($vCustomerEntityType, $vCustAttributeSetId, $vCustAttributeGroupId, 'sageref', 0);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'sageref');
$oAttribute->setData('used_in_forms', array('customer_account_edit','customer_account_create','adminhtml_customer','checkout_register'));
$oAttribute->save();

$installer->endSetup();