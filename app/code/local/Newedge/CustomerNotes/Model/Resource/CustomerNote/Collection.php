<?php
class Newedge_CustomerNotes_Model_Resource_CustomerNote_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('newedge_customernotes/customernote');
	}
}