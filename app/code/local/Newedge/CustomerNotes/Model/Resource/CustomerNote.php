<?php

class Newedge_CustomerNotes_Model_Resource_CustomerNote extends Mage_Core_Model_Resource_Db_Abstract
{
	protected function _construct()
	{
		$this->_init('newedge_customernotes/customernote', 'customernotes');
	}
}