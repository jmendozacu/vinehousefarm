<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Common_Block_Catalog_Product_View_Simples extends Mage_Core_Block_Template
{
    protected $products;
    protected $product;

    /**
     * @return array
     */
    public function getSimpleProducts($product = false)
    {


        $this->products = false;
        if (!$this->getProducts()) {
            if (!$product){
                $product = $this->getProduct();
            }
            if ($product->isConfigurable()) {
                $collection = $product->getTypeInstance()->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();

                if ($collection) {
                    if ($collection->getSize()) {

                        $options = $product->getTypeInstance()->getConfigurableOptions($product);
                        $attributes = $product->getTypeInstance()->getUsedProductAttributes($product);

                        foreach ($collection as $item) {
                            foreach ($attributes as $id => $attribute) {
                                if (array_key_exists($id, $options)) {
                                    foreach ($options[$id] as $option) {
                                        if (array_key_exists('sku', $option)) {
                                            if ($option['sku'] === $item->getSku()) {
                                                $item->setOptionTitle($option['option_title']);
                                                $item->setSuperAttributeId($id);
                                                $item->setSupperAttributeValueId($item->getData($attribute->getAttributeCode()));
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $this->setProducts($collection);
                    }
                }
            }
        } 
        return $this->getProducts();
    }

    public function getIsSaleable($product)
    {
        $productAvailabilityStatus = Mage::getModel('SalesOrderPlanning/ProductAvailabilityStatus')->load($product->getId(), 'pa_product_id');
        return $productAvailabilityStatus->getIsSaleable();
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

//    public function setProduct($product){
////        $this->product = $product;
////        $this->_product = $product;
////        Mage::register('product', $product);
//    }

//    public function getProductId(){
//        if (isset($this->product)){
//            return $this->product->getId();
//        } else {
//            return false;
//        }
//    }

    /**
     * Retrieve current product model
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!Mage::registry('product') && $this->getProductId()) {
            $product = Mage::getModel('catalog/product')->load($this->getProductId());
            Mage::register('product', $product);
        } else if ($this->getProductId()){
            if (Mage::registry('product') && Mage::registry('product') == $this->getProductId()) {
                $this->setData('product', Mage::registry('product'));
            } else {
                $product = Mage::getModel('catalog/product')->load($this->getProductId());
                Mage::register('product', $product);
            }
        } else {
            if (Mage::registry('product')) {
                $this->setData('product', Mage::registry('product'));
            }
        }

        return $this->getData('product');
    }
}