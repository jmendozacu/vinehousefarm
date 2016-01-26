<?php
/**
 * @package UK Mail.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Service_Label extends Vinehousefarm_Ukmail_Model_Service_Abstract
{
    /**
     * @var string
     */
    protected $wsdl = 'Services/UKMConsignmentServices/UKMConsignmentService.svc?wsdl';

    /**
     * @var Vinehousefarm_Ukmail_Model_Service_Book
     */
    protected $bookCollection;

    /**
     * @var string
     */
    protected $consignmentNumber = '';

    /**
     * @var array
     */
    protected $labels = array();

    /**
     * @param array $labels
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param mixed $consignmentNumber
     */
    public function setConsignmentNumber($consignmentNumber)
    {
        if ($this->getOrder()) {
            $label = Mage::getModel('ukmail/label');

            $label->setOrderId($this->getOrder()->getId());
            $label->setConsignmentNumber($consignmentNumber);
            $label->setCollectionJobNumber($this->getBookCollection()->getCollectionJobNumber());

            $label->save();
        }

        $this->consignmentNumber = $consignmentNumber;
    }

    /**
     * @return mixed
     */
    public function getConsignmentNumber()
    {
        return $this->consignmentNumber;
    }

    /**
     * @return mixed
     */
    public function getBookCollection()
    {
        return $this->bookCollection;
    }

    /**
     * @param mixed $bookCollection
     */
    public function setBookCollection($bookCollection)
    {
        $this->bookCollection = $bookCollection;
    }

    public function getLabel()
    {
        $request = new stdClass();

        $request->AuthenticationToken = $this->getClient()->getToken();
        $request->Username = $this->getClient()->getUsername();
        $request->AccountNumber = $this->getClient()->getAccount()->AccountNumber;
        $request->CollectionJobNumber = $this->getBookCollection()->getCollectionJobNumber();

        /**
         * @var $order Mage_Sales_Model_Order
         */
        $order = $this->getOrder();
        $address = new stdClass();

        if ($order->getShippingAddress()->getStreet1()) {
            $address->Address1 = $order->getShippingAddress()->getStreet1();
        }

        if ($order->getShippingAddress()->getStreet2()) {
            $address->Address2 = $order->getShippingAddress()->getStreet2();
        }

        if ($order->getShippingAddress()->getStreet3()) {
            $address->Address3 = $order->getShippingAddress()->getStreet3();
        }

        $address->PostalTown = $order->getShippingAddress()->getCity();
        $address->Postcode = $order->getShippingAddress()->getPostcode();

        $country = Mage::getModel('directory/country')->loadByCode($order->getShippingAddress()->getCountry());

        $address->CountryCode = $country->getIso3Code();

        $request->Address = $address;

        $request->PreDeliveryNotification = (string) Mage::helper('ukmail')->getConfigValue('pre_delivery_notification');
        $request->Email = $order->getCustomerEmail();
        $request->Telephone = $order->getShippingAddress()->getTelephone();
        $request->ContactName = $order->getCustomerName();

        if ($order->getShippingAddress()->getCompany()) {
            $request->BusinessName = $order->getShippingAddress()->getCompany();
        }

        $request->CustomersRef = $order->getIncrementId();
        $request->Items = (int) $order->getShippingLabels();
        $request->Weight = (int) $this->getWeight($order);
        $request->ServiceKey = $this->getServiceKey();
        $request->ConfirmationOfDelivery = $this->getConfirmationOfDelivery();
        $request->ExchangeOnDelivery = (bool) Mage::helper('ukmail')->getConfigValue('exchange_on_delivery');
        $request->ExtendedCover = (int) Mage::helper('ukmail')->getConfigValue('extended_cover');
        $request->SignatureOptional = (bool) Mage::helper('ukmail')->getConfigValue('signature_optional');
        $request->BookIn = false;
        $request->CODAmount = 0.00;
        $request->LongLength = (bool) Mage::helper('ukmail')->getConfigValue('long_length');

        $body = new stdClass();
        $body->request = $request;

        $result = $this->doRequest('AddDomesticConsignment', $body);

        if (property_exists($result, 'AddDomesticConsignmentResult')) {
            if ($result->AddDomesticConsignmentResult->Result == Vinehousefarm_Ukmail_Helper_Data::RESULT_SUCCESSFUL) {
                $this->setConsignmentNumber($result->AddDomesticConsignmentResult->ConsignmentNumber);

                foreach ($result->AddDomesticConsignmentResult->Labels->base64Binary as $label) {
                    $this->labels[] = $label;
                }
            }

            if ($result->AddDomesticConsignmentResult->Result == Vinehousefarm_Ukmail_Helper_Data::RESULT_FAILED) {
                foreach ($result->AddDomesticConsignmentResult->Errors->UKMWebError as $error) {
                    $this->addError($error->Description);
                }

                Mage::throwException($order->getIncrementId() . ':' . implode(', ', $this->getErrors()));
            }
        }

        return $this;
    }

    public function getServiceKey()
    {
        return (int) Mage::helper('ukmail')->getConfigValue('service_key');
    }

    protected function getConfirmationOfDelivery()
    {
        return (bool) Mage::helper('ukmail')->getConfigValue('confirmation_of_delivery');
    }

    /**
     * @param $order
     * @return float
     */
    protected function getWeight(Mage_Sales_Model_Order $order)
    {
        $weight = 0;

        foreach ($order->getAllItems() as $item) {
            if (Mage::helper('vinehousefarm_common')->isWarehouse($item)) {
                $weight = $weight + $item->getWeight();
            }
        }

        return ceil($weight);
    }
}