<?php

class Newedge_OrderSource_Adminhtml_SourceController extends Mage_Adminhtml_Controller_Action
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

	/**
	 * This action handles both viewing and editing existing sources.
	 */
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = Mage::getModel('newedge_ordersource/source')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('current_source', $model);

			// Instantiate the form container.
			$sourceEditBlock = $this->getLayout()->createBlock(
				'newedge_ordersource_adminhtml/source_edit'
			);

        // Add the form container as the only item on this page.
        $this->loadLayout()
			->_addContent($sourceEditBlock)
			->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('newedge_ordersource')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction()
	{
		$this->_forward('edit');
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
		if ($data = $this->getRequest()->getPost('main')) {

			$model = Mage::getModel('newedge_ordersource/source');
			$model->setData($data);

			try {
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('newedge_ordersource')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $data['entity_id']));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('newedge_ordersource')->__('Unable to find item to save'));
		$this->_redirect('*/*/');
	}
}