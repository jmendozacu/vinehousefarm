<?php

class Newedge_OrderSource_Model_Observer
{
	public function saveCustomData($observer)
	{
		$event = $observer->getEvent();
		$order = $event->getOrder();
		if (Mage::app()->getFrontController()->getRequest()->getParam('order_moto_source')){
			$orderSource = Mage::app()->getFrontController()->getRequest()->getParam('order_moto_source');
			if ($source= Mage::getModel('newedge_ordersource/source')->load($orderSource)){
				$order->setOrderMotoSource($source->getTitle())->save();
			} else {
				Mage::logException('Specified Order Source not found: ' . $orderSource . ' Order: ' . $order->getId());
			}
		}
		return $this;
	}

	public function extendOrderViewGrid($observer){
		$collection = $observer->getOrderGridCollection();
		$select = $collection->getSelect();
		$select->joinLeft(array('order'=>$collection->getTable('sales/order')), 'order.entity_id=main_table.entity_id',array('order_source'=>'order_moto_source'));
	}
}