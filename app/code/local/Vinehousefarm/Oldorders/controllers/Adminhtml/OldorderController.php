<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Oldorders_Adminhtml_OldorderController extends Mage_Adminhtml_Controller_Action
{
    public function productsAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}