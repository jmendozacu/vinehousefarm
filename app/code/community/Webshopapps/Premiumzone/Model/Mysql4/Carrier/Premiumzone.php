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
class Webshopapps_Premiumzone_Model_Mysql4_Carrier_Premiumzone extends Mage_Core_Model_Mysql4_Abstract
{

	protected $_twoPhaseFiltering;
    protected $_shortMatchPostcode = '';
    protected $_longMatchPostcode = '';
    protected $_table;
    protected $_request;
    protected $_zipSearchString;
    protected $_debug;


	protected function _construct()
	{
		$this->_init('shipping/premiumzone', 'pk');
	}

	public function getNewRate(Mage_Shipping_Model_Rate_Request $request)
	{
		$read = $this->_getReadAdapter();
		$postcode = $request->getDestPostcode();
		$this->_table = Mage::getSingleton('core/resource')->getTableName('premiumzone_shipping/premiumzone');
		$this->_premiumzonematrix = Mage::getSingleton('core/resource')->getTableName('premiumzone_shipping/premiumzonematrix');
		$this->_debug = Mage::helper('wsalogger')->isDebug('Webshopapps_Premiumzone');
		$usingGreaterVolLogic=Mage::getStoreConfig('carriers/premiumzone/calculate_greater_volume');
		$this->_request = $request;
		$totalVolweight =0;
        $items = $request->getAllItems();

		if ($request->getPZConditionName()=='package_volweight')
		{
			$greaterVolume=true;
			$totalVolweight = $this->getVolumeWeight($request);
			if ($usingGreaterVolLogic && $request->getData('package_weight')> $totalVolweight) {
				$greaterVolume=false;
				$totalVolweight=$request->getData('package_weight');
			}
		}

		Mage::Helper('premiumzone')->processZipcode($read, $postcode,
			$this->_twoPhaseFiltering, $this->_zipSearchString, $this->_shortMatchPostcode, $this->_longMatchPostcode );


		if ($this->_twoPhaseFiltering) {
			$switchSearches=13;
		} else {
			$switchSearches=9;
		}

		$bind = array(
            ':website_id' => (int) $request->getWebsiteId(),
            ':package_value' => $request->getData('package_value'),
		    ':package_qty' => $request->getData('package_qty'),
            ':package_weight' => $request->getData('package_weight'),
            ':volume_weight' => $totalVolweight,
            ':condition_name' => $request->getPZConditionName(),
        );

        $bindZones= array(
            ':country_id' => $request->getDestCountryId(),
            ':region_id' => (int) $request->getDestRegionId(),
            ':postcode' => $request->getDestPostcode(),
		    ':city' => $request->getDestCity(),
        );



		for ($j=0;$j<$switchSearches;$j++)
		{
			$bindsRequired = $bind;

			$select = $this->getSwitchSelect($read,$j,$bindZones, $bindsRequired);
			if ($this->_debug) {
				Mage::helper('wsalogger/log')->postInfo('premiumzone','Bind Values',$bindsRequired);
			}

			if ($request->getPZConditionName()=='package_volweight') {
				if ($usingGreaterVolLogic) {
					$select->where('weight_from_value<= :volume_weight');
					$select->where('weight_to_value>= :volume_weight');
					$select->where('item_from_value<= :package_qty');
					$select->where('item_to_value>= :package_qty');
				} else {
					$select->where('weight_from_value<=?', $request->getData('package_weight'));
					$select->where('weight_to_value>=?', $request->getData('package_weight'));
					$select->where('item_from_value<= :volume_weight');
					$select->where('item_to_value>= :volume_weight');
				}
			} else {
				$select->where('weight_from_value<= :package_weight');
				$select->where('weight_to_value>= :package_weight');
				$select->where('item_from_value<= :package_qty');
				$select->where('item_to_value>= :package_qty');
			}

			$select->where('condition_name= :condition_name');
			$select->where('price_from_value<= :package_value');
			$select->where('price_to_value>= :package_value');
			$select->where('premiumzonematrix.website_id= :website_id');

			$select->order('sort_order ASC');
			/*
			 pdo has an issue. we cannot use bind
			 */
			$newData=array();
			try {
				$row = $read->fetchAll($select,$bindsRequired);
			} catch (Exception $e) {
				Mage::helper('wsalogger/log')->postCritical('premiumzone','SQL Exception',$e->getMessage(),$this->_debug);
			}


			if (!empty($row))
			{
				if ($this->_debug) {
					Mage::helper('wsalogger/log')->postInfo('premiumzone','SQL Select',$select->getPart('where'));
					Mage::helper('wsalogger/log')->postInfo('premiumzone','SQL Result',$row);
				}
				// have found a result or found nothing and at end of list!
				foreach ($row as $data) {
					if ($data['price']==-1) {
						continue;
					}
					$data['method_name']=$data['delivery_type'];
					if ($data['algorithm']!="") {
						$algorithm_array=explode("&",$data['algorithm']);  // Multi-formula extension
						reset($algorithm_array);
						$skipData=false;
						foreach ($algorithm_array as $algorithm_single) {
							$algorithm=explode("=",$algorithm_single,2);
							if (!empty($algorithm) && count($algorithm)==2) {
								if (strtolower($algorithm[0])=="w") {
									// weight based
									$weightIncrease=explode("@",$algorithm[1]);
									if (!empty($weightIncrease) && count($weightIncrease)==2 ) {
										if ($usingGreaterVolLogic && $request->getPRConditionName()=='package_volweight' && $greaterVolume ) {
											$weightDifference =	$totalVolweight-$data['weight_from_value'];
										} else {
											$weightDifference =	$request->getData('package_weight')-$data['weight_from_value'];
										}
										$quotient=ceil($weightDifference / $weightIncrease[0]);
										$data['price']=$data['price']+$weightIncrease[1]*$quotient;
									}
								} else if (strtolower($algorithm[0])=="v") {
									// volume based
									$weightIncrease=explode("@",$algorithm[1]);
									if (!empty($weightIncrease) && count($weightIncrease)==2 ) {
										$weightDifference=	$totalVolweight-$data['item_from_value'];
										$quotient=ceil($weightDifference / $weightIncrease[0]);
										$data['price']=$data['price']+$weightIncrease[1]*$quotient;
									}
								} else if (strtolower($algorithm[0])=="i") {
									// volume based
									$perItemCost=$algorithm[1];
									if (!empty($perItemCost)) {
										$numItemsAffected =	$request->getData('package_qty')-$data['item_from_value'];
										$data['price']=$data['price']+$perItemCost*$numItemsAffected;
									}
								} else if (strtolower($algorithm[0])=="ai") {
									//all items
									$itemCost=$algorithm[1];
									if (!empty($itemCost)) {
										$data['price'] = $data['price']+$itemCost*$request->getData('package_qty');
									}
                                } else if (strtolower($algorithm[0])=="instock") {
                                    // in stock - this has been deprecated, only here for backwards compatibility

                                    if (strtolower($algorithm[1]) == "true") {
                                        if (!Mage::helper('premiumzone')->isInStock($items)) {
                                            $skipData = true;
                                            break;
                                        }

                                    } elseif (strtolower($algorithm[1]) == "false")  {
                                        if (!Mage::helper('premiumzone')->isOutOfStock($items)) {
                                            $skipData = true;
                                            break;
                                        }
                                    }
                                } else if (strtolower($algorithm[0])=="stock") {

                                    // in stock
                                    $inStock = Mage::helper('premiumzone')->isInStock($items);
                                    $outOfStock = Mage::helper('premiumzone')->isOutOfStock($items);
                                    $stockStatus = strtolower($algorithm[1]);


                                    if ($this->_debug) {
                                        Mage::helper('wsalogger/log')->postInfo('premiumzone','In Stock',$inStock);
                                        Mage::helper('wsalogger/log')->postInfo('premiumzone','Out of Stock',$outOfStock);
                                        Mage::helper('wsalogger/log')->postInfo('premiumzone','Stock Check',$stockStatus);
                                    }

                                    if ($stockStatus == "only_in" && (!$inStock || $outOfStock) ) {
                                        $skipData = true;
                                        break;
                                    } elseif ($stockStatus == "only_out" && (!$outOfStock || $inStock) ) {
                                        $skipData = true;
                                        break;
                                    } elseif ($stockStatus == "both" && (!$outOfStock || !$inStock))  {
                                        $skipData = true;
                                        break;
                                    }
                                    break;
                                } else if (strtolower($algorithm[0])=="m") {
									$data['method_name']=$algorithm[1];
								}
							}
						}
						if ($skipData) {
							continue;
						}
					}
					$newData[]=$data;
				}
				break;
			} else {
				if ($this->_debug) {
					Mage::helper('wsalogger/log')->postDebug('premiumzone','SQL Select',$select->getPart('where'));
				}
			}
		}
		if(!empty($newData)){ return $newData;} else return;
	}

