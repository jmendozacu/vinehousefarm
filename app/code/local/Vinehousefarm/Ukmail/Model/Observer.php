<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */

class Vinehousefarm_Ukmail_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function orderCancelAfter(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order) {
            $labels = Mage::getModel('ukmail/label')->getCollection()
                ->addFieldToFilter('order_id', $order->getId());

            if ($labels->count()) {

                $service = Mage::getModel('ukmail/service_cancel');

                foreach ($labels as $label) {
                    $service->setLabel($label);

                    try {
                        $service->cancelReturn();
                    } catch (Exception $e) {
                        Mage::logException($e);
                    }
                }
            }
        }

        return $this;
    }
}