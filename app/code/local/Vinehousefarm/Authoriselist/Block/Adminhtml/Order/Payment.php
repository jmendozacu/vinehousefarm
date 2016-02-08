<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Block_Adminhtml_Order_Payment extends Mage_Adminhtml_Block_Sales_Order_Payment
{
    public function setPayment($payment)
    {
        $paymentInfoBlock = Mage::helper('payment')->getInfoBlock($payment)->setTemplate('payment/info/default.phtml');;
        $this->setChild('info', $paymentInfoBlock);
        $this->setData('payment', $payment);
        return $this;
    }
}
