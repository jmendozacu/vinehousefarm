<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */ 
class Vinehousefarm_Authoriselist_Helper_SagePaySuite_Data extends Ebizmarts_SagePaySuite_Helper_Data
{
    public function getSagePayConfigJson() {
        $_store = Mage::app()->getStore();
        $_url = Mage::getModel('core/url');
        $_urlAdmin = Mage::getModel('adminhtml/url');
        $_d = Mage::getDesign();

        $serverSafeFields = Mage::getModel('sagepaysuite/sagePayServer')->getConfigSafeFields();
        $directSafeFields = Mage::getModel('sagepaysuite/sagePayDirectPro')->getConfigSafeFields();

        $conf = array();

        $conf ['global'] = array_intersect_key($_store->getConfig('payment/sagepaysuite'), array_flip(array('debug', 'max_token_card')));
        $conf ['global']['not_valid_message'] = $this->__('This Sage Pay Suite module\'s license is NOT valid.');
        $conf ['global']['onepage_progress_url'] = $_url->getUrl('checkout/onepage/progress', array('_secure' => true));
        $conf ['global']['onepage_success_url'] = $_url->getUrl('checkout/onepage/success', array('_secure' => true));
        $conf ['global']['valid'] = (int) Mage::helper('sagepaysuite')->F91B2E37D34E5DC4FFC59C324BDC1157C();
        $conf ['global']['token_enabled'] = (int) Mage::getModel('sagepaysuite/sagePayToken')->isEnabled();
        $conf ['global']['sgps_saveorder_url'] = Mage::getModel('core/url')->addSessionParam()->getUrl('sgps/payment/onepageSaveOrder', array('_secure' => true));
        $conf ['global']['cart_url'] = Mage::getModel('core/url')->getUrl('checkout/cart', array('_secure' => true));
        $conf ['global']['osc_loading_image'] = '<img src="' . $_d->getSkinUrl('images/opc-ajax-loader.gif') . '" />&nbsp;&nbsp;' . $this->__('Please wait, processing your order...');
        $conf ['global']['osc_save_billing_url'] = $_url->getUrl('onestepcheckout/ajax/save_billing', array('_secure' => true));
        $conf ['global']['osc_set_methods_separate_url'] = $_url->getUrl('onestepcheckout/ajax/set_methods_separate', array('_secure' => true));
        $conf ['global']['ajax_review'] = ($this->mageVersionIs('1.5') ? '1' : '2');
        $conf ['global']['html_paymentmethods_url'] = $_url->getUrl('sgps/payment/getTokenCardsHtml', array('_secure' => true));


        if ($this->creatingAdminOrder()) {
            if ($this->motoAdminOrder()) {
                $conf ['global']['adminhtml_save_order_url'] = Mage::helper('adminhtml')->getUrl('adminhtml/moto_create/save', array('_secure' => true));
            } else {
                $conf ['global']['adminhtml_save_order_url'] = Mage::helper('adminhtml')->getUrl('adminhtml/sales_order_spsCreate/save', array('_secure' => true));
            }
        }

        if (is_array($_store->getConfig('payment/sagepayserver'))) {
            $conf ['server'] = array_intersect_key($_store->getConfig('payment/sagepayserver'), array_flip($serverSafeFields));
            $conf ['server']['sgps_registertrn_url'] = $_url->getUrl('sgps/serverPayment/registertrn', array('_secure' => true));
            $conf ['server']['sgps_admin_registertrn_url'] = $_urlAdmin->getUrl('adminhtml/spsServerPayment/registertrn', array('_secure' => true));
            $conf ['server']['osc_savebilling_url'] = $_url->getUrl('onestepcheckout/ajax/save_billing', array('_secure' => true));
            $conf ['server']['osc_setmethods_url'] = $_url->getUrl('onestepcheckout/ajax/set_methods_separate', array('_secure' => true));
            $conf ['server']['new_token_url'] = $_url->getUrl('sgps/card/serverform', array('_secure' => true));
            $conf ['server']['secured_by_image'] = $_d->getSkinUrl('sagepaysuite/images/secured-by-sage-pay.png');
        }

        $conf ['form']['url'] = $_url->getUrl('sgps/formPayment/go', array('_secure' => true));

        $conf ['direct'] = array_intersect_key($_store->getConfig('payment/sagepaydirectpro'), array_flip($directSafeFields));
        $conf ['direct']['test_data'] = Mage::helper('sagepaysuite/sandbox')->getTestDataJson();
        $conf ['direct']['sgps_registertrn_url'] = $_url->getUrl('sgps/directPayment/transaction', array('_secure' => true));
        $conf ['direct']['sgps_registerdtoken_url'] = $_url->getUrl('sgps/directPayment/registerToken', array('_secure' => true));
        $conf ['direct']['html_paymentmethods_url'] = $_url->getUrl('sgps/directPayment/getTokenCardsHtml', array('_secure' => true));
        $conf ['direct']['threed_before'] = '<h5 class="tdnote">' . $this->__('To increase the security of Internet transactions Visa and Mastercard have introduced 3D-Secure (like an online version of Chip and PIN). You have chosen to use a card that is part of the 3D-Secure scheme, so you will need to authenticate yourself with your bank in the section below.') . '</h5>';
        $conf ['direct']['threed_after'] = '<div id="direct3d-logos"><img src="' . $_d->getSkinUrl('sagepaysuite/images/mcsc_logo.gif') . '" alt="" /><img src="' . $_d->getSkinUrl('sagepaysuite/images/vbv_logo_small.gif') . '" alt="" /><img src="' . $_d->getSkinUrl('sagepaysuite/images/sage_pay_logo.gif') . '" alt="" /></div>';

        $conf ['directmoto'] = array_intersect_key($_store->getConfig('payment/sagepaydirectpro_moto'), array_flip($directSafeFields));
        $conf ['directmoto']['test_data'] = Mage::helper('sagepaysuite/sandbox')->getTestDataJson();

        $conf ['paypal']['redirect_url'] = $_url->getUrl('sgps/paypalexpress/go', array('_secure' => true));

        return Zend_Json::encode($conf);
    }

    public function motoAdminOrder()
    {
        $controllerName = Mage::app()->getRequest()->getControllerName();
        return $controllerName == 'moto_create';
    }

    public function creatingAdminOrder()
    {
        $controllerName = Mage::app()->getRequest()->getControllerName();
        return ($controllerName == 'sales_order_create' || $controllerName == 'adminhtml_sales_order_create' || $controllerName == 'sales_order_edit' || $controllerName == 'orderspro_order_edit' || $controllerName == 'moto_create');
    }
}