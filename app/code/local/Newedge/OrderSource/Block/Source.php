<?php
class Newedge_OrderSource_Block_Source extends Mage_Core_Block_Template
{
	public function getSources()
	{
		$sources = Mage::getResourceModel('newedge_ordersource/source_collection')
			->getAllSources();
		print_r($sources);
		return $sources;
	}
}