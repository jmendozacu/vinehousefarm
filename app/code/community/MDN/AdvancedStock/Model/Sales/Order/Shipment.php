<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2015 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : Olivier ZIMMERMANN
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MDN_AdvancedStock_Model_Sales_Order_Shipment extends Mage_Sales_Model_Order_Shipment {

    /**
     * Prevent strictly to ship non reserved orders
     */
    protected function _beforeSave() {

        parent::_beforeSave();

        //ERP Strict Mode, it is not possible to ship if the product has not been reserved
        if (Mage::getStoreConfig('advancedstock/general/prevent_non_reserved_shipment')) {

            //Security is active only on shipment creation, not on shipment update in code
            if (!$this->getOrigData('entity_id')) {

                //Browse each shipment item and check if they have been reserved
                foreach ($this->getAllItems() as $item) {
                    $orderItem = $item->getOrderItem();

                    //check if product manages stock
                    $productStock = mage::getModel('cataloginventory/stock_item')->load($item->getproduct_id(), 'product_id');
                    if ((!$productStock->getId()) || (!$productStock->ManageStock()))
                        continue;

                    //prevent to have wrong stock movement from magento native ship button (or other button)
                    //that are is not from ERP order preparation screen (which by nature prevent this)
                    $reservedQty = $orderItem->getreserved_qty();
                    if(!$reservedQty){
                        $reservedQty = 0;
                    }

                    $qtyRequestedToBeShipped = $item->getqty();

                    if ($reservedQty < 1 || $reservedQty < $qtyRequestedToBeShipped) {
                        $errorMessage = mage::helper('AdvancedStock')->__("Cannot ship this order because the product %s has %s reserved quantity and shipment requested quantity is %s", $orderItem->getname(),$reservedQty, $qtyRequestedToBeShipped);

                        //prevent the user
                        Mage::getSingleton('adminhtml/session')->addError($errorMessage);

                        //Block the shipment creation
                        throw new Exception($errorMessage);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Create stock movement when order is shipped
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterSave() {
        try {
            //appel le parent
            parent::_afterSave();

            //Define if shipment just created
            $newShipmentId = $this->getentity_id();
            $creation = ($newShipmentId != $this->getOrigData('entity_id'));
            if ($creation) {

                //Create stock movements
                $order = $this->getOrder();
                foreach ($this->getAllItems() as $item) {
                    try {
                        
                        //retrieve informaiton
                        $orderItem = $item->getOrderItem();
                        $qty = $this->getRealShippedQtyForItem($item);
                        $orderPreparationWarehouse = $orderItem->getPreparationWarehouse();

                       //if order preparation is empty,
                        if (!$orderPreparationWarehouse->getId())
                        {
                            $preparationWarehouseId = mage::helper('AdvancedStock/Router')->getWarehouseForOrderItem($orderItem, $order);
                            $orderPreparationWarehouse = Mage::getModel('AdvancedStock/Warehouse')->load($preparationWarehouseId);
                        }

                        //check if product manages stock
                        $productStock = mage::getModel('cataloginventory/stock_item')->load($item->getproduct_id(), 'product_id');
                        if ((!$productStock->getId()) || (!$productStock->ManageStock()))
                            continue;

                        if ($orderPreparationWarehouse) {
                            $additionalDatas = array('sm_ui' => $item->getId(), 'sm_type' => 'order');
                            mage::getModel('AdvancedStock/StockMovement')->createStockMovement($item->getproduct_id(),
                                    $orderPreparationWarehouse->getId(),
                                    null,
                                    $qty,
                                    mage::helper('AdvancedStock')->__('Shipment for order #') . $this->getOrder()->getincrement_id(),
                                    $additionalDatas);
                        }
                        else
                            throw new Exception(mage::helper('AdvancedStock')->__('Cant find warehouse for orderitem #' . $orderItem->getId()));

                        //reset reserved qty
                        $productId = $item->getproduct_id();
                        $oldReservedQty = $orderItem->getreserved_qty();
                        $newReservedQty = $oldReservedQty - $qty;
                        if ($newReservedQty < 0)
                            $newReservedQty = 0;
                        
                        $orderItem->getErpOrderItem()->setreserved_qty($newReservedQty)->save();

                        //updates
                        mage::helper('AdvancedStock/Product_Reservation')->storeReservedQtyForStock($productStock, $productId); 
                        
                        mage::helper('AdvancedStock/Product_Base')->planUpdateStocksWithBackgroundTask($productId, 'from shipment #'.$newShipmentId);

                        $debug = 'CreateShipment StackTrace for PID='.$productId.' WarehouseId='.$orderPreparationWarehouse->getId().' newReservedQty='.$newReservedQty;
                        try{
                          throw new Exception('LogException');
                        }catch(Exception $ex){
                          $stackTrace = $ex->getTraceAsString();
                          if(!empty($stackTrace)){
                            $debug .= $stackTrace;
                          }
                        }
                        if(!empty($debug)){
                          mage::log($debug, null, 'erp_create_shipment.log');
                        }

                    } catch (Exception $ex) {                        
                        mage::log($ex->getMessage(), null, 'erp_create_shipment.log');
                    }
                }

                //dispatch event
                $this->setOrigData('entity_id', $newShipmentId);
                Mage::dispatchEvent('salesorder_shipment_aftercreate', array('shipment' => $this, 'order' => $order));
            }
        } catch (Exception $ex) {
            Mage::log('Error in MDN_AdvancedStock_Model_Sales_Order_Shipment : ' . $ex->getMessage());
            throw new Exception($ex->getMessage());
        }


        return $this;
    }

    /**
     * Return real shipped qty for an item
     * Welcome in magento.....
     *
     * @param unknown_type $item
     */
    public function getRealShippedQtyForItem($item) {
        //init vars
        $qty = $item->getQty();
        $orderItem = $item->getOrderItem();
        $orderItemParentId = $orderItem->getparent_item_id();

        //define if we have to multiply qty by parent qty
        $mustMultiplyByParentQty = false;
        if ($orderItemParentId > 0) {
            $parentOrderItem = mage::getmodel('sales/order_item')->load($orderItemParentId);
            if ($parentOrderItem->getId()) {
                //if shipped together
                if ((($parentOrderItem->getproduct_type() == 'bundle') && (!$parentOrderItem->isShipSeparately())) || ($parentOrderItem->getproduct_type() == 'configurable')) {
                    $mustMultiplyByParentQty = true;
                    $qty = ($orderItem->getqty_ordered() / $parentOrderItem->getqty_ordered());
                }
            }
        }

        //if multiply by parent qty
        if ($mustMultiplyByParentQty) {
            $parentShipmentItem = null;
            foreach ($item->getShipment()->getAllItems() as $ShipmentItem) {
                if ($ShipmentItem->getorder_item_id() == $orderItemParentId)
                    $parentShipmentItem = $ShipmentItem;
            }
            if ($parentShipmentItem) {
                $qty = $qty * $parentShipmentItem->getQty();
            }
        }

        return $qty;
    }

}