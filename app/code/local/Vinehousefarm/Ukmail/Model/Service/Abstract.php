<?php
/**
 * @package UK Mail.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Service_Abstract extends Varien_Object
{
    /**
     * @var Vinehousefarm_Ukmail_Model_Service_Client
     */
    protected $_client;

    /**
     * @var Mage_Sales_Order_Model
     */
    protected $order;

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->getClient()->getErrors();
    }

    /**
     * @param array $errors
     */
    public function addError($error)
    {
        $this->getClient()->addError($error);
    }

    /**
     * @var string
     */
    protected $wsdl = 'Services/UKMAuthenticationServices/UKMAuthenticationService.svc?wsdl';

    /**
     * @return Vinehousefarm_Ukmail_Model_Service_Client
     */
    public function getClient()
    {
        if (!Mage::registry('ukmail_client')) {
            $this->_client = new Vinehousefarm_Ukmail_Model_Service_Client(
                array(
                    'username'   => $this->getHelper()->getConfigValue('username'),
                    'password'   => $this->getHelper()->getConfigValue('password'),
                    'wsdl' => 'Services/UKMAuthenticationServices/UKMAuthenticationService.svc?wsdl',
                    'mode' => $this->getHelper()->getConfigValue('sandbox_mode'),
                )
            );

            Mage::register('ukmail_client', $this->_client);
        }

        if (!Mage::registry('ukmail_client')->isLoggedIn()) {
            Mage::registry('ukmail_client')->login();
        }



        return Mage::registry('ukmail_client');
    }

    /**
     * Perform a SOAP call
     *
     * @param $command
     * @param $arguments
     * @return mixed
     */
    public function doRequest($command, $arguments)
    {
        /**
         * @var $client Vinehousefarm_Ukmail_Model_Service_Client
         */
        $client = $this->getClient();
        $client->setWsdl($this->getWsdl());

        return $client->getSoapClient()->__call($command, array($arguments));
    }

    /**
     * @return Vinehousefarm_Ukmail_Helper_Data
     */
    public function getHelper()
    {
        return Mage::helper('ukmail');
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getWsdl()
    {
        return $this->wsdl;
    }

    /**
     * @param string $wsdl
     */
    public function setWsdl($wsdl)
    {
        $this->wsdl = $wsdl;
    }
}