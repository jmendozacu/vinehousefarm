<?php
/**
 * @package UK Mail.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Service_Client
{
    /**
     * UK Mail service version
     */
    const VERSION = '1.0';

    const TEST_WSDL_URL = 'https://qa-api.ukmail.com/';
    const LIVE_WSDL_URL = 'https://api.ukmail.com/';

    /**
     * SOAP client used to connect to UK Mail service
     * @var   Zend_Soap_Client
     */
    protected $_soapClient;

    /**
     * Array of credentials (username and password) to log into backend server
     * @var   array
     */
    protected $_credentials;

    /**
     * WSDL of UK Mail web service
     * @var   string
     */
    protected $_wsdl;

    /**
     * Token for request
     * @var string
     */
    protected $_token;

    /**
     * Mode sandbox
     * @var string
     */
    protected $_sandbox_mode;

    protected $_account;

    protected $_errors = array();

    /**
     * @return array
     */
    public function getErrors()
    {
        $errors = $this->_errors;
        $this->_errors = array();
        return $errors;
    }

    /**
     * @param array $errors
     */
    public function addError($error)
    {
        return $this->_errors[] = $error;
    }

    /**
     * Constructor
     *
     * Optionally, pass an array of options (or Zend_Config object).
     *
     * If an option with the key 'soapClient' is provided, that value will be
     * used to set the internal SOAP client used to connect to the UK Mail
     * service.
     *
     * Use 'soapClient' in the case that you have a dedicated or (locally
     * installed) licensed UK Mail server. For example:
     *
     * {code}
     * $phpUKMail = new Vinehousefarm_Ukmail_Model_Service_Client(
     *     array (
     *         'username'   => 'myUsername',
     *         'password'   => 'myPassword',
     *         'soapClient' => new Zend_Soap_Client('https://api.example.com/path/mailmerge.asmx?WSDL')
     *     )
     * );
     * {code}
     *
     * Replace the URI of the WSDL in the constructor of Zend_Soap_Client with
     * that of your dedicated or licensed UK Mail server.
     *
     * If you are using the public UK Mail server, simply pass 'username' and
     * 'password'. For example:
     *
     * {code}
     * $phpUKMail = new Vinehousefarm_Ukmail_Model_Service_Client(
     *     array (
     *         'username' => 'myUsername',
     *         'password' => 'myPassword'
     *     )
     * );
     * {code}
     *
     * If you prefer to not pass the username and password through the
     * constructor, you can also call the following methods:
     *
     * {code}
     * $phpUKMail = new Vinehousefarm_Ukmail_Model_Service_Client();
     *
     * $phpUKMail->setUsername('myUsername')
     *             ->setPassword('myPassword');
     * {/code}
     *
     * Or, if you want to specify your own SoapClient:
     *
     * {code}
     * $phpUKMail = new Vinehousefarm_Ukmail_Model_Service_Client();
     *
     * $phpUKMail->setUsername('myUsername')
     *             ->setPassword('myPassword');
     *
     * $phpUKMail->setSoapClient(
     *     new Zend_Soap_Client('https://api.example.com/path/mailmerge.asmx?WSDL')
     * );
     * {/code}
     *
     * @param  array|Zend_Config $options
     * @return void
     * @throws Mage_Core_Exception
     */
    public function __construct($options = null)
    {
        $this->_credentials = array();
        $this->_sandbox_mode = true;
        $this->_token = false;

        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Set options
     * One or more of username, password, soapClient
     *
     * @param  array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Clean up and log out of UK Mail service
     *
     * @return bool
     */
    public function __destruct()
    {
        return $this->logOut();
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->_sandbox_mode;
    }

    /**
     * @param $sandbox_mode
     * @return $this
     */
    public function setMode($sandbox_mode)
    {
        $this->_sandbox_mode = $sandbox_mode;
        return $this;
    }

    /**
     * @return Zend_Soap_Client
     */
    public function getSoapClient()
    {
        return $this->_soapClient;
    }

    /**
     * @param Zend_Soap_Client $soapClient
     * @return $this
     */
    public function setSoapClient(Zend_Soap_Client $soapClient)
    {
        $this->_soapClient = $soapClient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWsdl()
    {
        if ($this->_sandbox_mode) {
            return self::TEST_WSDL_URL . $this->_wsdl;
        }

        return self::LIVE_WSDL_URL . $this->_wsdl;
    }

    /**
     * @param $wsdl
     * @return $this
     */
    public function setWsdl($wsdl)
    {
        $this->_wsdl = $wsdl;

        if ($this->getSoapClient()) {
            $this->getSoapClient()->setWsdl($this->getWsdl());
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->_account;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Set username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->_credentials['username'] = $username;
        return $this;
    }

    /**
     * Set password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->_credentials['password'] = $password;
        return $this;
    }

    /**
     * Return current username
     *
     * @return string|null
     */
    public function getUsername()
    {
        if (isset($this->_credentials['username'])) {
            return $this->_credentials['username'];
        }

        return null;
    }

    /**
     * Return current password
     *
     * @return string|null
     */
    public function getPassword()
    {
        if (isset($this->_credentials['password'])) {
            return $this->_credentials['password'];
        }

        return null;
    }

    /**
     * Log in to UK mail service
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     * @throws Mage_Core_Exception
     */
    public function login()
    {
        if (!$this->isLoggedIn()) {
            if (null === $this->getUsername()) {
                Mage::throwException('Username has not been set.');
            }

            if (null === $this->getPassword()) {
                Mage::throwException('Password has not been set.');
            }

            if (null === $this->getSoapClient()) {
                $this->_initSoapClient($this->getWsdl());
            }

            try {
                $login = new stdClass();

                $login->Username = $this->getUsername();
                $login->Password = $this->getPassword();

                $loginRequest = new stdClass();

                $loginRequest->loginWebRequest = $login;

                $response = $this->getSoapClient()->Login($loginRequest);

                if (property_exists($response->LoginResult,'Accounts')) {
                    if ($response->LoginResult->Accounts) {
                        $this->_account = array_shift($response->LoginResult->Accounts->AccountWebModel);
                    }
                }

                if (property_exists($response->LoginResult,'AuthenticationToken')) {
                    if ($response->LoginResult->AuthenticationToken) {
                        $this->_token = $response->LoginResult->AuthenticationToken;
                    }
                }

            } catch (Exception $e) {
                Mage::throwException('Cannot login into service - username and/or password are invalid. ' . $e->getMessage());
            }
        }

        return $this->isLoggedIn();
    }

    /**
     * Log out of the LiveDocx service
     *
     * @return bool
     * @throws Mage_Core_Exception
     */
    public function logout()
    {
        if ($this->isLoggedIn()) {
            try {

                $this->setWsdl('Services/UKMAuthenticationServices/UKMAuthenticationService.svc?wsdl');

                $webRequest = new stdClass();

                $webRequest->Username = $this->getUsername();
                $webRequest->AuthenticationToken = $this->getToken();

                $logout = new stdClass();

                $logout->webRequest = $webRequest;

                $response = $this->getSoapClient()->Logout($logout);

                $this->_token = false;
            } catch (Exception $e) {
                Mage::throwException($e->getMessage());
            }
        }

        return $this->isLoggedIn();
    }

    /**
     * Return true, if session is currently logged into the backend server
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        if ($this->_token) {
            return true;
        }

        return false;
    }

    /**
     * Init Soap client - connect to SOAP service
     *
     * @param  string $endpoint
     * @throws Mage_Core_Exception
     * @return void
     */
    protected function _initSoapClient($endpoint)
    {
        try {
            $this->_soapClient = new Zend_Soap_Client();
            $this->_soapClient->setSoapVersion(SOAP_1_1);
            $this->_soapClient->setSoapFeatures(1);
            $this->_soapClient->setWsdl($endpoint);
        } catch (Exception $e) {
            Mage::throwException('Cannot connect to UK Mail service at ' . $e->getMessage());
        }
    }

    /**
     * Return the current API version
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }
}