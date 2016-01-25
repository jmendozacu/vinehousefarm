<?php
require_once 'app/Mage.php';
Mage::app();
Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
$importDir = Mage::getBaseDir('media') . DS . 'incoming/';
$finger = new Varien_Io_File();
$finger->open(array('path' => $importDir));
$images = $finger->ls(Varien_Io_File::GREP_FILES);
$csv = new Varien_File_Csv();

$items = $csv->getData($importDir . 'images.csv');

foreach ($items as $item) {
	$productSKU = $item[0];

	if ($item[1]) {
		$filePath = $importDir . $item[1];
		if ($finger->fileExists($filePath)) {
			$ourProduct = Mage::getModel('catalog/product')->loadByAttribute('sku',$productSKU);

			if ($ourProduct) {
				$filePath = $importDir . $item[1];

				$ourProduct->addImageToMediaGallery($filePath, array('image', 'small_image', 'thumbnail'), false, false);

				try {
					$ourProduct->save();
					echo 'Success: ' . $productSKU . ' - ' . $item[1] . '</br>';
				} catch (Exception $e) {
					Mage::log('Error: ' . $productSKU . ' - ' . $e->getMessage(), null, 'import-images-error.log');
				}
			} else {
				Mage::log('Error: ' . $productSKU . ' - does not exist', null, 'import-images-error.log');
				echo 'Error: ' . $productSKU . ' - does not exist'. '</br>';
			}
		} else {

			Mage::log('Error: ' . $productSKU . ' - ' . $item[1], null, 'import-images-error.log');
			echo 'Error: ' . $productSKU . ' - ' . $item[1] . '</br>';
		}
	}
}

?>