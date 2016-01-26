<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$data = array(
    'Gardman',
    'Chapelwood',
    'Wildlife World',
    'Droll Yankee',
    'Meripac',
    'Vivara',
    'Vine House Farm',
    'Bird Lovers',
    'Creative Tops',
    'DK Publishing',
    'Gardenature',
    'Jacobi Jayne',
    'Kernel Feeders',
    'Unipet',
    'Vetark Professional',
    'RSPB',
    'The Nuttery',
    'Schwegler',
    'Oakdale',
    'Cascade Products',
    'C J Wildlife',
    'FSC Publications',
    'Geoff Gadd',
    'Harper Collins Publishers',
    'Jill Rogers Associates',
    'K T Nestboxes',
    'Keycraft',
    'Landlife Wildflowers Ltd',
    'Livefoods Direct Ltd',
    'Solus Garden & Leisure Ltd',
);
$installer->addAttributeOptions('manufacturer', $data);

$installer->endSetup();