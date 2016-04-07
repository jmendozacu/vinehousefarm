<?php
/**
 * Helper class
 *
 * @category   Scommerce
 * @package    Scommerce_Heardaboutus
 * @author     Scommerce Mage (core@scommerce-mage.co.uk)
 */
class Scommerce_Heardaboutus_Helper_Data extends Mage_Core_Helper_Abstract
{

	
	const XML_PATH_LICENSE_KEY 				= 'customer/heardaboutus/license_key';
	/**
     * returns license key administration configuration option
     *
     * @return boolean
     */
    public function getLicenseKey(){		
        return Mage::getStoreConfig(self::XML_PATH_LICENSE_KEY);
    }
	
	/**
     * Returns indexes of the fetched array as headers for CSV
     * @param array $items
     * @return array
     */
    protected function _getCsvHeaders($items)
    {
        $item = current($items);
        $headers = array_keys($item->getData());
 
        return $headers;
    }
	
	/**
     * Generates CSV file
     * @return array
     */
    public function generateCsv()
    {
        $collection = Mage::getModel('sales/order');
		$collection = $collection->getCollection()
						->addFieldToSelect(array('increment_id','heard_about_us','store_id'))
						->addAttributeToSort('store_id','ASC');
		$collection->getSelect()->where('heard_about_us is not null and length(heard_about_us)>0')
								->order('store_id ASC');

        
        if (!is_null($collection)) {
            $items = $collection->getItems();
            if (count($items) > 0) {
                $io = new Varien_Io_File();
                $path = Mage::getBaseDir('var') . DS . 'export' . DS;
                $name = md5(microtime());
                $file = $path . DS . $name . '.csv';
                $io->setAllowCreateFolders(true);
                $io->open(array('path' => $path));
                $io->streamOpen($file, 'w+');
                $io->streamLock(true);
                $io->streamWriteCsv($this->_getCsvHeaders($items));
                foreach ($items as $item) {
                    $io->streamWriteCsv($item->getData());
                }
 
                return array(
                    'type'  => 'filename',
                    'value' => $file,
                    'rm'    => true // can delete file after use
                );
            }
        }
    }
	
	/**
     * returns whether license key is valid or not
     *
     * @return bool
     */
    public function isLicenseValid(){
		$sku = strtolower(str_replace('_Helper_Data','',str_replace('Scommerce_','',get_class($this))));		
		return Mage::helper("scommerce_core")->isLicenseValid($this->getLicenseKey(),$sku);
	}
}
