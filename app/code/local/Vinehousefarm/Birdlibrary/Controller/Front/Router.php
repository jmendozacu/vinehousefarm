<?php
/**
 * @package Vine-House-Farm.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015
 */

class Vinehousefarm_Birdlibrary_Controller_Front_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function initControllerRouters(Varien_Event_Observer $observer)
    {
        $front = $observer->getEvent()->getFront();
        $front->addRouter('birdlibrary', $this);

        return $this;
    }

    /**
     * @param Zend_Controller_Request_Http $request
     *
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $front = $this->getFront();

        $pathInfo = trim($request->getPathInfo(), '/');
        $params = explode('/', $pathInfo);
        if (isset($params[0]) && $params[0] == 'library') {
            $request->setModuleName('birdlibrary')
                ->setControllerName('bird')
                ->setControllerModule('Vinehousefarm_Birdlibrary');

            $controllerClassName = $this->_validateControllerClassName(
                'Vinehousefarm_Birdlibrary',
                $params[1]
            );

            // instantiate controller class
            $controllerInstance = Mage::getControllerInstance($controllerClassName, $request,
                $front->getResponse());

            $request->setParam('url_path', $params[1]);
            $request->setRouteName($params[0]);

            if (isset($params[1]) && $params[1] == 'bird') {

                if (isset($params[2])) {
                    $birdPath = Mage::getModel('birdlibrary/bird')->getCollection()
                        ->addFieldToFilter('url', (string) $params[2])
                        ->getFirstItem();

                    if ($birdPath) {
                        $request->setActionName('view');
                        $request->setParam('id', $birdPath->getId());

                        $request->setAlias(
                            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                            $pathInfo
                        );

                        $request->setDispatched(true);
                        $controllerInstance->dispatch('view');

                        return true;
                    }
                }
            }

        }

        return false;
    }
}