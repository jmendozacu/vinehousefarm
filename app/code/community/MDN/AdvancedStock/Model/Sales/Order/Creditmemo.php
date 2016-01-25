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
 * @copyright  Copyright (c) 2009 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : Olivier ZIMMERMANN
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MDN_AdvancedStock_Model_Sales_Order_Creditmemo extends Mage_Sales_Model_Order_Creditmemo {

    /**
     * Unreserve qty for evry product of a credit memo
     *  - instantly at order level
     *  - plan unreservation at product level using background task
     *
     */
    protected function _afterSave() {
        parent::_afterSave();

        //browse products of the credit memo
        foreach ($this->getAllItems() as $item) {

            $productId = $item->getproduct_id();
            if(!$productId)
                return;

            $productStockManagement = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
            if ($productStockManagement->getManageStock()) {

                $orderItem = $item->getOrderItem();
                if($orderItem){
                    $erpOrderItem = $orderItem->getErpOrderItem();
                    if($erpOrderItem){

                        $oldReservedQty = $orderItem->getreserved_qty();
                        $newReservedQty = $oldReservedQty - $item->getqty();

                        if ($newReservedQty < 0){
                            $newReservedQty = 0;
                        }

                        //update reserved qty at order level
                        $erpOrderItem->setreserved_qty($newReservedQty)->save();

                        //plan stock updates to adjust reserved qty at product level
                        mage::helper('AdvancedStock/Product_Base')->planUpdateStocksWithBackgroundTask($productId, 'from credit memo aftersave');

                        //because if a customer cancel an item non reserved, ERP must re Dispatch the order
                        if($oldReservedQty == 0 && $newReservedQty == 0){
                            Mage::dispatchEvent('advancedstock_order_item_reserved_qty_changed', array('order_item' => $orderItem));
                        }
                    }
                }
            }
        }

        mage::dispatchEvent('advancedstock_creditmemo_aftersave', array('creditmemo' => $this));
    }

}