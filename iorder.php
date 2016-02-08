<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */


require_once 'app/Mage.php';
umask(0);
Mage::app();
try {

    $csv = new Varien_File_Csv();
    $path = Mage::getBaseDir('var') . DS . 'export';
    $name ='vhorders_test';
    $file = $path . DS . $name . '.csv';

    if (!file_exists($file)) {
        throw new Exception('File "'.$file.'" do not exists');
    }

    $fh = fopen($file, 'r');
    $numRow = 0;
    $currentOrderId = 0;

    $websiteId = Mage::app()->getWebsite()->getId();
    $store = Mage::app()->getStore();

    while (($rowData = fgetcsv($fh, 0, ',', '"')) !== FALSE) {
        if (!$numRow) {
            $numRow++;
            continue;
        }

        $orderId = $rowData[1];

        if ($currentOrderId != $orderId) {

            if ($currentOrderId != 0) {
                //save

                $shippingAddress->setCollectShippingRates(true)
                    ->collectShippingRates()
                    ->setShippingMethod('freeshipping_freeshipping')
                    ->setPaymentMethod('checkmo');

                $quote->getPayment()->importData(array('method' => 'checkmo'));
                $quote->setCouponCode('code' . $currentOrderId);

                $quote->setTotalsCollectedFlag(false);
                $quote->collectTotals()->save();

                $service = Mage::getModel('sales/service_quote', $quote);
                $service->submitAll();
            }

            $currentOrderId = $orderId;
            $currentCustomer = array();
            $currentOrder = array();

            $quote = $customer = $service = null;

            $quote = Mage::getModel('sales/quote')->setStoreId($store->getId());
            $quote->setCurrency($order->AdjustmentAmount->currencyID);

            if ($rowData[20] !== 'NULL') {
                $email = $rowData[20];
            } else {
                $email = "client" . $rowData[7] . "@vinehousefarm.com";
            }

            $customer = Mage::getModel('customer/customer')->getCollection()
                ->addFieldToFilter('clinet_id', $rowData[7])
                ->getFirstItem();

            if (!$customer->getId()) {
                $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId($websiteId)
                    ->loadByEmail($email);
            }

            if (!$customer->getId()) {
                $customer = Mage::getModel('customer/customer');
                $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($rowData[9])
                    ->setLastname($rowData[9])
                    ->setClinetId($rowData[7])
                    ->setEmail($email);

                $newPassword = $customer->generatePassword(); // generate a new password
                $customer->setPassword($newPassword); // set it

            }

            $customer->setFirstname($rowData[9])
                ->setLastname($rowData[9])
                ->save();

            if ($rowData['21'] > 0) {

                $customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
                $rule = Mage::getModel('salesrule/rule');

                $rule->setName('Rule order' . $currentOrderId)
                    ->setDescription('Rule order' . $currentOrderId)
                    ->setFromDate('')
                    ->setCouponType(Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
                    ->setCouponCode('code' . $currentOrderId)
                    ->setUsesPerCustomer(1)
                    ->setUsesPerCoupon(1)
                    ->setCustomerGroupIds($customerGroupIds)
                    ->setIsActive(1)
                    ->setConditionsSerialized('')
                    ->setActionsSerialized('')
                    ->setStopRulesProcessing(0)
                    ->setIsAdvanced(1)
                    ->setProductIds('')
                    ->setSortOrder(0)
                    ->setSimpleAction(Mage_SalesRule_Model_Rule::BY_FIXED_ACTION)
                    ->setDiscountAmount($rowData['21'])
                    ->setDiscountQty(1)
                    ->setDiscountStep(0)
                    ->setSimpleFreeShipping('0')
                    ->setApplyToShipping('0')
                    ->setIsRss(0)
                    ->setWebsiteIds(array(1))
                    ->setStoreLabels(array('Order Rule ' . $currentOrderId));

                $productFoundCondition = Mage::getModel('salesrule/rule_condition_product_found')
                    ->setType('salesrule/rule_condition_product_found')
                    ->setValue(1)               // 0 == not found, 1 == found
                    ->setAggregator('all');     // match all conditions

                $rule->getConditions()->addCondition($productFoundCondition);

                $rule->save();
            }

            $quote->assignCustomer($customer);

            $quote->setSendCconfirmation(1);

            $address_city = (trim($rowData[13]) !== 'NULL') ? $rowData[13] : "London";
            $address_county = (trim($rowData[14]) !== 'NULL') ? $rowData[14] : "London";
            //$address_county = (trim($rowData[5]) !== '') ? $rowData[5] : "";

            $country = Mage::getModel('directory/country')->loadByCode('GB');

            $city = Mage::getModel('directory/region')->loadByName($address_city, $country->getId());
            $region = Mage::getModel('directory/region')->loadByName($address_county, $country->getId());

            //$address_country_id = (trim($val[24]) !== '') ? $val[24] : "";
            $address_postcode = (trim($rowData[15]) !== 'NULL') ? $rowData[15] : "123456";
            $address_street1 = (trim($rowData[11]) !== 'NULL') ? $rowData[11] : "";
            $address_street2 = (trim($rowData[12]) !== 'NULL') ? $rowData[12] : "";
            $address_telephone = (trim($rowData[16]) !== 'NULL') ? $rowData[16] : "123456";

            $_custom_address = array(
                'firstname' => $rowData[9],
                'lastname' => $rowData[9],
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

            $billingAddress = $quote->getBillingAddress()->addData(array(
                'customer_address_id' => '',
                'prefix' => '',
                'firstname' => $rowData[9],
                'middlename' => '',
                'lastname' => $rowData[9],
                'suffix' => '',
                'company' =>'',
                'street' => array(
                    '0' => $address_street1,
                    '1' => $address_street2
                ),
                'city' => $address_city,
                'country_id' => $country->getId(),
                'region' => $address_county,
                'postcode' => $address_postcode,
                'telephone' => $address_telephone,
                'fax' => '',
                'vat_id' => '',
                'save_in_address_book' => 1
            ));

            $shippingAddress = $quote->getShippingAddress()->addData(array(
                'customer_address_id' => '',
                'prefix' => '',
                'firstname' => $rowData[9],
                'middlename' => '',
                'lastname' => $rowData[9],
                'suffix' => '',
                'company' =>'',
                'street' => array(
                    '0' => $address_street1,
                    '1' => $address_street2
                ),
                'city' => $address_city,
                'country_id' => $country->getId(),
                'region' => $address_county,
                'postcode' => $address_postcode,
                'telephone' => $address_telephone,
                'fax' => '',
                'vat_id' => '',
                'save_in_address_book' => 1
            ));
        }

        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $rowData[2]);

        if (!$product) {
            $product = Mage::getModel('catalog/product')
                ->setWebsiteIds(array($websiteId)) //website ID the product is assigned to, as an array
                ->setAttributeSetId(9) //ID of a attribute set named 'default'
                ->setTypeId('simple') //product type

                ->setSku($rowData[2]) //SKU
                ->setName('For order ' . $currentOrderId) //product name
                ->setWeight(1.0000)
                ->setStatus(1) //product status (1 - enabled, 2 - disabled)
                ->setTaxClassId(2) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) //catalog and search visibility

                ->setPrice($rowData[4]) //price in form 11.22
                ->setCost($rowData[4]) //price in form 11.22

                ->setMetaTitle('For order ' . $currentOrderId)
                ->setMetaKeyword('For order ' . $currentOrderId)
                ->setMetaDescription('For order ' . $currentOrderId)

                ->setDescription('For order ' . $currentOrderId)
                ->setShortDescription('For order ' . $currentOrderId)

                ->setStockData(array(
                        'use_config_manage_stock' => 0, //'Use config settings' checkbox
                        'manage_stock'=>0, //manage stock
                        'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
                        'max_sale_qty'=>10000, //Maximum Qty Allowed in Shopping Cart
                        'is_in_stock' => 0, //Stock Availability
                        'qty' => 0 //qty
                    )
                );

            $product->save();
        }

        if ($product) {

            $product = Mage::getModel('catalog/product')->load($product->getId());
            $product->setSpecialPrice($rowData[4]);

            $quoteItem = $quote->addProduct($product, new Varien_Object(array('qty' => $rowData[3])));
        } else {
            Mage::log('Invalid product - ' . $rowData[2] . ' - (Order:' . $currentOrderId .  ')', null, 'import_orders');
            $currentOrderId = 0;
        }

    }

    fclose($fh);

} catch (Exception $e) {
    echo $e->getMessage();
}