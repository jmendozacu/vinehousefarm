<?php
/**
 * Loyalty Program
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitloyalty
 * @version      2.3.20
 * @license:     U26UI6JXXc2UZmhGTqStB0pBKQbnwle1fzElfPIr8Z
 * @copyright:   Copyright (c) 2015 AITOC, Inc. (http://www.aitoc.com)
 */
class Aitoc_Aitloyalty_Block_Rewrite_Adminhtml_Sales_Order_Creditmemo_Totals extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Totals
{
    protected function _initTotals()
    {
        parent::_initTotals();
        $this->addTotal(new Varien_Object(array(
            'code'      => 'adjustment_positive',
            'value'     => $this->getSource()->getAdjustmentPositive(),
            'base_value'=> $this->getSource()->getBaseAdjustmentPositive(),
            'label'     => $this->helper('sales')->__('Adjustment Refund')
        )));
        $this->addTotal(new Varien_Object(array(
            'code'      => 'adjustment_negative',
            'value'     => $this->getSource()->getAdjustmentNegative(),
            'base_value'=> $this->getSource()->getBaseAdjustmentNegative(),
            'label'     => $this->helper('sales')->__('Adjustment Fee')
        )));

        /**
         * Add discount
         */
        $amount = (float) ( Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion("<1.8.0.0") ? -1 : 1 ) * $this->getSource()->getDiscountAmount();
        $part = Mage::helper('aitloyalty/discount')->getTitlePart($amount);
        if ($amount != 0) {
            if ($this->getSource()->getDiscountDescription()) {
                $discountLabel = $this->helper('sales')->__($part.' (%s)', $this->getSource()->getDiscountDescription());
            } else {
                $discountLabel = $this->helper('sales')->__($part);
            }
            $this->_totals['discount'] = new Varien_Object(array(
                'code'      => 'discount',
                'value'     => ( Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion("<1.8.0.0") ? -1 : 1 ) * $this->getSource()->getDiscountAmount(),
                'base_value'=> ( Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion("<1.8.0.0") ? -1 : 1 ) * $this->getSource()->getBaseDiscountAmount(),
                'label'     => $discountLabel
            ));
        }

        return $this;
    }
}