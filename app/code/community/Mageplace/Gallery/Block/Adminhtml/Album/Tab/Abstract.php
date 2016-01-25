<?php
/**
 * MagePlace Gallery Extension
 *
 * @category    Mageplace_Gallery
 * @package     Mageplace_Gallery
 * @copyright   Copyright (c) 2014 Mageplace. (http://www.mageplace.com)
 * @license     http://www.mageplace.com/disclaimer.html
 */

/**
 * Class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Abstract
 */
class Mageplace_Gallery_Block_Adminhtml_Album_Tab_Abstract extends Mage_Adminhtml_Block_Catalog_Form
{
    protected $_album;

    public function __construct()
    {
        parent::__construct();

        $this->setShowGlobalIcon(true);
    }

    public function getAlbum()
    {
        if (!$this->_album) {
            $this->_album = Mage::registry('album');
        }

        return $this->_album;
    }
}

