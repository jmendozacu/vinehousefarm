<?php

require_once 'app/Mage.php';
umask(0);
Mage::app();
try {
    $csv = new Varien_File_Csv();
    $path = Mage::getBaseDir('var');
    $name ='clientname';
    $file = $path . DS . $name . '.csv';

    if (!file_exists($file)) {
        throw new Exception('File "'.$file.'" do not exists');
    }

    $fh = fopen($file, 'r');
    $numRow = 0;
    while (($rowData = fgetcsv($fh, 0, ',', '"')) !== FALSE) {
        if (!$numRow) {
            $numRow++;
            continue;
        }

        $clientId = trim($rowData[0]);
        $title = trim($rowData[1]);
        $firstName = trim($rowData[2]);
        $lastName = trim($rowData[3]);


        $customer = Mage::getModel('customer/customer')->getCollection()
            ->addFieldToFilter('clinet_id', $clientId)
            ->getFirstItem();

        if ($customer->getId()) {

            if ($title !== 'NULL') {
                $customer->setPrefix($title);
            }

            if ($lastName !== 'NULL') {
                $customer->setLastname($lastName);
            }

            if ($firstName !== 'NULL') {
                $customer->setFirstname($firstName);
            }

            if ($customer->getEmail()) {
                try {
                    $customer->save();
                    Mage::log(print_r($clientId, true), null,'f.log', true);
                } catch (Exception $e) {
                    echo $e->getMessage() . ' --- ' . $clientId;
                }
            }
        }

    }

    fclose($fh);

} catch (Exception $e) {
    echo $e->getMessage();
}