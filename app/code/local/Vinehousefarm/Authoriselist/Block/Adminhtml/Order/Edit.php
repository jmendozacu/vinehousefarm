<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Authoriselist_Block_Adminhtml_Order_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        // $this->_objectId = 'id';
        parent::__construct();
        $this->_blockGroup      = 'authoriselist';
        $this->_controller = 'adminhtml_order';
        $this->addButton('order_next', array(
            'label'   => Mage::helper('authoriselist')->__('Next Order'),
            'onclick' => "setLocation('{$this->getUrl('*/*/view')}')",
        ));
        $this->removeButton('save');
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->_mode = 'edit';
    }

    public function getOrder()
    {
        $this->_getModel();
        return $this->getParentBlock()->getOrder();
    }

    public function getCountNext()
    {
        return $this->_getHelper()->getCountNext();
    }

    protected function _getHelper()
    {
        return Mage::helper('authoriselist');
    }

    protected function _getModel()
    {
        /**
         * @var $collection Mage_Sales_Model_Resource_Order_Collection
         */
        $collection = Mage::getModel('authoriselist/order')->getCollection();

        $collection->addAttributeToFilter('status', array('in' => array(Vinehousefarm_Authoriselist_Helper_Data::STATUS_ORDER_AUTHORISE)));

        if (!Mage::registry('current_order')) {
            Mage::register('current_order', $collection->getFirstItem());
        }

        $this->getParentBlock()->setOrder(Mage::registry('current_order'));
        $this->getParentBlock()->setOrderInfoData(array('no_use_order_link' => true));

        return Mage::registry('current_order');
    }

    protected function _getModelTitle() {
        return 'Awaiting Authorisation';
    }

    public function getHeaderText()
    {
        $model = $this->_getModel();
        $modelTitle = $this->_getModelTitle();
        if ($model && $model->getId()) {
           return $this->_getHelper()->__("Edit $modelTitle (ID: {$model->getId()})");
        }
    }

    /**
     * Get form save URL
     *
     * @deprecated
     * @see getFormActionUrl()
     * @return string
     */
    public function getSaveUrl()
    {
        $this->setData('form_action_url', 'save');
        return $this->getFormActionUrl();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getDatePicker()
    {
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save'),
            'method'    => 'post'
        ));
        $element = new Varien_Data_Form_Element_Date(
            array(
                'name' => 'shipping_arrival_date',
                'label' => Mage::helper('sales')->__('Date'),
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                'value' => date(
                    Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                    strtotime($this->getOrder()->getShippingArrivalDate())
                )
            )
        );
        $element->setForm($form);
        $element->setId('shipping_arrival_date');
        return $element->getElementHtml();
    }

    public function getShippingArrivalDate()
    {
        return Mage::helper('vinehousefarm_deliverydate')->getFormatedDeliveryDateToSave(str_replace('/','-',$this->getOrder()->getShippingArrivalDate()));
    }

    public function getOrderLabels()
    {
        if (!$this->getOrder()->getShippingLabels()) {
            $helper = $this->_getHelper()->setOrder($this->getOrder());
            return $helper->getLabelsByOrder();
        }

        return (int) $this->getOrder()->getShippingLabels();
    }

    public function getDayOff()
    {
        $result = array();

        $collection = Mage::getModel('vinehousefarm_deliverydate/deliverydate')->getCollection()
            ->addFieldToFilter('status', 1);

        foreach ($collection as $item)
        {
            $result[] = $item->getHolidayTime();
        }

        return implode(',', $result);
    }
}
