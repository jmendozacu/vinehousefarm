<?php

/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */
class Vinehousefarm_Ukmail_Model_Cron extends Mage_Core_Model_Abstract
{
    /**
     * @throws Exception
     */
    public function updatePostCode()
    {
        $service = Mage::getModel('ukmail/service_postcode');

        if (!count($service->getPostTime())) {
            try {
                Mage::getModel('ukmail/postcode')->truncate();

                foreach ($service->getPostTime() as $postcode) {
                    $item = Mage::getModel('ukmail/postcode');

                    $item->setCountry($postcode->Country);
                    $item->setCounty($postcode->County);
                    $item->setEffectiveDateFrom($postcode->EffectiveDateFrom);
                    $item->setEffectiveDateTo($postcode->EffectiveDateTo);
                    $item->setHas1030AM((bool)$postcode->Has1030AM);
                    $item->setHas48hr((bool)$postcode->Has48hr);
                    $item->setHas9AM((bool)$postcode->Has9AM);
                    $item->setHasAM((bool)$postcode->HasAM);
                    $item->setHasEvening((bool)$postcode->HasEvening);
                    $item->setHasNextDay((bool)$postcode->HasNextDay);
                    $item->setHasPM((bool)$postcode->HasPM);
                    $item->setHasPallets((bool)$postcode->HasPallets);
                    $item->setHasSaturday((bool)$postcode->HasSaturday);
                    $item->setHasSaturday1030AM((bool)$postcode->HasSaturday1030AM);
                    $item->setHasSaturday9AM((bool)$postcode->HasSaturday9AM);
                    $item->setLCTime($postcode->LCTime);
                    $item->setLNTime($postcode->LNTime);
                    $item->setLocality($postcode->Locality);
                    $item->setLocationName($postcode->LocationName);
                    $item->setPostcode($postcode->Postcode);
                    $item->setPrimarySort($postcode->PrimarySort);
                    $item->setSecondarySort($postcode->SecondarySort);
                    $item->setTown($postcode->Town);

                    $item->save();
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    public function updateCountry()
    {
        $service = Mage::getModel('ukmail/service_country');

        if (count($service->getCountry())) {
            try {
                Mage::getModel('ukmail/country')->truncate();

                foreach ($service->getCountry() as $country) {
                    $item = Mage::getModel('ukmail/country');

                    $item->setCode($country->Code);
                    $item->setName($country->Name);

                    $item->save();
                }

            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
}