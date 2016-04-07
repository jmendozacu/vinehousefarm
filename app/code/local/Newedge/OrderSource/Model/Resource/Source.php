<?php

class Newedge_OrderSource_Model_Resource_Source extends Mage_Core_Model_Resource_Db_Abstract
{
	protected function _construct()
	{
		$this->_init('newedge_ordersource/source', 'order_moto_source_id');
	}
}