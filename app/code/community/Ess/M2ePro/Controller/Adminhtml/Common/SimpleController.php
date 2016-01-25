<?php

/*
 * @copyright  Copyright (c) 2013 by  ESS-UA.
 */

abstract class Ess_M2ePro_Controller_Adminhtml_Common_SimpleController
    extends Ess_M2ePro_Controller_Adminhtml_BaseController
{
    //#############################################

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(Ess_M2ePro_Helper_View_Common::MENU_ROOT_NODE_NICK);
    }

    //#############################################
}
