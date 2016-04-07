<?php
class Newedge_OrderSource_Block_Adminhtml_Source_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	protected function _prepareCollection()
	{
		/**
		 * Tell Magento which collection to use to display in the grid.
		 */
		$collection = Mage::getResourceModel(
			'newedge_ordersource/source_collection'
		);
		$this->setCollection($collection);

        return parent::_prepareCollection();
    }

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
	}

	protected function _prepareColumns()
	{
		/**
		 * Here, we'll define which columns to display in the grid.
		 */
		$this->addColumn('order_moto_source_id', array(
			'header' => $this->_getHelper()->__('ID'),
			'type' => 'number',
			'index' => 'order_moto_source_id',
		));

        $this->addColumn('title', array(
		'header' => $this->_getHelper()->__('Source'),
            'type' => 'text',
            'index' => 'title',
        ));

        /**
         * Finally, we'll add an action column with an edit link.
         */
        $this->addColumn('action', array(
		'header' => $this->_getHelper()->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'actions' => array(
		array(
			'caption' => $this->_getHelper()->__('Edit'),
                    'url' => array(
		'base' => 'newedge_ordersource_admin'
	. '/index/edit',
                    ),
                    'field' => 'id'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'order_moto_source_id',
        ));

        return parent::_prepareColumns();
    }

	protected function _getHelper()
	{
		return Mage::helper('newedge_ordersource');
	}
}