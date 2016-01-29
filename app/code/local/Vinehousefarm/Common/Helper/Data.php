<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Common_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH_HIDE_FRONTEND_SHIPPING_METHODS = 'vinehousefarm/vinehousefarm_general/frontend_hidden_methods';
    const CONFIG_PATH_HIDE_FRONTEND_DEFAULT_SHIPPING_METHOD = 'vinehousefarm/vinehousefarm_general/default_shipping';
    const CONFIG_PATH_DROPSHIP_NAME = 'vinehousefarm/vinehousefarm_general/dropship_name';
    const CONFIG_PATH_DROPSHIP_EMAIL = 'vinehousefarm/vinehousefarm_general/dropship_email';
    const CONFIG_PATH_DROPSHIP_DEVELOPER = 'vinehousefarm_develop/developer/enabled';
    const CONFIG_PATH_DROPSHIP_DEVELOPER_NAME = 'vinehousefarm_develop/developer/dropship_name';
    const CONFIG_PATH_DROPSHIP_DEVELOPER_EMAIL = 'vinehousefarm_develop/developer/dropship_email';

    const WAREHOUSE_CODE = 'warehouse';

    protected $group_options = array();

    /**
     * @return array|mixed
     */
    public function getHiddenFrontendShippingMethods()
    {
        $methods = Mage::getStoreConfig(self::CONFIG_PATH_HIDE_FRONTEND_SHIPPING_METHODS);
        $methods = explode(',', $methods);

        return $methods;
    }

    /**
     * @param $post
     * @return string
     */
    public function getPostCategory($post)
    {
        $category = Mage::getModel('wordpress/term')->getCollection()
            ->addPostIdFilter($post->getId())
            ->getFirstItem();

        if ($category->getId()) {
            return $category;
        }

        return NULL;
    }

    /**
     * @param $_product
     * @param int $group_id
     * @return string
     */
    public function getProductGroup($_product, $group_id = 0)
    {
        if (!$this ->group_options) {
            $this->group_options = $_product->getResource()->getAttribute('product_group')->getSource()->getAllOptions(false);
        }

        if ($this->group_options) {
            foreach ($this->group_options as $option) {
                if (isset($option['value'])) {
                    if ($option['value'] === $this->getProductGrouptId($_product)) {
                        return $option['label'];
                    }
                }
            }
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isDeveloperMode()
    {
        return (bool) Mage::getStoreConfig(self::CONFIG_PATH_DROPSHIP_DEVELOPER);
    }

    /**
     * @return mixed
     */
    public function getDeveloperName()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_DROPSHIP_DEVELOPER_NAME);
    }

    /**
     * @return mixed
     */
    public function getDeveloperEmail()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_DROPSHIP_DEVELOPER_EMAIL);
    }

    /**
     * @return mixed
     */
    public function getDefaultShippingMethod()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_HIDE_FRONTEND_DEFAULT_SHIPPING_METHOD);
    }

    /**
     * @return mixed
     */
    public function getDropshipName()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_DROPSHIP_NAME);
    }

    /**
     * @return mixed
     */
    public function getDropshipEmail()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_DROPSHIP_EMAIL);
    }

    /**
     * @param $item
     * @return bool
     */
    public function isWarehouse($item)
    {
        if ($item->getWarehouseCode() == self::WAREHOUSE_CODE) {
            return true;
        }

        return false;
    }

    /**
     * @param $_product
     * @return mixed
     */
    public function getProductGrouptId($_product)
    {
        if (!$_product->hasData('product_group')) {
            $_product = Mage::getModel('catalog/product')->load($_product->getId());
        }

        return $_product->getProductGroup();
    }
}