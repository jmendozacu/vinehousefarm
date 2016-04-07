<?php

class Newedge_OrderSource_IndexController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Instantiate our grid container block and add to the page content.
	 * When accessing this admin index page, we will see a grid of all
	 * sources currently available in our Magento instance, along with
	 * a button to add a new one if we wish.
	 */
	public function indexAction()
	{
		// instantiate the grid container
		$sourceBlock = $this->getLayout()
			->createBlock('newedge_ordersource_adminhtml/source');

		// Add the grid container as the only item on this page
		$this->loadLayout()
			->_addContent($sourceBlock)
			->renderLayout();
	}

	public function newAction()
	{
		$this->_forward('edit');
	}

	/**
	 * This action handles both viewing and editing existing sources.
	 */
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id', null);
		$model = Mage::getModel('newedge_ordersource/source');
		if ($id) {
			$model->load((int) $id);
			if ($model->getId()) {
				$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
				if ($data) {
					$model->setData($data)->setId($id);
				}
			} else {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('newedge_ordersource')->__('Source does not exist'));
				$this->_redirect('*/*/');
			}
		}
		Mage::register('current_source', $model);




		// Instantiate the form container.
		$sourceEditBlock = $this->getLayout()->createBlock(
				'newedge_ordersource_adminhtml/source_edit'
			);

		// Add the form container as the only item on this page.
		$this->loadLayout()
			->_setActiveMenu('ordersources')
			->_addContent($sourceEditBlock)
			->renderLayout();
	}

	public function viewAction()
	{
		$this->_forward('edit');
	}

	public function deleteAction()
	{
		if ($this->getRequest()->getParam('id') > 0) {
			try {
				$model = Mage::getModel('newedge_ordersource/source');

				$model->setId($this->getRequest()->getParam('id'))
					->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost())
		{
			$model = Mage::getModel('newedge_ordersource/source');
			$id = $this->getRequest()->getParam('id');
			if ($id) {
				$model->load($id);
			}

			$model->setData($data);
			Mage::getSingleton('adminhtml/session')->setFormData($data);
			try {
				if ($id) {
					$model->setId($id);
				}
				$model->save();

				if (!$model->getId()) {
					Mage::throwException(Mage::helper('newedge_ordersource')->__('Error saving source'));
				}

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('newedge_ordersource')->__('Source was successfully saved.'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				// The following line decides if it is a "save" or "save and continue"
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
				} else {
					$this->_redirect('*/*/');
				}
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				if ($model && $model->getId()) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
				} else {
					$this->_redirect('*/*/');
				}
			}

			return;
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('newedge_ordersource')->__('No data found to save'));
		$this->_redirect('*/*/');
	}
}