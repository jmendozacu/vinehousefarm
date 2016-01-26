<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2016
 */

class Vinehousefarm_Ukmail_Helper_Label extends Mage_Core_Helper_Abstract
{
    /**
     * Return the base media directory for labels
     *
     * @return string
     */
    public function getBaseDir()
    {
        return Mage::getModel('ukmail/label_config')->getBaseMediaPath();
    }

    /**
     * Return the Base URL for labels
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return Mage::getModel('ukmail/label_config')->getBaseMediaUrl();
    }

    public function getOrderLabels(Vinehousefarm_Ukmail_Model_Label $label)
    {
        $files = array();
        $order = Mage::getModel('sales/order')->load($label->getOrderId());

        $baseDir = $this->getBaseDir() . DS . $label->getCollectionJobNumber() . DS . $label->getConsignmentNumber() . DS . $order->getIncrementId();

        $io = new Varien_Io_File();
        $io->open(array('path' => $baseDir));

        foreach ($io->ls(Varien_Io_File::GREP_FILES) as $item) {
            if ($item['is_image']) {
                $files[] = $baseDir . DS . $item['text'];
            }
        }

        return $files;
    }

    /**
     * @param Vinehousefarm_Ukmail_Model_Service_Label $label
     * @return array
     * @throws Exception
     */
    public function save(Vinehousefarm_Ukmail_Model_Service_Label $label)
    {
        $files = array();

        foreach ($label->getLabels() as $key => $item) {

            $baseDir = $this->getBaseDir() . DS . $label->getBookCollection()->getCollectionJobNumber() . DS . $label->getConsignmentNumber() . DS . $label->getOrder()->getIncrementId();

            $io = new Varien_Io_File();
            $io->checkAndCreateFolder($baseDir);
            $io->open(array('path' => $baseDir));

            $io->streamOpen('label-' . $key . '.png');
            $io->streamWrite($item);
            $io->streamClose();

            $files[] = $baseDir . DS . 'label-' . $key . '.png';
        }

       return $files;
    }

    public function remove(Vinehousefarm_Ukmail_Model_Label $label)
    {
        $baseDir = $this->getBaseDir() . DS . $label->getCollectionJobNumber() . DS . $label->getConsignmentNumber();

        $io = new Varien_Io_File();
        return $io->rmdirRecursive($baseDir);
    }

    /**
     * @param $allLabels
     * @throws Zend_Pdf_Exception
     */
    public function getLabelPdf($allLabels)
    {
        if (count($allLabels)) {
            $pdf = new Zend_Pdf();

            foreach ($allLabels as $labelFiles) {
                foreach ($labelFiles as $labelFile) {
                    $page = new Zend_Pdf_Page('432:288:');
                    $image = Zend_Pdf_Image::imageWithPath($labelFile);
                    $page->drawImage($image, 0, 0, 432, 288);
                    $pdf->pages[] = $page;
                }
            }

            return $pdf;
        }

        return null;
    }
}