<?php

require_once 'app/Mage.php';
umask(0);
Mage::app();
try {

    $customers = Mage::getModel('customer/customer')->getCollection()
        ->addAttributeToSelect(array('firstname', 'lastname'))
        ->addAttributeToFilter('entity_id', array('gt' => 52701));

    $customers->setPageSize(100);

    $pages = $customers->getLastPageNumber();
    $currentPage = 1;

    do {
        $customers->setCurPage($currentPage);
        $customers->load();

        foreach ($customers as $customer) {
            $addresses = $customer->getAddressesCollection();

            /**
             * @var $address Mage_Customer_Model_Address
             */
            foreach ($addresses as $address) {
                $address->setFirstname($customer->getFirstname());
                $address->setLastname($customer->getLastname());
                $address->save();
                Mage::log('Customer: ' . $customer->getId(), null, 'fix-name.log');
            }
        }


        $currentPage++;
        //clear collection and free memory
        $customers->clear();
    } while ($currentPage <= $pages);


} catch (Exception $e) {
    echo $e->getMessage();
}