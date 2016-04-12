<?php

class Newedge_CustomerNotes_Model_Observer
{
	public function adminhtmlSalesOrderCreateProcessData(Varien_Event_Observer $observer)
	{
		try {
			$requestData = Mage::app()->getFrontController()->getRequest()->getParam('order');
			$notes = $requestData["account"]["customernotes"];
			$event = $observer->getEvent();
			$order = $event->getOrder();
			$customer_id = $order->getCustomerId();
			if ($customer_id){
				$customer = Mage::getModel("customer/customer")->load($customer_id);
				if ($customer){
					$customer->setCustomernotes((string)$notes)->save();
				}
			}
		} catch (Exception $e) {
			Mage::logException($e);
		}
		return $this;
	}
}