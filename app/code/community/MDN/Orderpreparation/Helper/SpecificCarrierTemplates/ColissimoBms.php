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
class MDN_Orderpreparation_Helper_SpecificCarrierTemplates_ColissimoBms extends MDN_Orderpreparation_Helper_SpecificCarrierTemplates_Abstract {

    /**
     * Generate XML output
     *
     * @param unknown_type $orderPreparationCollection
     */
    public function createExportFile($orderPreparationCollection) {


        $trackings = array();

        foreach ($orderPreparationCollection as $orderToPrepare) {

            //check shipping method
            $order = mage::getModel('sales/order')->load($orderToPrepare->getorder_id());
            $shipmentId = $orderToPrepare->getshipment_id();
            $shipment = Mage::getModel('sales/order_shipment')->loadByIncrementId($shipmentId);

            if (!$this->checkShippingMethod($order))
                continue;

            foreach($shipment->getAllTracks() as $track)
            {
                $trackings[] = $track->getNumber();
            }

        }

        return Mage::getModel('colissimo/Pdf_Label')->getForSeveralTrackings($trackings);
    }
    /**
     * Method to import trackings
     * @param <type> $t_lines
     */
    public function importTrackingFile($t_lines) {

        throw new Exception('Not implemented');
    }

    /**
     * Check that shipping method is UPS
     * @param <type> $order
     */
    protected function checkShippingMethod($order)
    {
        $shippingMethod = strtolower($order->getShippingDescription());

        return preg_match('/colissimo/', $shippingMethod);
    }

}