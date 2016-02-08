<?php
/**
 * @package UK Mail.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Service_Cancel extends Vinehousefarm_Ukmail_Model_Service_Abstract
{
    /**
     * @var string
     */
    protected $wsdl = 'Services/UKMConsignmentServices/UKMConsignmentService.svc?wsdl';

    /**
     * @var Vinehousefarm_Ukmail_Model_Label
     */
    protected $label;

    /**
     * @return Vinehousefarm_Ukmail_Model_Label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param Vinehousefarm_Ukmail_Model_Label $label
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @throws Mage_Core_Exception
     */
    public function cancelReturn()
    {
        if ($this->getLabel()) {

            $request = new stdClass();

            $request->AuthenticationToken = $this->getClient()->getToken();
            $request->Username = $this->getClient()->getUsername();
            $request->ConsignmentNumber = $this->getLabel()->getConsignmentNumber();

            $body = new stdClass();
            $body->request = $request;

            $result = $this->doRequest('CancelReturn', $body);

            if (property_exists($result, 'CancelReturnResult')) {
                if ($result->CancelReturnResult->Result == Vinehousefarm_Ukmail_Helper_Data::RESULT_SUCCESSFUL) {
                    $this->getLabel()->delete();
                }

                if ($result->CancelReturnResult->Result == Vinehousefarm_Ukmail_Helper_Data::RESULT_FAILED) {
                    foreach ($result->CancelReturnResult->Errors->UKMWebError as $error) {
                        $this->addError($error->Description);
                    }

                    Mage::throwException($this->getLabel()->getConsignmentNumber() . ':' . implode(', ', $this->getErrors()));
                }
            }
        } else {
            Mage::throwException('Label exists');
        }
    }
}