	private function getSwitchSelect($read,$j,$bindZones, &$bindsRequired)
	{

		$select = $read->select()->from(array('premiumzone'=>$this->_table))
						->joinLeft(array('premiumzonematrix' => $this->_premiumzonematrix),
							'premiumzonematrix.dest_zone = premiumzone.dest_zone AND premiumzonematrix.website_id = premiumzone.website_id');

		if($this->_twoPhaseFiltering) {
			switch($j) {
				case 0:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
						" AND STRCMP(LOWER(dest_zip),LOWER(?)) = 0)", $this->_longMatchPostcode
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					$bindsRequired[':postcode'] 	= $bindZones[':postcode'];
					break;
				case 1:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
					$this->_shortMatchPostcode
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					$bindsRequired[':postcode'] 	= $bindZones[':postcode'];
					break;
				case 2:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND dest_city=''".
						" AND STRCMP(LOWER(dest_zip),LOWER(?)) = 0)", $this->_longMatchPostcode
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					$bindsRequired[':postcode'] 	= $bindZones[':postcode'];
					break;
				case 3:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND dest_city=''".
					$this->_shortMatchPostcode
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					$bindsRequired[':postcode'] 	= $bindZones[':postcode'];
					break;
				case 4:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
						"  AND dest_zip='')"
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					break;
				case 5:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = 0 AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
						" AND STRCMP(LOWER(dest_zip),LOWER(?)) = 0)", $this->_longMatchPostcode
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					$bindsRequired[':postcode'] 	= $bindZones[':postcode'];
					break;
				case 6:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = 0 AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
					$this->_shortMatchPostcode
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					$bindsRequired[':postcode'] 	= $bindZones[':postcode'];
					break;
				case 7:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = 0 AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
							" AND dest_zip='') "
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					break;
				case 8:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = 0 AND  dest_city=''".
								" AND STRCMP(LOWER(dest_zip),LOWER(?)) = 0)", $this->_longMatchPostcode
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':postcode'] 	= $bindZones[':postcode'];

					break;
				case 9:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = 0 AND  dest_city=''".
					$this->_shortMatchPostcode
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':postcode'] 	= $bindZones[':postcode'];
					break;
				case 10:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND  dest_city='' AND dest_zip='')");
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					break;
				case 11:
					$select->where("  (dest_country_id= :country_id AND dest_region_id='0' AND dest_city='' AND dest_zip='') "
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					break;

				case 12:
					$select->where("  (dest_country_id='0' AND dest_region_id='0' AND dest_zip='')" );
					break;
			}
		}
		else {
			switch($j) {
				case 0:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
					$this->_zipSearchString
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					break;
				case 1:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND dest_city=''".
					$this->_zipSearchString
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					break;
				case 2:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
						" AND dest_zip='')"
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					break;
				case 3:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = '0' AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
					$this->_zipSearchString
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					break;
				case 4:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = '0' AND STRCMP(LOWER(dest_city),LOWER(:city)) = 0".
					" AND dest_zip='') "
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':city'] 		= $bindZones[':city'];
					break;
				case 5:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = '0' AND dest_city=''".
					$this->_zipSearchString
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					break;
				case 6:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = :region_id AND dest_city='' AND dest_zip='')"
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					$bindsRequired[':region_id'] 	= $bindZones[':region_id'];
					break;

				case 7:
					$select->where(" (dest_country_id = :country_id AND dest_region_id = '0' AND dest_city='' AND dest_zip='')"
					);
					$bindsRequired[':country_id'] 	= $bindZones[':country_id'];
					break;

				case 8:
					$select->where("  (dest_country_id='0' AND dest_region_id='0' AND dest_zip='')" );
					$bindsRequired = array();
					break;
			}
		}
		return $select;
	}


	private function getVolumeWeight($request) {
		$total_volweight=0;
		$configurableQty = 0;
		$items = $request->getAllItems();
		foreach($items as $item) {
			$currentQty = $item->getQty();
			if ($item->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
				$configurableQty = $currentQty;
				continue;
			} elseif ($configurableQty > 0) {
				$currentQty = $configurableQty;
				$configurableQty = 0;
			}
			$parentQty = 1;
			if ($item->getParentItem()!=null) {
				if ($item->getParentItem()->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
					$parentQty = $item->getParentItem()->getQty();
				}
			}
			$qty=$currentQty * $parentQty;

			$product=Mage::getModel('catalog/product')->load( $item->getProductId() );
			$total_volweight  += ($product->getVolumeWeight()*$qty);

		}
		return $total_volweight;
	}

	/**
	 * CSV Import routine - Import the zones and countries
	 * @param $object
	 * @return unknown_type
	 */
	public function uploadAndImportZones(Varien_Object $object)
	{

		$csvFile = $_FILES["groups"]["tmp_name"]["premiumzone"]["fields"]["zones_import"]["value"];
		$csvName = $_FILES["groups"]["name"]["premiumzone"]["fields"]["import"]["value"];
		$session = Mage::getSingleton('adminhtml/session');

		if (!empty($csvFile)) {

			$csv = trim(file_get_contents($csvFile));

			$table = Mage::getSingleton('core/resource')->getTableName('premiumzone_shipping/premiumzone');
		//	Mage::helper('wsacommon/shipping')->saveCSV($csv,$csvName);

			$websiteId = $object->getScopeId();
			$websiteModel = Mage::app()->getWebsite($websiteId);

			if (!empty($csv)) {
				$exceptions = array();
				$csvLines = explode("\n", $csv);
				$csvLine = array_shift($csvLines);
				$csvLine = $this->_getCsvValues($csvLine);
				if (count($csvLine) < 6) {
					$exceptions[0] = Mage::helper('shipping')->__('Invalid Premium Zone File Format');
				}

				$countryCodes = array();
				$regionCodes = array();
				foreach ($csvLines as $k=>$csvLine) {
					$csvLine = $this->_getCsvValues($csvLine);
					if (count($csvLine) > 0 && count($csvLine) < 6) {
						$exceptions[0] = Mage::helper('shipping')->__('Invalid Premium Zone File Format %s',$csvLine);
					} else {
						$splitCountries = explode(",", trim($csvLine[1]));
						$splitRegions = explode(",", trim($csvLine[2]));
						foreach ($splitCountries as $country) {
							$countryCodes[] = trim($country);
						}
						foreach ($splitRegions as $region) {
							$regionCodes[] = $region;
						}
					}
				}

				if (empty($exceptions)) {
					$connection = $this->_getWriteAdapter();

					$condition = array(
					$connection->quoteInto('website_id = ?', $websiteId),
					);
					$connection->delete($table, $condition);



				}
				if (!empty($exceptions)) {
					throw new Exception( "\n" . implode("\n", $exceptions) );
				}


				if (empty($exceptions)) {
					$data = array();
					$countryCodesToIds = array();
					$regionCodesToIds = array();
					$countryCodesIso2 = array();

					$countryCollection = Mage::getResourceModel('directory/country_collection')->addCountryCodeFilter($countryCodes)->load();
					foreach ($countryCollection->getItems() as $country) {
						$countryCodesToIds[$country->getData('iso3_code')] = $country->getData('country_id');
						$countryCodesToIds[$country->getData('iso2_code')] = $country->getData('country_id');
						$countryCodesIso2[] = $country->getData('iso2_code');
					}

					$regionCollection = Mage::getResourceModel('directory/region_collection')
					->addRegionCodeFilter($regionCodes)
					->addCountryFilter($countryCodesIso2)
					->load();


					foreach ($regionCollection->getItems() as $region) {
						$regionCodesToIds[$countryCodesToIds[$region->getData('country_id')]][$region->getData('code')] = $region->getData('region_id');
					}

					foreach ($csvLines as $k=>$csvLine) {
						$csvLine = $this->_getCsvValues($csvLine);
						$splitCountries = explode(",", trim($csvLine[1]));
						$splitRegions = explode(",", trim($csvLine[2]));
						$splitPostcodes = explode(",",trim($csvLine[4]));

						if ($csvLine[3] == '*' || $csvLine[3] == '') {
							$city = '';
						} else {
							$city = $csvLine[3];
						}

						if ($csvLine[5] == '*' || $csvLine[5] == '') {
							$zip_to = '';
						} else {
							$zip_to = $csvLine[5];
						}

						if ($csvLine[0] == '*' || $csvLine[0] == '') {
							$exceptions[] = Mage::helper('shipping')->__('Zone field cannot be empty');
							$destZone='';
						} else {
							$destZone = $csvLine[0];
						}

						foreach ($splitCountries as $country) {

							$country=trim($country);

							if (empty($countryCodesToIds) || !array_key_exists($country, $countryCodesToIds)) {
								$countryId = '0';
								if ($country != '*' && $country != '') {
									$exceptions[] = Mage::helper('shipping')->__('Invalid Country "%s" in the Row #%s', $country, ($k+1));
								}
							} else {
								$countryId = $countryCodesToIds[$country];
							}

							foreach ($splitRegions as $region) {

								if (!isset($countryCodesToIds[$country])
								|| !isset($regionCodesToIds[$countryCodesToIds[$country]])
								|| !array_key_exists($region, $regionCodesToIds[$countryCodesToIds[$country]])) {
									$regionId = '0';
									if ($region != '*' && $region != '') {
										$exceptions[] = Mage::helper('shipping')->__('Invalid Region/State "%s" in the Row #%s', $region, ($k+1));
									}
								} else {
									$regionId = $regionCodesToIds[$countryCodesToIds[$country]][$region];
								}

								foreach ($splitPostcodes as $postcode) {
									$new_zip_to=$zip_to;
									if ($postcode == '*' || $postcode == '') {
										$zip = '';
									} else {
										$zip_str = explode("-", $postcode);
										if(count($zip_str) != 2)
										{
											$zip = trim($postcode);
											if (ctype_digit($postcode) && trim($zip_to) == '') {
												$new_zip_to = trim($postcode);
											} else $new_zip_to = $zip_to;
										}
										else {
											$zip = trim($zip_str[0]);
											$new_zip_to = trim($zip_str[1]);
										}
									}

									$data[] = array('website_id'=>$websiteId, 'dest_zone'=>$destZone,
										'dest_country_id'=>$countryId, 'dest_region_id'=>$regionId,
										'dest_city'=>$city, 'dest_zip'=>$zip, 'dest_zip_to'=>$new_zip_to);
								}
							}
						}
					}
				}
				if (empty($exceptions)) {
					foreach($data as $k=>$dataLine) {
						try {
							$connection->insert($table, $dataLine);
						} catch (Exception $e) {
							$exceptions[] = Mage::helper('shipping')->__('Duplicate Row #%s (Country "%s", Region/State "%s", Zip "%s")', ($k+1), $dataLine['dest_country_id'], $dataLine['dest_region_id'], $dataLine['dest_zip']);
							$exceptions[] = $e;
							break;
						}
					}
					Mage::helper('wsacommon/shipping')->updateStatus($session,count($data));

				}
				if (!empty($exceptions)) {
					throw new Exception( "\n" . implode("\n", $exceptions) );
				}
			}
		}
	}


	/**
	 * TODO Refactor
	 */
	private function lookupZones() {
		$collection = Mage::getResourceModel('premiumzone_shipping/carrier_premiumzone_collection');

		$read = $this->_getReadAdapter();
		$select = $read->select()->from(
			Mage::getSingleton('core/resource')->getTableName('premiumzone_shipping/premiumzone'),
			'dest_zone');
		$select->group('dest_zone');
		$rows = $read->fetchAll($select);
		$zoneArr=array();
		foreach ($rows as $key=>$zone) {
			$zoneArr[]=$zone['dest_zone'];
		}
		return $zoneArr;

	}


	/**
	 * Import the shipping rules
	 * @param Varien_Object $object
	 */
	public function uploadAndImport(Varien_Object $object)
	{
		$csvFile = $_FILES["groups"]["tmp_name"]["premiumzone"]["fields"]["import"]["value"];
		$csvName = $_FILES["groups"]["name"]["premiumzone"]["fields"]["import"]["value"];
		$session = Mage::getSingleton('adminhtml/session');

		if (empty($csvFile)) {
			return;
		}

		$csv = trim(file_get_contents($csvFile));

		$table = Mage::getSingleton('core/resource')->getTableName('premiumzone_shipping/premiumzonematrix');

		Mage::helper('wsacommon/shipping')->saveCSV($csv,$csvName);

		$websiteId = $object->getScopeId();
		$websiteModel = Mage::app()->getWebsite($websiteId);
		/*
		 getting condition name from post instead of the following commented logic
		 */

		if (isset($_POST['groups']['premiumzone']['fields']['condition_name']['inherit'])) {
			$conditionName = (string)Mage::getConfig()->getNode('default/carriers/premiumzone/condition_name');
		} else {
			$conditionName = $_POST['groups']['premiumzone']['fields']['condition_name']['value'];
		}

		$conditionFullName = Mage::getModel('premiumzone_shipping/carrier_premiumzone')->getCode('condition_name_short', $conditionName);
		if (!empty($csv)) {
			$exceptions = array();
			$csvLines = explode("\n", $csv);
			$csvLine = array_shift($csvLines);
			$csvLine = $this->_getCsvValues($csvLine);
			if (count($csvLine) < 11) {
				$exceptions[0] = Mage::helper('shipping')->__('Invalid Premium Zone Shipping File Format');
			}

			$zoneLookupArr = $this->lookupZones();

			foreach ($csvLines as $k=>$csvLine) {
				$csvLine = $this->_getCsvValues($csvLine);
				if (!in_array(trim($csvLine[0]), $zoneLookupArr)) {
					$exceptions[0] = Mage::helper('shipping')->__('Unknown zone %s',$csvLine[0]);
					break;
				}
			}

			if (empty($exceptions)) {
				$connection = $this->_getWriteAdapter();

				$condition = array(
				$connection->quoteInto('website_id = ?', $websiteId),
				$connection->quoteInto('condition_name = ?', $conditionName),
				);
				$connection->delete($table, $condition);
			}
			if (!empty($exceptions)) {
				throw new Exception( "\n" . implode("\n", $exceptions) );
			}

			$data = array();
			$counter = 0;

			foreach ($csvLines as $k=>$csvLine) {

				$csvLine = $this->_getCsvValues($csvLine);

				if ($csvLine[0] == '*' || $csvLine[0] == '') {
					$exceptions[] = Mage::helper('shipping')->__('Zone field cannot be empty');
					$destZone='';
				} else {
					$destZone = $csvLine[0];
					if (!in_array($destZone, $zoneLookupArr)) {
						$exceptions[] = Mage::helper('shipping')->__('Zone %s not present in zones table',$destZone);
						$destZone='';
					}
				}

				if ( $csvLine[1] == '*' || $csvLine[1] == '') {
					$weightFrom = 0;
				} else if (!$this->_isPositiveDecimalNumber($csvLine[1]) ) {
					$exceptions[] = Mage::helper('shipping')->__('Invalid Weight From "%s" in the Row #%s',  $csvLine[1], ($k+1));
				} else {
					$weightFrom = (float)$csvLine[1];
				}


				if ( $csvLine[2] == '*' || $csvLine[2] == '') {
					$weightTo = 10000000;
				} else if (!$this->_isPositiveDecimalNumber($csvLine[2]) ) {
					$exceptions[] = Mage::helper('shipping')->__('Invalid Weight To "%s" in the Row #%s',  $csvLine[2], ($k+1));
				} else {
					$weightTo = (float)$csvLine[2];
				}

				if ( $csvLine[3] == '*' || $csvLine[3] == '') {
					$priceFrom = 0;
				} else if (!$this->_isPositiveDecimalNumber($csvLine[3]) ) {
					$exceptions[] = Mage::helper('shipping')->__('Invalid Price From "%s" in the Row #%s',  $csvLine[3], ($k+1));
				} else {
					$priceFrom = (float)$csvLine[3];
				}


				if ( $csvLine[4] == '*' || $csvLine[4] == '') {
					$priceTo = 10000000;
				} else if (!$this->_isPositiveDecimalNumber($csvLine[4]) ) {
					$exceptions[] = Mage::helper('shipping')->__('Invalid Price To "%s" in the Row #%s',  $csvLine[4], ($k+1));
				} else {
					$priceTo = (float)$csvLine[4];
				}

				if ( $csvLine[5] == '*' || $csvLine[5] == '') {
					$itemFrom = 0;
				} else {
					$itemFrom = $csvLine[5];
				}


				if ( $csvLine[6] == '*' || $csvLine[6] == '') {
					$itemTo = 10000000;
				} else {
					$itemTo = $csvLine[6];
				}

				if ( $csvLine[8] == '*' || $csvLine[8] == '') {
					$algorithm = '';
				} else {
					$algorithm=$csvLine[8];
				}
				if ( $csvLine[10] == '*' || $csvLine[10] == '') {
					$sortOrder = 0;
				} else {
					$sortOrder=$csvLine[10];
				}

				if (!empty($exceptions)) {
					break;
				}
				$data[] = array('website_id'=>$websiteId, 'dest_zone'=>$destZone,  'condition_name'=>$conditionName,
									'weight_from_value'=>$weightFrom,'weight_to_value'=>$weightTo,
									'price_from_value'=>$priceFrom,'price_to_value'=>$priceTo,
									'item_from_value'=>$itemFrom,'item_to_value'=>$itemTo,
									'price'=>$csvLine[7], 'algorithm'=>$algorithm, 'delivery_type'=>$csvLine[9], 'sort_order'=>$sortOrder);


				$counter++;

				$dataStored = false;
				if (!empty($exceptions)) {
					break;
				}

				if($counter>1000) {
					foreach($data as $k=>$dataLine) {
						try {
							$connection->insert($table, $dataLine);
						} catch (Exception $e) {
							$messageStr = Mage::helper('shipping')->__('Error# 302 - Duplicate Row #%s (Zone "%s")',
									($k+1), $dataLine['dest_zone']);


							Mage::log($messageStr);
							Mage::helper('wsalogger/log')->postWarning('premiumzone','Duplicate Row',$messageStr,$this->_debug,
									'302','http://wiki.webshopapps.com/troubleshooting-guide/duplicate-row-error');

							//$exceptions[] = Mage::helper('shipping')->__($e);
						}
					}
					Mage::helper('wsacommon/shipping')->updateStatus($session,count($data));
					$counter = 0;
					unset($data);
					$dataStored = true;
				}
			}
		}

		if(empty($exceptions) && !$dataStored) {
			foreach($data as $k=>$dataLine) {
				try {
					$connection->insert($table, $dataLine);
				} catch (Exception $e) {
					$messageStr = Mage::helper('shipping')->__('Error# 302 - Duplicate Row #%s (Zone "%s")',
					($k+1), $dataLine[$k]['dest_zone']);

					Mage::log($messageStr);

					Mage::helper('wsalogger/log')->postWarning('premiumzone','Duplicate Row',$messageStr,$this->_debug,
					302,'http://wiki.webshopapps.com/troubleshooting-guide/duplicate-row-error');

				}
			}
			Mage::helper('wsacommon/shipping')->updateStatus($session,count($data));

		}
		if (!empty($exceptions)) {
			throw new Exception( "\n" . implode("\n", $exceptions) );
		}
	}

	private function _getCsvValues($string, $separator=",")
	{
		$elements = explode($separator, trim($string));
		for ($i = 0; $i < count($elements); $i++) {
			$nquotes = substr_count($elements[$i], '"');
			if ($nquotes %2 == 1) {
				for ($j = $i+1; $j < count($elements); $j++) {
					if (substr_count($elements[$j], '"') > 0) {
						// Put the quoted string's pieces back together again
						array_splice($elements, $i, $j-$i+1, implode($separator, array_slice($elements, $i, $j-$i+1)));
						break;
					}
				}
			}
			if ($nquotes > 0) {
				// Remove first and last quotes, then merge pairs of quotes
				$qstr =& $elements[$i];
				$qstr = substr_replace($qstr, '', strpos($qstr, '"'), 1);
				$qstr = substr_replace($qstr, '', strrpos($qstr, '"'), 1);
				$qstr = str_replace('""', '"', $qstr);
			}
			$elements[$i] = trim($elements[$i]);
		}
		return $elements;
	}

	private function _isPositiveDecimalNumber($n)
	{
		return preg_match ("/^[0-9]+(\.[0-9]*)?$/", $n);
	}

}
