<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */ 
class Vinehousefarm_Authoriselist_Helper_AdvancedStock_Router extends MDN_AdvancedStock_Helper_Router
{
    public function getWarehouseForOrderItem($orderItem, $order)
    {
        //if product doesnt manage stock, return null
        $productId = $orderItem->getproduct_id();
        $stockItem = mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
        if (!$stockItem)
            return null;

        //get warehouses
        $storeId = $order->getStore();
        $defaultWarehouseId = mage::getStoreConfig('advancedstock/router/default_warehouse', $storeId);
        $favoriteWarehouseId = $this->getFavoriteWarehouseId($orderItem, $order);
        $warehouseWithStockId = $this->getWarehouseWithStockId($orderItem, $order);



        //TODO check choise warehouse in  MOTO
        if ($orderItem->getWarehouseCode()) {
            $collectionWarehouse = mage::getModel('AdvancedStock/Warehouse')->getCollection()
                ->addFieldToFilter('stock_code', $orderItem->getWarehouseCode());

            $warehouse = $collectionWarehouse->getFirstItem();

            return  $warehouse->getId();
        }

        //apply mode
        $mode = mage::getStoreConfig('advancedstock/router/priority', $storeId);
        switch ($mode) {
            case self::kModeFavoriteStockDefault :
                if ($favoriteWarehouseId > 0)
                    return $favoriteWarehouseId;
                else
                    return ( $warehouseWithStockId > 0 ? $warehouseWithStockId : $defaultWarehouseId);
                break;
            case self::kModeStockFavoriteDefault :
                if ($warehouseWithStockId > 0)
                    return $warehouseWithStockId;
                else
                    return ( $favoriteWarehouseId > 0 ? $favoriteWarehouseId : $defaultWarehouseId);
                break;
            case self::kModeStockDefault :
                return ( $warehouseWithStockId > 0 ? $warehouseWithStockId : $defaultWarehouseId);
                break;
            case self::kModeFavoriteDefault :
                return ( $favoriteWarehouseId > 0 ? $favoriteWarehouseId : $defaultWarehouseId);
                break;
            case self::kModeDefaultInFirst :
                $defaultWarehouseStockCount = $this->getDefaultWarehouseStockCount($orderItem, $defaultWarehouseId);
                return ( $defaultWarehouseStockCount > 0 ? $defaultWarehouseId : $warehouseWithStockId);
                break;
            case self::kModeDefault :
                return $defaultWarehouseId;
                break;
        }

        //if we go until here, return default warehouse
        return $defaultWarehouseId;
    }
}