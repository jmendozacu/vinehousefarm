<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH_AUTHORISE = 'authoriselist/authoriselist_general';
    const CONFIG_PATH_PROCESSING = 'authoriselist/processing_general';
    const STATUS_ORDER_AUTHORISE = 'awaiting_auth';
    const STATUS_ORDER_PICKING = 'pickingpacking';
    const DATA_FORMAT = 'd/m/Y';

    /**
     * @var Mage_Sales_Model_Order
     */
    protected $_order;
    protected $_items;
    protected $_changeStatus = false;
    protected $_shipping_methods = array(
        'ukmail' => 'UK Mail',
        'royalmail' => 'Royal Mail',
    );

    public function checkAddress()
    {
        return true;
    }

    /**
     * Check future delivery date.
     */
    public function checkDeliveryDate()
    {
        if ($this->getOrder()->getShippingArrivalDate()) {
            $this->getOrder()->addStatusHistoryComment('Move to Future Dispatch Date.', false);
            $this->getOrder()->setStatus(Vinehousefarm_Deliverydate_Helper_Data::STATUS_ORDER_DELIVERY_DATE, true);
            $this->setChangeStatus(true);
            $this->getOrder()->save();
        }
    }

    /**
     * @return int
     */
    public function getCountNext()
    {
        /**
         * @var $collection Mage_Sales_Model_Resource_Order_Collection
         */
        $collection = Mage::getModel('authoriselist/order')->getCollection();

        $collection->addAttributeToFilter('status', array('in' => array(Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_AUTHORISE)));

        if ($collection->getSize()) {
            return (int)$collection->getSize();
        }

        return 0;
    }

    /**
     * @return bool
     */
    public function dropshipItem()
    {
        if ($this->isChangeStatus()) {
            return false;
        }

        $dropshipItems = array();
        /**
         * @var $item Mage_Sales_Model_Order_Item
         */
        foreach ($this->getItems() as $item) {
            //TODO need refactoring
            /**
             * @var $product Mage_Catalog_Model_Product
             */
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            $dropship = (string)$product->getResource()
                ->getAttribute('dropship')
                ->getFrontend()
                ->getValue($product);

            if ($dropship == 'Yes') {
                $dropshipItems[$item->getId()] = $item->getQtyOrdered();
            }
        }

        if ($dropshipItems) {

            foreach ($this->getItems() as $item) {
                if (!array_key_exists($item->getId(), $dropshipItems)) {
                    $dropshipItems[$item->getId()] = 0;
                }
            }

            Mage::getModel('sales/order_invoice_api')->create(
                $this->getOrder()->getIncrementId(),
                $dropshipItems,
                'Drop Ship Item'
            );

            Mage::getModel('sales/order_shipment_api')->create(
                $this->getOrder()->getIncrementId(),
                $dropshipItems,
                'Drop Ship Item'
            );

            $this->notify($this->getOrder());
        }
    }

    /**
     * @return bool
     */
    public function supplierItem()
    {
        if ($this->isChangeStatus()) {
            return false;
        }

        $supplierItems = array();
        $suppliers = array();

        /**
         * @var $item Mage_Sales_Model_Order_Item
         */
        foreach ($this->getItems() as $item) {
            //TODO need refactoring
            /**
             * @var $product Mage_Catalog_Model_Product
             */
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            $supplier = (int)$product->getSupplier();

            if ($supplier) {
                $suppliers[$supplier] = $supplier;
                $supplierItems[$item->getId()] = $item->getQtyOrdered();
            }
        }

        if ($supplierItems) {

            foreach ($this->getItems() as $item) {
                if (!array_key_exists($item->getId(), $supplierItems)) {
                    $supplierItems[$item->getId()] = 0;
                }
            }

            Mage::getModel('sales/order_invoice_api')->create(
                $this->getOrder()->getIncrementId(),
                $supplierItems,
                'Drop Ship Item'
            );

            Mage::getModel('sales/order_shipment_api')->create(
                $this->getOrder()->getIncrementId(),
                $supplierItems,
                'Drop Ship Item'
            );
        }

        if ($suppliers) {
            foreach ($suppliers as $supplier) {
                $this->notifySupplier($this->getOrder(), $supplier);
            }
        }
    }

    /**
     * @param $order
     * @param string $type
     * @return mixed
     */
    public function notify(Mage_Sales_Model_Order $order)
    {
        $storeId = $order->getStore()->getId();
        $helper = Mage::helper('vinehousefarm_common');

        $dropships = array();

        $templateId = Mage::getStoreConfig('authoriselist/dropship_general/email_template');

        /** @var $mailer Mage_Core_Model_Email_Template_Mailer */
        $mailer = Mage::getModel('core/email_template_mailer');
        /** @var $emailInfo Mage_Core_Model_Email_Info */
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($helper->getDropshipEmail(), $helper->getDropshipName());

        $dropships[] = array(
            'email' => $helper->getDropshipEmail(),
            'name' => $helper->getDropshipName()
        );

        if ($copyTo && $copyMethod == 'bcc') {
            // Add bcc to customer email
            foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
            }
        }
        $mailer->addEmailInfo($emailInfo);

        // Email copies are sent as separated emails if their copy method is 'copy'
        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        // Set all required params and send emails
        $mailer->setSender(
            array(
                'name' => 'Dropship',
                'email' => 'Order' . $order->getIncrementId() . '@vhm.co.uk',
            )
        );
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(
            array(
                'order' => $order,
                'billing' => $order->getBillingAddress(),
            )
        );

        /** @var $emailQueue Mage_Core_Model_Email_Queue */
        $emailQueue = Mage::getModel('core/email_queue');
        $emailQueue->setEntityId($order->getId())
            ->setEntityType('order')
            ->setEventType($type)
            ->setIsForceCheck(false);

        $isSent = $mailer->setQueue($emailQueue)->send();

        return array('isSent' => $isSent, 'dropships' => $dropships);
    }

    /**
     * @param $order
     * @param string $type
     * @return Mage_Core_Model_Email_Template_Mailer
     */
    public function notifySupplier(Mage_Sales_Model_Order $order, $supplierId = 0)
    {
        if (!$supplierId) {
            return false;
        } else {
            $supplier = Mage::getModel('Purchase/Supplier')->load($supplierId);

            if (!$supplier->getId()) {
                return false;
            }

            $order->setCurrentSupplier($supplier);
        }

        if (!$supplier->getSupMail()) {
            return false;
        }

        $storeId = $order->getStore()->getId();

        $templateId = Mage::getStoreConfig('authoriselist/dropship_general/supplyer_template');

        $suppliers = array();

        /** @var $mailer Mage_Core_Model_Email_Template_Mailer */
        $mailer = Mage::getModel('core/email_template_mailer');
        /** @var $emailInfo Mage_Core_Model_Email_Info */
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($supplier->getSupMail(), $supplier->getSupName());

        $suppliers[] = array(
            'name' => $supplier->getSupName(),
            'email' => $supplier->getSupMail()
        );

        if ($copyTo && $copyMethod == 'bcc') {
            // Add bcc to customer email
            foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
            }
        }
        $mailer->addEmailInfo($emailInfo);

        // Email copies are sent as separated emails if their copy method is 'copy'
        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        // Set all required params and send emails
        $mailer->setSender(
            array(
                'name' => 'Dropship',
                'email' => 'Order'.$order->getIncrementId() . '@vhm.co.uk',
            )
        );
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(
            array(
                'order' => $order,
                'billing' => $order->getBillingAddress(),
            )
        );

        /** @var $emailQueue Mage_Core_Model_Email_Queue */
        $emailQueue = Mage::getModel('core/email_queue');
        $emailQueue->setEntityId($order->getId())
            ->setEntityType('order')
            ->setEventType($type)
            ->setIsForceCheck(false);

        $isSent = $mailer->setQueue($emailQueue)->send();

        return array('isSent' => $isSent, 'suppliers' => $suppliers);
    }

    /**
     * If order has a mix Royal Mail and UK Mail then flag order for “admin authorisation”.
     */
    public function checkShippingMethods()
    {
        if ($this->isChangeStatus()) {
            return false;
        }

        $shippingType = array();

        /**
         * @var $item Mage_Sales_Model_Order_Item
         */
        foreach ($this->getItems() as $item) {
            //TODO need refactoring
            /**
             * @var $product Mage_Catalog_Model_Product
             */
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            $dropship = (string)$product->getResource()
                ->getAttribute('dropship')
                ->getFrontend()
                ->getValue($product);

            $shippingType = array();

            if ($dropship !== 'Yes') {
                if (!$item->getShippingMethod()) {
                    $value = (int)$product->getResource()->getAttributeRawValue($product->getId(), 'ships_method', $this->getOrder()->getStoreId());

                    $item->setShippingMethod($this->getShippingValue($product, $value));
                    $item->save();
                }

                $shippingType[$item->getShippingMethod()] = $item->getShippingMethod();

            } else {
                Mage::dispatchEvent('dropship_item', array('item' => $item, 'product' => $product));
            }
        }

        if (count($shippingType) > 1) {
            $this->changeStatus();
        }
    }

    /**
     *  If order exceeds weight threshold (which is admin configurable) then flag order for “admin authorisation”.
     */
    public function checkWeightThreshold()
    {
        if ($this->isChangeStatus()) {
            return false;
        }

        $totalWeight = 0;

        /**
         * @var $item Mage_Sales_Model_Order_Item
         */
        foreach ($this->getItems() as $item) {
            //TODO need refactoring
            /**
             * @var $product Mage_Catalog_Model_Product
             */
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            $totalWeight += (float)$product->getWeight();
        }

        if ($totalWeight > (int)Mage::getStoreConfig(self::CONFIG_PATH_AUTHORISE . '/weight_threshold')) {
            $this->changeStatus();
        }
    }

    /**
     *  If product is a “Requires Approval Before Shipping” product then flag order for “admin  authorisation”.
     */
    public function checkBeforeShipping()
    {
        if ($this->isChangeStatus()) {
            return false;
        }

        /**
         * @var $item Mage_Sales_Model_Order_Item
         */
        foreach ($this->getItems() as $item) {
            //TODO need refactoring
            /**
             * @var $product Mage_Catalog_Model_Product
             */
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            $value = (string)$product->getResource()
                ->getAttribute('approval_before_shipping')
                ->getFrontend()
                ->getValue($product);

            if ($value === 'Yes') {
                $this->changeStatus();
            }
        }
    }

    /**
     *  If trade order then put to “Awaiting Authorisation”.
     */
    public function checkTradeCustomer()
    {
        if ($this->isChangeStatus()) {
            return false;
        }

        //TODO need refactoring
        if ($this->getOrder()->getCustomerGroupId() === "3") {
            $this->changeStatus();
        }
    }

    /**
     * If more than 9 UK Mail labels.
     */
    public function checkLabels()
    {
        $labels = $this->getLabelsByOrder();

        $this->getOrder()
            ->setShippingLabels($labels)
            ->save();

//        if (!$labels) {
//            $this->changeStatus();
//        }

        if ($labels > (int)Mage::getStoreConfig(self::CONFIG_PATH_AUTHORISE . '/labels')) {
            $this->changeStatus();
        }
    }

    /**
     * @return bool
     */
    public function changeStatus()
    {
        if (!$this->isChangeStatus()) {
            $this->setChangeStatus(true);
            $this->getOrder()->addStatusHistoryComment('Move to Awaiting Authorisation.');
            $this->getOrder()->setStatus(self::STATUS_ORDER_AUTHORISE);
            $this->getOrder()->save();
        }

        return true;
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return boolean
     */
    public function isChangeStatus()
    {
        return $this->_changeStatus;
    }

    /**
     * @param boolean $changeStatus
     */
    public function setChangeStatus($changeStatus)
    {
        $this->_changeStatus = $changeStatus;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return Vinehousefarm_Authoriselist_Helper_Data
     */
    public function setOrder($order)
    {
        $this->_order = $order;
        return $this;
    }

    /**
     * @return array
     */
    public function getShippingMethods()
    {
        return $this->_shipping_methods;
    }

    /**
     * @return array
     */
    public function getWarehouses()
    {
        $warehouse = array(
            '' => $this->__('Select warehouse')
        );

        $collection = Mage::getModel('AdvancedStock/Warehouse')->getCollection()
            ->addFieldToFilter('stock_code', array('neq' => 'NULL' ));

        foreach ($collection as $item) {
            $warehouse[$item->getStockCode()] = $item->getStockName();
        }

        return $warehouse;
    }

    /**
     * Retrieve yesterday's date
     *
     * @return string
     */
    public function getYesterdaysDate()
    {
        $date = new DateTime();
        $date->add(DateInterval::createFromDateString('- ' . Mage::getStoreConfig(self::CONFIG_PATH_PROCESSING . '/prev_days') . ' day'));

        return $date->format(self::DATA_FORMAT);
    }

    /**
     * @return string
     */
    public function getTodayDate()
    {
        $date = new DateTime();
        $date->add(DateInterval::createFromDateString('today'));

        return $date->format(self::DATA_FORMAT);
    }

    /**
     * Create encoded string for grid filter
     *
     * @param array $data
     * @return string
     */
    public function createFilterString(array $data)
    {
        array_walk_recursive($data, array($this, '_encodeFilter'));
        $query = http_build_query($data);

        return base64_encode($query);
    }

    /**
     * @return array
     */
    public function getErpIds()
    {
        $ids = array(0);

        $collection = Mage::getModel('Orderpreparation/ordertopreparepending')->getCollection()
            ->addFieldToFilter('opp_type', array('in', array('ignored', 'fullstock')));

        if ($collection) {
            foreach ($collection as $item) {
                $ids[$item->getopp_order_id()] = $item->getopp_order_id();
            }
        }

        return $ids;
    }

    /**
     * @return mixed
     */
    public function getErpCollection()
    {
        return Mage::getModel('Orderpreparation/ordertopreparepending')->getCollection()
            ->addFieldToFilter('opp_type', array('in', array('ignored', 'fullstock')));
    }

    /**
     * @param string $value
     */
    protected function _encodeFilter(&$value)
    {
        $value = trim(rawurlencode($value));
    }

    /**
     * @return array
     */
    protected function getItems()
    {
        if (!$this->_items) {
            $this->_items = $this->getOrder()->getAllItems();
        }

        return $this->_items;
    }

    /**
     * @param $product
     * @return mixed
     */
    protected function getShippingValue($product, $id)
    {
        return str_replace(' ', '', strtolower(trim($product->getResource()->getAttribute('ships_method')->getFrontend()->getOPtion($id))));
    }

    /**
     * @return int
     */
    public function getLabelsByOrder()
    {
        $labels = 0;
        /**
         * @var $item Mage_Sales_Model_Order_Item
         */
        foreach ($this->getItems() as $item) {
            /**
             * @var $product Mage_Catalog_Model_Product
             */
            $product = $item->getProduct();

            $value = (int)$product->getResource()->getAttributeRawValue($product->getId(), 'number_labels', $this->getOrder()->getStoreId());

            $labels += $value;
        }

        return (int)$labels;
    }

    /**
     * @param $stockData
     */
    public function stockProduct($product, $stockData)
    {
        try {
            $productId = $product->getId();
            //save stocks information
            if (Mage::getSingleton('admin/session')->isAllowed('admin/erp/products/view/stock/stocks'))
            {
                $stocks = mage::helper('AdvancedStock/Product_Base')->getStocks($productId);
                foreach ($stocks as $stock) {
                    //store main information
                    foreach ($stockData as $key => $value) {
                        $stock->setData($key, $value);
                    }

                    //store stocks info
                    $usedDefautNotifyStockQty = 0;
                    if ($this->_getRequest()->getPost('use_config_notify_stock_qty_' . $stock->getId()) == 1)
                        $usedDefautNotifyStockQty = 1;
                    $usedDefautIdealStockLevel = 0;
                    if ($this->_getRequest()->getPost('use_config_ideal_stock_level_' . $stock->getId()) == 1)
                        $usedDefautIdealStockLevel = 1;
                    $stock->setuse_config_notify_stock_qty($usedDefautNotifyStockQty);
                    $stock->setuse_config_ideal_stock_level($usedDefautIdealStockLevel);
                    $stock->setshelf_location($this->_getRequest()->getPost('shelf_location_' . $stock->getId()));
                    $stock->setis_favorite_warehouse($this->_getRequest()->getPost('is_favorite_warehouse_'.$stock->getId()));
                    if ($this->_getRequest()->getPost('notify_stock_qty_' . $stock->getId()) != '') {
                        $stock->setnotify_stock_qty($this->_getRequest()->getPost('notify_stock_qty_' . $stock->getId()));
                    }
                    if ($this->_getRequest()->getPost('ideal_stock_level_' . $stock->getId()) != '') {
                        $stock->setideal_stock_level($this->_getRequest()->getPost('ideal_stock_level_' . $stock->getId()));
                    }
                    $stock->seterp_exclude_automatic_warning_stock_level_update($this->_getRequest()->getPost('erp_exclude_automatic_warning_stock_level_update_'.$stock->getId()));
                    $stock->save();
                }
            }

            //associate new warehouse (if required)
            $associateWarehouseData = $this->_getRequest()->getPost('affect_to_warehouse');
            if ($associateWarehouseData) {
                if ($associateWarehouseData['warehouse_id']) {
                    $warehouseId = $associateWarehouseData['warehouse_id'];
                    $preferedStockLevel = $associateWarehouseData['prefered_stock_level'];
                    $idealStockLevel = $associateWarehouseData['ideal_stock_level'];
                    $isFavorite = $associateWarehouseData['is_favorite'];

                    $newStockItem = mage::getModel('cataloginventory/stock_item')->createStock($productId, $warehouseId);
                    if ($isFavorite)
                        $newStockItem->setis_favorite_warehouse($isFavorite);
                    if ($preferedStockLevel)
                        $newStockItem->setuse_config_notify_stock_qty(0)->setnotify_stock_qty($preferedStockLevel);
                    if ($idealStockLevel)
                        $newStockItem->setuse_config_ideal_stock_level(0)->setideal_stock_level($idealStockLevel);
                    $newStockItem->save();
                }
            }

            //process barcodes & serials
            if (Mage::getSingleton('admin/session')->isAllowed('admin/erp/products/view/barcode/edit_barcode'))
            {
                try
                {
                    $string = $this->_getRequest()->getPost('barcodes');
                    mage::helper('AdvancedStock/Product_Barcode')->saveBarcodesFromString($productId, $string);
                }
                catch(Exception $ex)
                {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('This barcode is already used for another product'));
                    Mage::logException($ex->getMessage());
                }
            }

            $string = $this->_getRequest()->getPost('serials_to_add');
            if ($string)
                $insertedSerials = mage::helper('AdvancedStock/Product_Serial')->addSerialsFromString($productId, $string);

            //dispatch event to allow other extension to save data for their own tabs
            $postData = $this->_getRequest()->getPost();
            Mage::dispatchEvent('advancedstock_product_sheet_save', array('product' => $product, 'post_data' => $postData));

        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured : ') . $ex->getMessage());
        }
    }

    /**
     * @param $item
     * @return bool
     */
    public function isDropShipItem($item)
    {
        //TODO need refactoring
        /**
         * @var $product Mage_Catalog_Model_Product
         */
        $product = Mage::getModel('catalog/product')->load($item->getProductId());

        $dropship = (string)$product->getResource()
            ->getAttribute('dropship')
            ->getFrontend()
            ->getValue($product);

        if ($dropship === 'Yes') {
            return true;
        }

        return false;
    }

    public function isSupplierItem($item)
    {
        //TODO need refactoring
        /**
         * @var $product Mage_Catalog_Model_Product
         */
        $product = Mage::getModel('catalog/product')->load($item->getProductId());

        $supplier = (int)$product->getSupplier();

        if ($supplier) {
            return true;
        }

        return false;
    }
}