<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2009 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : Olivier ZIMMERMANN
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
$installer=$this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();
			
//rajoute l'attribut purchase_tva_rate au produit
$installer->addAttribute('catalog_product','purchase_tax_rate', array(
															'type' 		=> 'int',
															'visible' 	=> false,
															'label'		=> 'Purchase Tax Rate',
															'required'  => false,
															'global'       => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL
															));
		