<?php

/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */
class Vinehousefarm_Ukmail_Model_Service_Country extends Vinehousefarm_Ukmail_Model_Service_Abstract
{
    /**
     * @var string
     */
    protected $wsdl = 'Services/UKMReferenceDataServices/UKMReferenceDataService.svc?wsdl';

    protected $items = array();

    public function getCountry()
    {
        if (!count($this->items)) {

            $request = new stdClass();

            $request->AuthenticationToken = $this->getClient()->getToken();
            $request->Username = $this->getClient()->getUsername();

            $countries = new stdClass();
            $countries->request = $request;

            $result = $this->doRequest('GetCountries', $countries);

            if (property_exists($result, 'GetCountriesResult')) {
                if ($result->GetCountriesResult->Result == Vinehousefarm_Ukmail_Helper_Data::RESULT_SUCCESSFUL) {
                    if (property_exists($result->GetCountriesResult, 'Countries')) {
                        if (property_exists($result->GetCountriesResult->Countries, 'CountryWebModel')) {
                            if (!empty($result->GetCountriesResult->Countries->CountryWebModel)) {
                                foreach ($result->GetCountriesResult->Countries->CountryWebModel as $country) {
                                    $this->items[] = $country;
                                }
                            }
                        }
                    }
                }

            }
        }

        return $this->items;
    }
}