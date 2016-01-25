<?php


class MDN_SmartReport_Block_Report_Type extends Mage_Adminhtml_Block_Template
{

    public function getReports()
    {
        $reports = Mage::getModel('SmartReport/Report')->getReports($this->getGroup());
        //echo "<pre>"; var_dump($reports); die();

        return $reports;
    }

    public function getGroup()
    {
        return strtolower($this->getRequest()->getActionName());
    }

    public function getVariables()
    {
        return Mage::helper('SmartReport')->getVariables();
    }

    public function getVariable($key)
    {
        $vars = $this->getVariables();
        if (isset($vars[$key]))
            return $vars[$key];
        else
            return '';
    }

    public function getTitle()
    {
        return Mage::helper('SmartReport')->getName().' - '.$this->__($this->getGroup());
    }

    public function getFormHiddens()
    {
        return array();
    }

    public function getBackUrl()
    {

    }

    public function getAdditionalFilters()
    {
        return array();
    }

    public function getAdditionalButtons()
    {
        return array();
    }

    public function canDisplay()
    {
        return true;
    }

    public function sortReports($reports)
    {
        usort($reports, array("MDN_SmartReport_Model_Report", "sortByWidth"));
        return $reports;
    }

    public function getGoupByDateOptions()
    {
        return Mage::getSingleton('SmartReport/System_Config_Source_GroupByDate')->getAllOptions();
    }

    public function getPeriods()
    {
        $values =  Mage::getSingleton('SmartReport/System_Config_Source_Periods')->getAllOptionsWitDates();
        return $values;
    }

    public function isFormLess()
    {
        return false;
    }

}
