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
 * Class Mageplace_Gallery_Controller_Varien_Router_Standard
 */
class Mageplace_Gallery_Controller_Varien_Router_Standard extends Mage_Core_Controller_Varien_Router_Standard
{
    public function match(Zend_Controller_Request_Http $request)
    {
        $path = explode('/', trim($request->getPathInfo(), '/'));

        $pathCount = count($path);
        if ($pathCount < 1) {
            return parent::match($request);
        }

        $urlHelper = Mage::helper('mpgallery/url');

        if ($path[0] != $urlHelper->getGalleryDirectUrl()) {
            return parent::match($request);
        }

        $module = Mageplace_Gallery_Helper_Const::EXTENSION_NAME;

        $request->setRouteName($module);
        $request->setModuleName($module);

        if (!$controller = $request->getControllerName()) {
            if ($pathCount == 1) {
                if ($upload = $request->getParam('upload')) {
                    $controller = 'photo';
                    if ('photo_save' == $upload) {
                        $action = 'upload';
                    } else {
                        $action = 'uploadform';
                    }
                } elseif ($customer = $request->getParam('customer')) {
                    $controller = 'customer';
                    $action = $customer;
                } else {
                    $controller = 'album';
                }
                $request->setParam('id', 1);
            } else {
                $urlKey = $path[$pathCount - 1];
                if ($suffix = $urlHelper->getPhotoUrlSuffix()) {
                    $urlKey = str_replace($suffix, '', $urlKey);
                }
                $photoId = Mage::getResourceModel('mpgallery/photo')->getIdByUrlKey($urlKey);
                if ($photoId > 0) {
                    $controller = 'photo';
                    $request->setParam('photo_id', $photoId);
                    if ($pathCount > 2) {
                        $urlKey = $path[$pathCount - 2];
                        if ($suffix = $urlHelper->getAlbumUrlSuffix()) {
                            $urlKey = str_replace($suffix, '', $urlKey);
                        }
                        $id = Mage::getResourceModel('mpgallery/album')->getIdByUrlKey($urlKey);

                        $request->setParam('album_id', $id);
                    }
                } else {
                    $controller = 'album';
                    $urlKey     = $path[$pathCount - 1];
                    if ($suffix = $urlHelper->getAlbumUrlSuffix()) {
                        $urlKey = str_replace($suffix, '', $urlKey);
                    }
                    $request->setParam('id', Mage::getResourceModel('mpgallery/album')->getIdByUrlKey($urlKey));
                }
            }
        }

        if (empty($action) && !($action = $request->getActionName())) {
            $action = 'view';
        }

        $realModule = 'Mageplace_Gallery';

        $controllerClassName = $this->_validateControllerClassName(
            $realModule,
            $controller
        );

        if (!$controllerClassName) {
            return parent::match($request);
        }

        $controllerInstance = Mage::getControllerInstance(
            $controllerClassName,
            $request,
            $this->getFront()->getResponse()
        );

        if (!$controllerInstance->hasAction($action)) {
            return parent::match($request);
        }

        $request->setControllerName($controller);
        $request->setActionName($action);
        $request->setControllerModule($realModule);

        $request->setDispatched(true);

        $controllerInstance->dispatch($action);

        return true;
    }
}
