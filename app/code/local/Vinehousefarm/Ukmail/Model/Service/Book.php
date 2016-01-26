<?php
/**
 * @package UK Mail.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Ukmail_Model_Service_Book extends Vinehousefarm_Ukmail_Model_Service_Abstract
{
    /**
     * @var string
     */
    protected $wsdl = 'Services/UKMCollectionServices/UKMCollectionService.svc?wsdl';

    protected $collectionJobNumber = '';

    /**
     * @return string
     */
    public function getCollectionJobNumber()
    {
        return $this->collectionJobNumber;
    }

    /**
     * @param string $collectionJobNumber
     */
    public function setCollectionJobNumber($collectionJobNumber)
    {
        $this->collectionJobNumber = $collectionJobNumber;
    }

    /**
     * @return mixed
     */
    public function getBookCoolection()
    {

        $bookcollection = new stdClass();
        $request = new stdClass();

        $request->AuthenticationToken = $this->getClient()->getToken();
        $request->Username = $this->getClient()->getUsername();
        $request->AccountNumber = $this->getClient()->getAccount()->AccountNumber;
        $request->ClosedForLunch = false;

        $dataJob = new DateTime(date('Y-m-d'));

        $dateResult = $this->getDateJob($dataJob);

        $dateEarliest = new DateTime($dateResult->format('Y-m-d'));
        $dateEarliest->add(new DateInterval('PT10H'));
        $dateLatest = new DateTime($dateResult->format('Y-m-d'));
        $dateLatest->add(new DateInterval('PT17H'));
        $dateRequested = new DateTime($dateResult->format('Y-m-d'));
        $dateRequested->add(new DateInterval('PT17H'));

        $request->EarliestTime = $dateEarliest->format('c');
        $request->LatestTime = $dateLatest->format('c');
        $request->RequestedCollectionDate = $dateRequested->format('c');
        $request->SpecialInstructions = "Delivery";

        $bookcollection->request = $request;

        $result = $this->doRequest('BookCollection', $bookcollection);

        if (property_exists($result, 'BookCollectionResult')) {
            if ($result->BookCollectionResult->Result == Vinehousefarm_Ukmail_Helper_Data::RESULT_SUCCESSFUL) {
                $this->setCollectionJobNumber($result->BookCollectionResult->CollectionJobNumber);
            }

            if ($result->BookCollectionResult->Result == Vinehousefarm_Ukmail_Helper_Data::RESULT_FAILED) {
                foreach ($result->BookCollectionResult->Errors->UKMWebError as $error) {
                    $this->addError($error->Description);
                }

                Mage::throwException(implode(', ', $this->getErrors()));
            }
        }

        return $this;
    }

    /**
     * @param DateTime $dataJob
     * @return DateTime
     */
    public function getDateJob(DateTime $dataJob)
    {
        $dateResult = Mage::helper('vinehousefarm_deliverydate/salesOrderPlanning_holidays')->getNextDayThatIsNotHolyday($dataJob->getTimestamp());
        $dateResult = new DateTime(date('Y-m-d', $dateResult));

        $interval = $dateResult->diff($dataJob);

        if ($interval->format('%a') > 1) {
            $dataJob = $this->getDateJob($dateResult);
        }

        return $dataJob;
    }
}