<?php
class Newedge_OrderSource_Model_Resource_Source_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('newedge_ordersource/source');
	}

	public function getAllSources(){
		$this->_data = $this->_fetchAll($this->_select);
		$values = array();
		foreach ($this->_data as $key => $value){
			array_push($values, array("value" => $value["order_moto_source_id"], "label" => $value["title"]));
		}
		return $values;
	}
}