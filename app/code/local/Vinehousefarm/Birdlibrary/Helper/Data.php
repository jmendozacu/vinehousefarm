<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */ 
class Vinehousefarm_Birdlibrary_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param Vinehousefarm_Birdlibrary_Model_Bird $bird
     * @return string
     */
    public function getBirdUrl(Vinehousefarm_Birdlibrary_Model_Bird $bird)
    {
        return $this->_getUrl('library/bird/' . $bird->getUrl());
    }

    /**
     * @param Vinehousefarm_Birdlibrary_Model_Bird $bird
     * @return string
     */
    public function getBirdMap(Vinehousefarm_Birdlibrary_Model_Bird $bird)
    {
        $io = new Varien_Io_File();
        $io->open(array('path' => Mage::getBaseDir('media') . DS . 'bird' . DS . 'maps'));
        if ($io->fileExists($bird->getDistributionMap())) {
            return Mage::getBaseUrl('media') . '/bird/maps/' . $bird->getDistributionMap();
        }
    }

    /**
     * @param Vinehousefarm_Birdlibrary_Model_Bird $bird
     * @return string
     */
    public function getBirdSound(Vinehousefarm_Birdlibrary_Model_Bird $bird)
    {
        $io = new Varien_Io_File();
        $io->open(array('path' => Mage::getBaseDir('media') . DS . 'bird' . DS . 'sounds'));
        if ($io->fileExists($bird->getAudioFile())) {
            return Mage::getBaseUrl('media') . '/bird/sounds/' . $bird->getAudioFile();
        }
    }

    /**
     * @param Vinehousefarm_Birdlibrary_Model_Bird $bird
     * @return string
     */
    public function getBirdEgg(Vinehousefarm_Birdlibrary_Model_Bird $bird)
    {
        $io = new Varien_Io_File();
        $io->open(array('path' => Mage::getBaseDir('media') . DS . 'bird' . DS . 'eggnest'));
        if ($io->fileExists($bird->getEggNest())) {
            return Mage::getBaseUrl('media') . '/bird/eggnest/' . $bird->getEggNest();
        }
    }
}