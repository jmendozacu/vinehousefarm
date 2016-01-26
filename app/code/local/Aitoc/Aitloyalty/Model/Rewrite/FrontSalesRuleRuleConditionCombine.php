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
/**
 * @copyright  Copyright (c) 2009 AITOC, Inc. 
 */

class Aitoc_Aitloyalty_Model_Rewrite_FrontSalesRuleRuleConditionCombine extends Mage_SalesRule_Model_Rule_Condition_Combine
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getNewChildSelectOptions()
    {
        $addressCondition = Mage::getModel('salesrule/rule_condition_address');
        $addressAttributes = $addressCondition->loadAttributeOptions()->getAttributeOption();
        $attributes = array();
        foreach ($addressAttributes as $code=>$label) {
            $attributes[] = array('value'=>'salesrule/rule_condition_address|'.$code, 'label'=>$label);
        }

//        $pAttributes = array(
//            array(
//                'value' => 'salesrule/rule_condition_product|custom_design_from',
//                'label' => 'Test Atrr',
//            ),
//        );
        
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, array(
            array(
                'value' =>  'Aitoc_Aitloyalty_Model_Rule_Condition_Customer_Subselect', 
                'label' =>  Mage::helper('aitloyalty')->__('Customer data subselection'),
            ),
        ));
        return $conditions;
    }
    
    public function validate(Varien_Object $object)
    {
        if (!$this->getConditions()) {
            return true;
        }
        $bResult   = false;
        $bMeetCond = false;
        foreach ($this->getConditions() as $cond) {
            if ($cond instanceof Aitoc_Aitloyalty_Model_Rule_Condition_Customer_Subselect)
            {
                $iStoreId = Mage::app()->getStore()->getId();
                $iSiteId  = Mage::app()->getWebsite()->getId();

                /* {#AITOC_COMMENT_END#}
                $performer = Aitoc_Aitsys_Abstract_Service::get()->platform()->getModule('Aitoc_Aitloyalty')->getLicense()->getPerformer();
                $ruler     = $performer->getRuler();
                if (!($ruler->checkRule('store', $iStoreId, 'store') || $ruler->checkRule('store', $iSiteId, 'website')))
                {
                    return false;
                }
                {#AITOC_COMMENT_START#} */
                
                $bMeetCond = true;
                if ($this->getValue())
                {
                    // If ALL/ANY of these conditions are TRUE 
                    $bResult = $cond->validate($object) || false;
                } else 
                {
                    // If ALL/ANY of these conditions are FALSE 
                    $bResult = !($cond->validate($object) || false);
                }
            }
        }
        if ($bMeetCond)
        {
            if ('any' == $this->getAggregator())
            {
                return $bResult || parent::validate($object);
            } else 
            {
                return $bResult && parent::validate($object);
            }
        } else 
        {
            return parent::validate($object);
        }
    }
}