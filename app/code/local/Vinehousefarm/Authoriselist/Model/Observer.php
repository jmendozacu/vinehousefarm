<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Model_Observer
{
//    public function salesModelServiceQuoteSubmitBefore(Varien_Event_Observer $observer)
//    {
//        $order = $this->getOrder($observer);
//        $quote = $this->getQuote($observer);
//
//        if ($order) {
//            $order->setCreatedAt(Varien_Date::formatDate($quote->getOldCreateData(), false));
//        }
//
//        return $this;
//    }

    public function salesOrderSaveBefore(Varien_Event_Observer $observer)
    {
        /**
         * @var $model Mage_Sales_Model_Order
         */
        $model = $this->getOrder($observer);

        if ($model) {
            $shipping_labels = Mage::app()->getRequest()->getPost('shipping_labels', 0);
            if ($shipping_labels) {
                $model->setShippingLabels($shipping_labels);
            }

            $shipping_arrival_date = Mage::app()->getRequest()->getPost('shipping_arrival_date');
            $desiredArrivalDate = Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave(str_replace('/', '-', $shipping_arrival_date));

            if ($desiredArrivalDate) {
                $model->setShippingArrivalDate($desiredArrivalDate);
            }

            $key = 'item_id';

            if ($model->isObjectNew()) {
                $key = 'quote_item_id';
            }

            foreach ($model->getAllVisibleItems() as $item) {
                $shipping_method = Mage::app()->getRequest()->getPost('item_' . $item->getData($key) . '_shippng_method', null);

                if ($item->getShippingMethod() != $shipping_method) {
                    if ($shipping_method) {
                        $item->setShippingMethod($shipping_method);
                        if (!$model->isObjectNew()) {
                            $item->save();
                        }
                    }
                }
            }

            foreach ($model->getAllVisibleItems() as $item) {
                $warehouse_code = Mage::app()->getRequest()->getPost('item_' . $item->getData($key) . '_warehouse', null);

                if ($item->getWarehouseCode() !== $warehouse_code) {
                    if ($warehouse_code) {
                        $item->setWarehouseCode($warehouse_code);
                        if (!$model->isObjectNew()) {
                            $item->save();
                        }
                    }
                }
            }

            if ($model->isObjectNew()) {
                if (!$model->getShippingArrivalComments()) {
                    $orderData = Mage::app()->getRequest()->getPost('order');

                    if (isset($orderData['shipping_address']) && !empty($orderData['shipping_address'])) {
                        $shippingAddress = $orderData['shipping_address'];
                        if (isset($shippingAddress['delivery_note']) && !empty($shippingAddress['delivery_note'])) {
                            $model->setShippingArrivalComments($shippingAddress['delivery_note']);
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function salesQuoteItemSaveBefore(Varien_Event_Observer $observer)
    {
        /**
         * @var $model Mage_Sales_Model_Quote
         */
        $model = $observer->getEvent()->getQuote();

        foreach ($model->getAllVisibleItems() as $item) {

            if ($item) {
                $shipping_method = Mage::app()->getRequest()->getPost('item_' . $item->getId() . '_shippng_method', null);

                if ($item->getShippingMethod() != $shipping_method) {
                    if ($shipping_method) {
                        $item->setShippingMethod($shipping_method);
                    }
                }

                $warehouse_code = Mage::app()->getRequest()->getPost('item_' . $item->getId() . '_warehouse', null);

                if ($item->getWarehouseCode() !== $warehouse_code) {
                    if ($warehouse_code) {
                        $item->setWarehouseCode($warehouse_code);
                    }
                }
            }

        }

        return $this;
    }

    public function catalogProductSaveBefore(Varien_Event_Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();

        if ($product) {
            if ($product->getSupplier()) {
                $product->setIsDropship('1');
            } else {
                $product->setIsDropship('0');
            }
        }

        return $this;
    }

    public function adminhtmlCatalogProductEditPrepareForm(Varien_Event_Observer $observer)
    {
        /**
         * @var Varien_Data_Form
         */
        $form = $observer->getEvent()->getForm();

        if ($form) {
            if ($formset = $form->getElements()) {
                foreach ($formset->getIterator() as $item) {
                    if ($item instanceof Varien_Data_Form_Element_Fieldset) {
                        if ($dropship = $item->getElements()->searchById('dropship')) {
                            $dropship->setReadonly(true, true);
                        }
                    }
                }
            }
        }

        return $this;
    }

    /*
     *
     */
    public function catalogProductSaveAfter(Varien_Event_Observer $observer)
    {
        /**
         * @var $product Mage_Catalog_Model_Product
         */
        $product = $observer->getEvent()->getProduct();

        if ($product) {

            if ($product->getSupplier()) {

                if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {

                    $stockData = array(
                        'use_config_manage_stock' => 0,
                        'manage_stock' => 1,
                    );

                    $warehouse = Mage::getModel('AdvancedStock/Warehouse')->getCollection()
                        ->addFieldToFilter('stock_code', 'dropship')
                        ->getFirstItem();


                    $stockData['affect_to_warehouse'] = array(
                        'warehouse_id' => $warehouse->getStockId(),
                        'is_favorite' => '1',
                    );

                    $this->getHelper()->stockProduct($product, $stockData);
                }

                if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                    $stockData = array(
                        'use_config_manage_stock' => 0,
                        'manage_stock' => 1,
                    );

                    $this->getHelper()->stockProduct($product, $stockData);

                    $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null,$product);

                    /**
                     * @var $child Mage_Catalog_Model_Product
                     */
                    foreach ($childProducts as $child) {
                        $child->addAttributeUpdate('dropship', 1, $child->getStoreId());

                        $stockData = array(
                            'use_config_manage_stock' => 0,
                            'manage_stock' => 1,
                        );

                        $warehouse = Mage::getModel('AdvancedStock/Warehouse')->getCollection()
                            ->addFieldToFilter('stock_code', 'dropship')
                            ->getFirstItem();


                        $stockData['affect_to_warehouse'] = array(
                            'warehouse_id' => $warehouse->getStockId(),
                            'is_favorite' => '1',
                        );

                        $this->getHelper()->stockProduct($child, $stockData);

                    }
                }


            } else {
                if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                    $stockData = array(
                        'use_config_manage_stock' => 0,
                        'manage_stock' => 1,
                    );

                    $this->getHelper()->stockProduct($product, $stockData);

                    $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null,$product);

                    /**
                     * @var $child Mage_Catalog_Model_Product
                     */
                    foreach ($childProducts as $child) {
                        $child->addAttributeUpdate('dropship', 0, $child->getStoreId());

                        $favorite = trim(strtolower($child->getAttributeText('default_picked_from')));

                        $warehouse = Mage::getModel('AdvancedStock/Warehouse')->getCollection()
                            ->addFieldToFilter('stock_code', $favorite)
                            ->getFirstItem();

                        if (!$warehouse) {
                            $warehouseId = Mage::getStoreConfig('advancedstock/router/default_warehouse', $child->getStoreId());
                        } else {
                            $warehouseId = $warehouse->getStockId();
                        }


                        $stockData['affect_to_warehouse'] = array(
                            'warehouse_id' => $warehouseId,
                            'is_favorite' => '1',
                        );

                        $this->getHelper()->stockProduct($child, $stockData);

                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function salesOrderPlaceAfter(Varien_Event_Observer $observer)
    {
        $order = $this->getOrder($observer);

        if ($order) {
            $this->getHelper()->setOrder($order);

            if (!Mage::getSingleton('admin/session')->getUser()) {
                $this->getHelper()->checkAddress();
                $this->getHelper()->checkDeliveryDate();
                $this->getHelper()->checkTradeCustomer();
                $this->getHelper()->checkShippingMethods();
                $this->getHelper()->checkWeightThreshold();
                $this->getHelper()->checkBeforeShipping();
                $this->getHelper()->checkLabels();
            }

            $this->getHelper()->dropshipItem();
            $this->getHelper()->supplierItem();
        }

        return $this;
    }

    public function dropshipItem(Varien_Event_Observer $observer)
    {
        $item = $observer->getEvent()->getItem();
        $product = $observer->getEvent()->getProduct();


        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function orderpreparartionCreateTabs(Varien_Event_Observer $observer)
    {
        /*
         * @var $tab Mage_Adminhtml_Block_Widget_Tabs
         */
        $tab = $observer->getEvent()->getTab();

        $tab->removeTab('ignoredorders');

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function controllerActionPredispatchAdminhtmlProcessing(Varien_Event_Observer $observer)
    {
        $request = Mage::app()->getFrontController()->getRequest();

        if (!$request->getParam('filter')) {
            $filter = $this->_getDefaultEncodedFilterString();

            Mage::app()->getFrontController()
                ->getRequest()
                ->setParam('filter', $filter);
        }

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function coreBlockAbstractPrepareLayoutAfter(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();

        if (Mage::app()->getFrontController()->getAction()->getFullActionName() === 'adminhtml_dashboard_index') {
            if ($block->getNameInLayout() === 'dashboard') {
                $orders = (int)$this->getHelper()->getCountNext();
                $block->getChild('sales')->addTotal($this->getHelper()->__('Orders Requiring Validation'), $orders, true);
            }
        }

        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {

            /**
             * @var $product Mage_Catalog_Model_Product
             */
            if (Mage::registry('current_product')) {
                $product = Mage::registry('current_product');

                $item = new Varien_Object();
                $item->setProductId($product->getId());

                if (Mage::helper('authoriselist')->isSupplierItem($item) || Mage::helper('authoriselist')->isDropShipItem($item)) {
                    $block->removeTab('group_137');
                }
            }
        }

        return $this;
    }

    public function addColumnToSalesOrderGrid($observer)
    {

        $block = $observer->getEvent()->getBlock();
        //if (get_class($block) == 'Mage_Adminhtml_Block_Sales_Order_Grid') {
        if (in_array(get_class($block), array(
            'Vinehousefarm_Authoriselist_Block_Adminhtml_Processing_Grid',
            'Vinehousefarm_Authoriselist_Block_Adminhtml_Pickingpacking_Grid',
            'Vinehousefarm_Authoriselist_Block_Adminhtml_Completed_Grid',
            'Vinehousefarm_Authoriselist_Block_Adminhtml_Ordersearch_Grid',
            'Vinehousefarm_Deliverydate_Block_Adminhtml_Futureorder_Grid',
        ))) { //Thanks Paul Ketelle for your feedback on this

            $block->addColumnAfter('sagepay_transaction_state', array(
                    'header' => Mage::helper('sagepaysuite')->__('Sage Pay'),
                    'index' => 'sagepay_transaction_state',
                    'align' => 'center',
                    'filter' => false,
                    'renderer' => 'sagepaysuite/adminhtml_sales_order_grid_renderer_state',
                    'sortable' => false,
                )
                , 'real_order_id');

            $block->addColumnAfter('shipping_date', array(
                    'header' => Mage::helper('sales')->__('Delivery Date'),
                    'renderer' => 'Vinehousefarm_Deliverydate_Block_Adminhtml_Futureorder_Renderer_Deliverydate',
                    'index' => 'shipping_arrival_date',
                    'align' => 'center',
                    'filter' => false,
                    'renderer' => 'Vinehousefarm_Deliverydate_Block_Adminhtml_Futureorder_Renderer_Deliverydate',
                    'sortable' => false,
                )
                , 'shipping_name');
        }

        return $observer;
    }

    /**
     * @return string
     */
    protected function _getDefaultEncodedFilterString()
    {
        $helper = Mage::helper('authoriselist');
        $yesterdaysDate = $helper->getYesterdaysDate();
        $todayDate = $helper->getTodayDate();

        $data = array(
            'created_at' =>
                array(
                    'from' => $yesterdaysDate,
                    'to' => $todayDate,
                    'locale' => $this->_getLocalCode()
                )
        );

        return $helper->createFilterString($data);
    }

    /**
     * @return string|null
     */
    protected function _getLocalCode()
    {
        return Mage::getStoreConfig('general/locale/code', Mage::app()->getStore()->getId());
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return mixed
     */
    protected function getOrder(Varien_Event_Observer $observer)
    {
        return $observer->getEvent()->getOrder();
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return mixed
     */
    protected function getQuote(Varien_Event_Observer $observer)
    {
        return $observer->getEvent()->getQuote();
    }

    /**
     * @return Vinehousefarm_Authoriselist_Helper_Data
     */
    protected function getHelper()
    {
        $helper = Mage::helper('authoriselist');
        return $helper;
    }
}