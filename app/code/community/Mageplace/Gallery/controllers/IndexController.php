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
 * Class Mageplace_Gallery_IndexController
 */
class Mageplace_Gallery_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->_forward('view', 'album');
    }


}