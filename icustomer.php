<?php

require_once 'app/Mage.php';
umask(0);
Mage::app();
try {
    $csv = new Varien_File_Csv();
    $path = Mage::getBaseDir('var') . DS . 'export';
    $name ='vhcliebt';
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
        $fullname = trim($rowData[1]);
        $source = trim($rowData[15]);
        $totalspend = trim($rowData[41]);
        $sageref = trim($rowData[44]);
        $firstName = $fullname;
        $middleName = $fullname;
        $lastName = $fullname;

        $email = (trim($rowData[11]) !== 'NULL') ? $rowData[11] : "client" . $clientId . "@vinehousefarm.com";

        $address_city = (trim($rowData[4]) !== 'NULL') ? $rowData[4] : "";
        $address_county = (trim($rowData[5]) !== 'NULL') ? $rowData[5] : "";
        //$address_county = (trim($rowData[5]) !== '') ? $rowData[5] : "";

        $country = Mage::getModel('directory/country')->loadByCode('GB');

        $city = Mage::getModel('directory/region')->loadByName($address_city, $country->getId());
        $region = Mage::getModel('directory/region')->loadByName($address_county, $country->getId());

        //$address_country_id = (trim($val[24]) !== '') ? $val[24] : "";
        $address_postcode = (trim($rowData[6]) !== 'NULL') ? $rowData[6] : "";
        $address_street1 = (trim($rowData[2]) !== 'NULL') ? $rowData[2] : "";
        $address_street2 = (trim($rowData[3]) !== 'NULL') ? $rowData[3] : "";
        $address_telephone = (trim($rowData[7]) !== 'NULL') ? $rowData[7] : "";


        $customer = Mage::getModel('customer/customer');

        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());

        $customer->loadByEmail($email);

        if (!$customer->getId()) {

            $customer->setEmail($email);
            $customer->setFirstname($firstName);
            $customer->setMiddlename($middleName);
            $customer->setLastname($lastName);
            $customer->setClientId($clientId);
            $customer->setSource($source);
            $customer->setTotalSpend($totalspend);
            $newPassword = $customer->generatePassword(); // generate a new password
            $customer->setPassword($newPassword); // set it

        try {
            $customer->save();
            //$customer->setConfirmation(null);
            //$customer->save();
            //$customer->sendPasswordReminderEmail(); // save successful, send new password

            $_custom_address = array(
                'firstname' => $firstName,
                'lastname' => $lastName,
                'street' => array(
                    '0' => $address_street1,
                    '1' => $address_street2,
                ),
                'city' => $address_city,
                'region_id' => $region->getId(),
                'region' => $address_county,
                'postcode' => $address_postcode,
                'country_id' => $country->getId(),
                'telephone' => $address_telephone,
            );

            $customAddress = Mage::getModel('customer/address');

            $customAddress->setData($_custom_address)
                ->setCustomerId($customer->getId())
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');
            try {
                $customAddress->save();
            } catch (Exception $ex) {
                Zend_Debug::dump($ex->getMessage());
            }


        } catch (Exception $ex) {
            Zend_Debug::dump($ex->getMessage());
        }
    }
    }
    fclose($fh);

} catch (Exception $e) {
    echo $e->getMessage();
}