<?php
/**
 * @package UK Mail.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Service_Labels extends Vinehousefarm_Ukmail_Model_Service_Abstract
{
    /**
     * @var string
     */
    protected $wsdl = 'Services/UKMAuthenticationServices/UKMAuthenticationService.svc?wsdl';

    public function getLabel()
    {
        $request = new stdClass();

        $request->AuthenticationToken = $this->getClient()->getToken();
        $request->Username = $this->getClient()->getUsername();
        $request->AccountNumber = $this->getClient()->getAccount()->AccountNumber;

        /**
         * @var $order Mage_Sales_Model_Order
         */
        $order = $this->getOrder();
        $address = new stdClass();

        $address->Address1 = $order->getShippingAddress()->getStreetFull();
        $address->PostalTown = $order->getShippingAddress()->getCity();
        $address->Postcode = $order->getShippingAddress()->getPostcode();
        $address->CountryCode = $order->getShippingAddress()->getCountry();

        $request->Address = $address;

        $request->PreDeliveryNotification = 'NonRequired';

        $addressOrder = $order->getShippingAddress();


    }
}