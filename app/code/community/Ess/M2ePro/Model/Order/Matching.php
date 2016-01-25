<?php

/*
 * @copyright  Copyright (c) 2013 by  ESS-UA.
 */

class Ess_M2ePro_Model_Order_Matching extends Ess_M2ePro_Model_Abstract
{
    // ########################################

    public function _construct()
    {
        parent::_construct();
        $this->_init('M2ePro/Order_Matching');
    }

    // ########################################

    public function getProductId()
    {
        return (int)$this->getData('product_id');
    }

    public function getType()
    {
        return (int)$this->getData('type');
    }

    public function getInputVariationOptions()
    {
        return $this->getSettings('input_variation_options');
    }

    public function getOutputVariationOptions()
    {
        return $this->getSettings('output_variation_options');
    }

    public function getComponent()
    {
        return $this->getData('component');
    }

    // ########################################

    public static function create(
        $productId,
        array $input,
        array $output,
        $component,
        $hash = null
    ) {
        if (is_null($productId) || count($input) == 0 || count($output) == 0) {
            throw new InvalidArgumentException('Invalid matching data.');
        }

        if (is_null($hash)) {
            $hash = self::generateHash($input);
        }

        /** @var Ess_M2ePro_Model_Mysql4_Order_Matching_Collection $matchingCollection */
        $matchingCollection = Mage::getModel('M2ePro/Order_Matching')->getCollection();
        $matchingCollection->addFieldToFilter('product_id', (int)$productId);
        $matchingCollection->addFieldToFilter('hash', $hash);

        /** @var Ess_M2ePro_Model_Order_Matching $matching */
        $matching = $matchingCollection->getFirstItem();

        $matching->addData(array(
            'product_id'               => (int)$productId,
            'input_variation_options'  => json_encode($input),
            'output_variation_options' => json_encode($output),
            'hash'                     => $hash,
            'component'                => $component,
        ));

        $matching->save();
    }

    public static function generateHash(array $input)
    {
        if (count($input) == 0) {
            return null;
        }

        return sha1(serialize($input));
    }

    // ########################################
}