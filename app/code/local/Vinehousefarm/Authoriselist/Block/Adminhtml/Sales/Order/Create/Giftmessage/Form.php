<?php
/**
 * @package Default (Template) Project.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */ 
class Vinehousefarm_Authoriselist_Block_Adminhtml_Sales_Order_Create_Giftmessage_Form extends Mage_Adminhtml_Block_Sales_Order_Create_Giftmessage_Form
{
    /**
     * Retrieve default value for giftmessage sender
     *
     * @return string
     */
    public function getDefaultSender()
    {
        return '';

        if(!$this->getEntity()) {
            return '';
        }

        if($this->_getSession()->getCustomer()->getId()) {
            return $this->_getSession()->getCustomer()->getName();
        }

        $object = $this->getEntity();

        if ($this->getEntity()->getQuote()) {
            $object = $this->getEntity()->getQuote();
        }

        return $object->getBillingAddress()->getName();
    }

    /**
     * Retrieve default value for giftmessage recipient
     *
     * @return string
     */
    public function getDefaultRecipient()
    {
        return '';

        if(!$this->getEntity()) {
            return '';
        }

        $object = $this->getEntity();

        if ($this->getEntity()->getOrder()) {
            $object = $this->getEntity()->getOrder();
        }
        else if ($this->getEntity()->getQuote()){
            $object = $this->getEntity()->getQuote();
        }

        if ($object->getShippingAddress()) {
            return $object->getShippingAddress()->getName();
        }
        else if ($object->getBillingAddress()) {
            return $object->getBillingAddress()->getName();
        }

        return '';
    }
}