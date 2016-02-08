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
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml coupons report grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */


if (version_compare(Mage::getVersion(), '1.4.1.0', 'ge'))
{
    class Aitoc_Aitloyalty_Block_Rewrite_AdminhtmlReportSalesCouponsGrid extends Mage_Adminhtml_Block_Report_Sales_Coupons_Grid
    {
        protected function _prepareColumns()
        {
            $return = parent::_prepareColumns();

            unset($this->_columns['subtotal_amount']);
            unset($this->_columns['discount_amount']);
            unset($this->_columns['total_amount']);

            $this->addColumn('period', array(
                'header'            => Mage::helper('salesrule')->__('Period'),
                'index'             => 'period',
                'width'             => 100,
                'sortable'          => false,
                'period_type'       => $this->getPeriodType(),
                'renderer'          => 'adminhtml/report_sales_grid_column_renderer_date',
                'totals_label'      => Mage::helper('salesrule')->__('Total'),
                'subtotals_label'   => Mage::helper('salesrule')->__('Subtotal'),
                'html_decorators' => array('nobr'),
            ));

            $this->addColumn('coupon_code', array(
                'header'    => Mage::helper('salesrule')->__('Coupon Code'),
                'sortable'  => false,
                'index'     => 'coupon_code'
            ));

            $this->addColumn('coupon_uses', array(
                'header'    => Mage::helper('salesrule')->__('Number of Uses'),
                'sortable'  => false,
                'index'     => 'coupon_uses',
                'total'     => 'sum',
                'type'      => 'number'
            ));

            if ($this->getFilterData()->getStoreIds()) {
                $this->setStoreIds(explode(',', $this->getFilterData()->getStoreIds()));
            }
            $currencyCode = $this->getCurrentCurrencyCode();


            $this->addColumn('subtotal_amount_actual', array(
                'header'        => Mage::helper('salesrule')->__('Subtotal Amount'),
                'sortable'      => false,
                'type'          => 'currency',
                'currency_code' => $currencyCode,
                'total'         => 'sum',
                'index'         => 'subtotal_amount_actual'
            ));

            $this->addColumn('discount_amount_actual', array(
                'header'        => $this->__('Discount(-)/Surcharge(+) Amount'),
                'sortable'      => false,
                'type'          => 'currency',
                'currency_code' => $currencyCode,
                'total'         => 'sum',
                'index'         => 'discount_amount_actual',
                'renderer'      => 'aitloyalty/renderer_discount'
            ));

            $this->addColumn('total_amount_actual', array(
                'header'        => Mage::helper('salesrule')->__('Total Amount'),
                'sortable'      => false,
                'type'          => 'currency',
                'currency_code' => $currencyCode,
                'total'         => 'sum',
                'index'         => 'total_amount_actual'
            ));

            return $return;
        }
    }
}
elseif (version_compare(Mage::getVersion(), '1.4.0.0', 'ge') && version_compare(Mage::getVersion(), '1.4.1.0', 'lt'))
{
    class Aitoc_Aitloyalty_Block_Rewrite_AdminhtmlReportSalesCouponsGrid extends Mage_Adminhtml_Block_Report_Grid
    {
        public function __construct()
        {
            parent::__construct();
            $this->setId('gridCoupons');
            $this->setSubReportSize(false);
        }

        protected function _prepareCollection()
        {
            parent::_prepareCollection();
            $this->getCollection()->initReport('reports/coupons_collection');
        }

        protected function _prepareColumns()
        {
            $this->addColumn('coupon_code', array(
                'header'    => $this->__('Coupon Code'),
                'sortable'  => false,
                'index'     => 'coupon_code'
            ));

            $this->addColumn('uses', array(
                'header'    => $this->__('Number of Use'),
                'sortable'  => false,
                'index'     => 'uses',
                'total'     => 'sum',
                'type'      => 'number'
            ));

            $currency_code = $this->getCurrentCurrencyCode();

            $this->addColumn('subtotal', array(
                'header'        => $this->__('Subtotal Amount'),
                'sortable'      => false,
                'type'          => 'currency',
                'currency_code' => $currency_code,
                'index'         => 'subtotal',
                'total'         => 'sum',
                'renderer'  =>'adminhtml/report_grid_column_renderer_currency'
            ));

            $this->addColumn('discount', array(
                'header'        => $this->__('Discount(-)/Surcharge(+) Amount'),
                'sortable'      => false,
                'type'          => 'currency',
                'currency_code' => $currency_code,
                'index'         => 'discount',
                'total'         => 'sum',
                'renderer'  =>'adminhtml/report_grid_column_renderer_currency'
            ));

            $this->addColumn('total', array(
                'header'        => $this->__('Total Amount'),
                'sortable'      => false,
                'type'          => 'currency',
                'currency_code' => $currency_code,
                'index'         => 'total',
                'total'         => 'sum',
                'renderer'  =>'adminhtml/report_grid_column_renderer_currency'
            ));

            $this->addExportType('*/*/exportCouponsCsv', Mage::helper('reports')->__('CSV'));
            $this->addExportType('*/*/exportCouponsExcel', Mage::helper('reports')->__('Excel'));

            return parent::_prepareColumns();
        }
    }
}