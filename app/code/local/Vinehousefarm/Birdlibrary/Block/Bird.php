<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Bird extends Vinehousefarm_Birdlibrary_Block_Abstract
{
    /**
     * @var Vinehousefarm_Birdlibrary_Model_Resource_Link_Collection
     */
    protected $_links;

    /**
     * @return mixed
     */
    public function getBird()
    {
        return Mage::registry('current_bird');
    }

    /**
     * @return Vinehousefarm_Birdlibrary_Model_Resource_Link_Collection
     */
    public function getBirdLinks()
    {
        if (!$this->_links) {
            $this->_links = Mage::getModel('birdlibrary/bird')->getCollection()
                ->addFieldToFilter('entity_id', array('in', $this->getBird()->getInLinks()));
        }

        return $this->_links;
    }

    /**
     * @return bool
     */
    public function isLinks()
    {
        return (bool) $this->getBirdLinks()->getSize();
    }
}