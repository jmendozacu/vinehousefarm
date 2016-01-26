<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */

class Vinehousefarm_Ukmail_Model_Service_Postcode extends Vinehousefarm_Ukmail_Model_Service_Abstract
{
    /**
     * @var string
     */
    protected $wsdl = 'Services/UKMReferenceDataServices/UKMReferenceDataService.svc?wsdl';

    protected $items = array();

    public function getPostTime()
    {
        if (!count($this->items)) {
            $request = new stdClass();

            $request->AuthenticationToken = $this->getClient()->getToken();
            $request->Username = $this->getClient()->getUsername();

            $date = new DateTime();
            $date->sub(new DateInterval('P180D'));

            $request->LastUpdateDate = $date->format('c');

            $postcodes = new stdClass();
            $postcodes->request = $request;

            $result = $this->doRequest('GetPostcodes', $postcodes);

            if (property_exists($result, 'GetPostcodesResult')) {
                if ($result->GetPostcodesResult->Result == Vinehousefarm_Ukmail_Helper_Data::RESULT_SUCCESSFUL) {
                    if (property_exists($result->GetPostcodesResult, 'Postcodes')) {
                        if (property_exists($result->GetPostcodesResult->Postcodes, 'PostcodeWebModel')) {
                            if (!empty($result->GetPostcodesResult->Postcodes->PostcodeWebModel)) {
                                foreach ($result->GetPostcodesResult->Postcodes->PostcodeWebModel as $postcode) {
                                    $this->items[] = $postcode;
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