<?php
/**
 * Magento Webshopapps Shipping Module
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
 * Shipping MatrixRates
 *
 * @category   Webshopapps
 * @package    Webshopapps_Premiumzone
 * @copyright   Copyright (c) 2013 Zowta Ltd (http://www.WebShopApps.com)
 *              Copyright, 2013, Zowta, LLC - US license
 * @license    http://www.webshopapps.com/license/license.txt
 * @author     Karen Baker <sales@webshopapps.com>
*/

class Webshopapps_Premiumzone_Helper_Data extends Mage_Core_Helper_Abstract
{



    protected static $_outofstock = null;
    protected static $_instock = null;


	public function processZipcode($readAdaptor, $customerPostcode,&$twoPhaseFiltering,
		&$zipString, &$shortMatchPostcode, &$longMatchPostcode ) {
			
        $debug = Mage::helper('wsalogger')->isDebug('Webshopapps_Premiumzone');
		//$zipRangeSet = Mage::getStoreConfig("carriers/premiumzone/zip_range"); //TODO sort out for backward compatability
		//$ukFiltering = Mage::getStoreConfig("carriers/premiumzone/uk_postcode"); //TODO sort out for backward compatability
        $postcodeFilter = Mage::getStoreConfig("carriers/premiumzone/postcode_filter");       
        Mage::helper('wsalogger/log')->postDebug('premiumzone','Postcode Filter',$postcodeFilter,$debug);	
        
		$customerPostcode = trim($customerPostcode);
		$twoPhaseFiltering = false;
		if ($postcodeFilter == 'numeric' && is_numeric($customerPostcode)) {			
			$zipString = ' AND '.$customerPostcode.' BETWEEN dest_zip AND dest_zip_to )';
			
		} else if ($postcodeFilter == 'uk' && strlen($customerPostcode)>4) {
			$twoPhaseFiltering = true;
			$longPostcode=substr_replace($customerPostcode,"",-3);
			$longMatchPostcode = trim($longPostcode);
			$shortMatchPostcode = preg_replace('/\d/','', $longMatchPostcode);
			$shortMatchPostcode = $readAdaptor->quoteInto(" AND STRCMP(LOWER(dest_zip),LOWER(?)) = 0)", $shortMatchPostcode);
		}  else if ($postcodeFilter == 'uk_numeric') {
			if(is_numeric($customerPostcode)){
				$zipString = ' AND '.$customerPostcode.' BETWEEN dest_zip AND dest_zip_to )';
			} else {
				$twoPhaseFiltering = true;
				$longPostcode=substr_replace($customerPostcode,"",-3);
				$longMatchPostcode = trim($longPostcode);
				$shortMatchPostcode = preg_replace('/\d/','', $longMatchPostcode);
				$shortMatchPostcode = $readAdaptor->quoteInto(" AND STRCMP(LOWER(dest_zip),LOWER(?)) = 0)", $shortMatchPostcode);
			}
		} else if ($postcodeFilter == 'canada') { 
			// first search complete postcode
			// then search exact match on first 3 chars
			// then search range
			$shortPart = substr($customerPostcode,0,3);
			if (strlen($shortPart) < 3 || !is_numeric($shortPart[1]) || !ctype_alpha($shortPart[2])) {
				$zipString = $readAdaptor->quoteInto(" AND ? LIKE dest_zip )", $customerPostcode);
			} else {
				$zipFromRegExp='^'.$shortPart[0].'[0-'.$shortPart[1].'][A-'.$shortPart[2].']$';
				$zipToRegExp='^'.$shortPart[0].'['.$shortPart[1].'-9]['.$shortPart[2].'-Z]$';
				$shortMatchPostcode = $readAdaptor->quoteInto(" AND dest_zip REGEXP ?", $zipFromRegExp).$readAdaptor->quoteInto(" AND dest_zip_to REGEXP ? )",$zipToRegExp );
				$longMatchPostcode = $customerPostcode;
				$twoPhaseFiltering = true;
			}
		} else {
			 $zipString = $readAdaptor->quoteInto(" AND ? LIKE dest_zip )", $customerPostcode);
		}
		
		if ($debug) {
        	Mage::helper('wsalogger/log')->postDebug('premiumzone','Postcode Range Search String',$zipString);	
        	if ($twoPhaseFiltering) {
        		Mage::helper('wsalogger/log')->postDebug('premiumzone','Postcode 2 Phase Search String','short match:'.$shortMatchPostcode.
        			', long match:'.$longMatchPostcode);	
        	}
    	}
				
	}


    /**
     * Checks to see if products in cart out of stock. Sets 2 static variables. Doesn't return anything
     * @param $items
     */
    protected function _checkOutOfStock($items) {

        // check if statics are null, if not just return
        if (self::$_outofstock==NULL) {

            // make statics false
            self::$_outofstock = false;
            self::$_instock = false;

            foreach($items as $item) {
                if ($item->getBackorders() != Mage_CatalogInventory_Model_Stock::BACKORDERS_NO) {
                    self::$_outofstock=true;
                } else {
                    self::$_instock=true;
                }
            }
        }

    }


    public function isOutOfStock($items) {

        $this->_checkOutOfStock($items);

        return self::$_outofstock;
    }


    public function isInStock($items) {

        $this->_checkOutOfStock($items);

        return self::$_instock;

    }


}