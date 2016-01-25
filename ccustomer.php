<?php

require_once 'app/Mage.php';
umask(0);
Mage::app();
try {

    $customers = Mage::getModel('customer/customer')->getCollection()
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('clinet_id');

    /**
     * @var $customer $customer Mage_Customer_Model_Customer
     */
    foreach ($customers as $customer) {
        if ($customer->getId() > 2) {
            if ($customer->getFirstname() == 'NULL') {
                $customer->setFirstname('Client');
            }
            $customer->setLastname('(' .$customer->getClinetId() . ')');
            $customer->setMiddlename('');
            $customer->save();
        }
    }

} catch (Exception $e) {
    echo $e->getMessage();
}