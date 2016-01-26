<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */ 
class Vinehousefarm_Deliverydate_Helper_SalesOrderPlanning_Holidays extends MDN_SalesOrderPlanning_Helper_Holidays
{
    /**
     * return true if an day is holy day
     *
     * @param unknown_type $date
     */
    public function isHolyDay($dateTimestamp)
    {

        //check weekend

        $dayId = date('w', $dateTimestamp);
        $weekendDay = Mage::getStoreConfig('general/locale/weekend');
        $pos = strpos($weekendDay, $dayId);
        if (!($pos === false))
        {
            return true;
        }


        $day = date('d', $dateTimestamp);
        $month = date('m', $dateTimestamp);
        $year = date('Y', $dateTimestamp);
        $country = mage::getStoreConfig('general/country/default');
        switch ($country)
        {
            case 'FR':
                //todo: add holydays logic
                $dayMonth = $day.'-'.$month;
                switch ($dayMonth)
                {
                    case '01-01':
                    case '01-05':
                    case '08-05':
                    case '14-07':
                    case '15-08':
                    case '01-11':
                    case '11-11':
                    case '25-12':
                        return true;
                        break;
                }

                break;

            case 'GB':

                $collection = Mage::getModel('vinehousefarm_deliverydate/deliverydate')->getCollection()
                    ->addFieldToFilter('status','1');
                $dayMonth = $day.'-'.$month;

                foreach ($collection as $item) {
                    if ($item->getHolidayTime() === $dayMonth) {
                        return true;
                    }
                }

                break;

            default:
                //todo: add holydays logic

                break;
        }

        return false;
    }
}