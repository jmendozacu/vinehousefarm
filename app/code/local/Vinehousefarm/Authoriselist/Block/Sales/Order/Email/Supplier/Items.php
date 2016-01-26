<?php
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
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Sales Order Email order items
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Vinehousefarm_Authoriselist_Block_Sales_Order_Email_Supplier_Items extends Mage_Sales_Block_Items_Abstract
{
    /**
     * Return product type for quote/order item
     *
     * @param Varien_Object $item
     * @return string
     */
    protected function _getItemType(Varien_Object $item)
    {
        if ($item->getOrderItem()) {
            $type = $item->getOrderItem()->getProductType();
        } elseif ($item instanceof Mage_Sales_Model_Quote_Address_Item) {
            $type = $item->getQuoteItem()->getProductType();
        } else {
            $type = $item->getProductType();

            if (!Mage::helper('authoriselist')->isSupplierItem($item)) {
                if ($item->getOrder()->getCurrentSupplier()) {
                    if ($item->getProduct()->getSupplier() !== $item->getOrder()->getCurrentSupplier()->getSupId()) {
                        $type = 'nodisplay';
                    }
                }
            }
        }
        return $type;
    }

    /**
     * Retrieve item renderer block
     *
     * @param string $type
     * @return Mage_Core_Block_Abstract
     */
    public function getItemRenderer($type)
    {
        if (!isset($this->_itemRenders[$type])) {
            $type = 'default';
        }

        if (is_null($this->_itemRenders[$type]['renderer'])) {
            $this->_itemRenders[$type]['renderer'] = $this->getLayout()
                ->createBlock($this->_itemRenders[$type]['block'])
                ->setTemplate($this->_itemRenders[$type]['template'])
                ->setRenderedBlock($this);
        }
        return $this->_itemRenders[$type]['renderer'];
    }
}
