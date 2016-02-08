<?php

/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Helper_Orderpreparation_PickingList extends MDN_Orderpreparation_Helper_PickingList
{
    /**
     * Return picking list PDF
     */
    public function getPickListPdf($orders, $type = 'office') {

        //generate PDF depending of the method
        if (!mage::getModel('Orderpreparation/ordertoprepare')->countOrders(MDN_Orderpreparation_Model_OrderToPrepare::filterSelected))
            throw new Exception('There is no orders in selected orders');

        if (Mage::getStoreConfig('orderpreparation/picking_list/mode') == MDN_Orderpreparation_Model_Source_PickingListMode::kMerged) {
            //merged mode, all products are displayed in the same document (mass picking)
            $obj = mage::getModel('Orderpreparation/Pdf_PickingList');
            $products = $this->GetProductsSummary();
            $pdf = $obj->getPdf(array(
                'comments' => '',
                'products' => $products,
                'orders' => $orders,
                'type' => $type,
                'periods' => $this->getSelectedPreiods(),
            ));
        }

        return $pdf;
    }

    /**
     * Return picking list PDF
     */
    public function getAdviceSlipsPdf($orders, $type) {

        //generate PDF depending of the method
        if (!mage::getModel('Orderpreparation/ordertoprepare')->countOrders(MDN_Orderpreparation_Model_OrderToPrepare::filterSelected))
            throw new Exception('There is no orders in selected orders');

        if (Mage::getStoreConfig('orderpreparation/picking_list/mode') == MDN_Orderpreparation_Model_Source_PickingListMode::kMerged) {
            //merged mode, all products are displayed in the same document (mass picking)
            $obj = Mage::getModel('vinehousefarm_common/orderpreparation_pdf_adviceList');
            $products = $this->GetProductsSummary();
            $pdf = $obj->getPdf(
                array(
                    'comments' => '',
                    'products' => $products,
                    'orders' => $orders,
                    'type' => $type,
                    'title' => $this->__('Advice Slips')
                )
            );
        }

        return $pdf;
    }

    protected function getSelectedPreiods()
    {
        $data = array();

        $grid = Mage::getBlockSingleton('authoriselist/adminhtml_processing_grid');

        if ($grid) {
            $filter = $grid->getParam($grid->getVarNameFilter(), null);
            $data = $grid->helper('adminhtml')->prepareFilterString($filter);

            if (array_key_exists('created_at', $data)) {
                if (array_key_exists('from', $data['created_at']) && array_key_exists('from', $data['created_at'])) {
                    $result = array(
                        'from' => $data['created_at']['from'],
                        'to' => $data['created_at']['to']
                    );

                    $data = $result;

                    unset($result);
                }
            }
        }

        return $data;
    }
}