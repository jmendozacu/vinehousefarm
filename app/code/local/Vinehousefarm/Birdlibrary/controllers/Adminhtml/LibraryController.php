<?php

/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */
class Vinehousefarm_Birdlibrary_Adminhtml_LibraryController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('admin/library')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Bird Library'), Mage::helper('adminhtml')->__('Bird Library'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('birdlibrary/bird')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('current_data', $model);

            $this->loadLayout();

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('birdlibrary')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function viewAction()
    {
        $this->_forward('edit');
    }


    /**
     * Product grid for AJAX request
     */
    public function productsAction()
    {
        /* @var $model Vinehousefarm_Birdlibrary_Model_Bird */
        $model = Mage::getModel('birdlibrary/bird');

        $itemId = $this->getRequest()->getParam('id');

        if ($itemId) {
            $model->load($itemId);

            Mage::register('current_data', $model);
        }

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('birdlibrary/adminhtml_library_edit_tab_product', 'library.tabs.products')
                ->toHtml()
        );
    }

    /**
     * Link grid for AJAX request
     */
    public function linksAction()
    {
        /* @var $model Vinehousefarm_Birdlibrary_Model_Bird */
        $model = Mage::getModel('birdlibrary/bird');

        $itemId = $this->getRequest()->getParam('id');

        if ($itemId) {
            $model->load($itemId);

            Mage::register('current_data', $model);
        }

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('birdlibrary/adminhtml_library_edit_tab_links', 'library.tabs.links')
                ->toHtml()
        );
    }


    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('main')) {

            $model = Mage::getModel('birdlibrary/bird');
            $model->setData($data);

            $url_key = strtolower($model->getUrlKey());
          
            if(isset($url_key) && strlen($url_key)>0){

                // Creating a rewrite
                /* @var $rewrite Mage_Core_Model_Url_Rewrite */
                $targetpath = 'library/bird/view/id/'.$model->getEntityId();
                
                $rewriteCheck = Mage::getModel('core/url_rewrite')
                    ->getCollection()
                    ->addFieldToFilter('target_path', $targetpath);

                if (count($rewriteCheck) == 0) {
                    die("here");
                    $rewrite = Mage::getModel('core/url_rewrite');
                    $rewrite->setStoreId($store_id)
                            ->setIdPath('bird-library/'.$url_key)
                            ->setRequestPath('bird-library/'.$url_key)
                            ->setTargetPath($targetpath)
                            ->setIsSystem(true)
                            ->save();
                 }       
            }
              


            if (!array_key_exists('in_garden', $data)) {
                $model->setInGarden(0);
            }

            if ($gallery = $this->getRequest()->getPost('gallery_section')) {
                $model->setGallery($gallery['images']);
                $imageData = Mage::helper('core')->jsonDecode($gallery['values']);
                $model->setImage($imageData['image']);
            }

            if (isset($_FILES['distribution_mapi']['name']) and (file_exists($_FILES['distribution_mapi']['tmp_name']))) {

                $uploaderMap = new Varien_File_Uploader('distribution_mapi');
                $uploaderMap->setAllowedExtensions(array('jpg', 'jpeg'));
                $uploaderMap->setAllowRenameFiles(false);
                $uploaderMap->setFilesDispersion(false);

                $uploaderMapPath = Mage::getBaseDir('media') . DS . 'bird' . DS . 'maps';

                $nameMap = trim(strtolower($model->getLatinName())) . '-map.jpg';

                if ($model->getDistributionMap()) {
                    $fileMap = new Varien_Io_File();
                    $fileMap->cd($uploaderMapPath);

                    if ($fileMap->checkAndCreateFolder($uploaderMapPath)) {
                        $fileMap->cd($uploaderMapPath);

                        if ($fileMap->fileExists($nameMap, true)) {
                            $fileMap->rm($nameMap);
                        }
                    }
                }

                $uploaderMap->save($uploaderMapPath, $nameMap);

                $model->setDistributionMap($uploaderMap->getUploadedFileName());
            }

            $currentEggNest = $model->getEggNest();

            if (isset($_FILES['egg_nesti']['name']) and (file_exists($_FILES['egg_nesti']['tmp_name']))) {

                $uploaderEggNest = new Varien_File_Uploader('egg_nesti');
                $uploaderEggNest->setAllowedExtensions(array('jpg'));
                $uploaderEggNest->setAllowRenameFiles(false);
                $uploaderEggNest->setFilesDispersion(false);

                $uploaderEggNestPath = Mage::getBaseDir('media') . DS . 'bird' . DS . 'eggnest';

                $nameEggNest = trim(strtolower($model->getLatinName())) . '-egg.jpg';

                if ($model->getEggNest()) {
                    $fileEggNest = new Varien_Io_File();
                    $fileEggNest->cd($uploaderEggNestPath);

                    if ($fileEggNest->checkAndCreateFolder($uploaderEggNestPath)) {
                        $fileEggNest->cd($uploaderEggNestPath);

                        if ($fileEggNest->fileExists($nameEggNest, true)) {
                            $fileEggNest->rm($nameEggNest);
                        }
                    }
                }

                $uploaderEggNest->save($uploaderEggNestPath, $nameEggNest);

                $model->setEggNest($uploaderEggNest->getUploadedFileName());
            }

            if (isset($_FILES['audio_filei']['name']) and (file_exists($_FILES['audio_filei']['tmp_name']))) {
                $uploaderAudio = new Varien_File_Uploader('audio_filei');
                $uploaderAudio->setAllowedExtensions(array('mp3'));
                $uploaderAudio->setAllowRenameFiles(false);

                $uploaderAudioPath = Mage::getBaseDir('media') . DS . 'bird' . DS . 'sounds';

                $nameAudio = trim(strtolower($model->getLatinName())) . '-sound.mp3';

                if ($model->getAudioFile()) {
                    $fileAudio = new Varien_Io_File();

                    if ($fileAudio->checkAndCreateFolder($uploaderAudioPath)) {
                        $fileAudio->cd($uploaderAudioPath);

                        if ($fileAudio->fileExists($nameAudio, true)) {
                            $fileAudio->rm($nameAudio);
                        }
                    }
                }

                $uploaderAudio->save($uploaderAudioPath, $nameAudio);

                $model->setAudioFile($uploaderAudio->getUploadedFileName());
            }

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('birdlibrary')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $data['entity_id']));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('birdlibrary')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('birdlibrary/bird');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $entityIds = $this->getRequest()->getParam('ids');
        if (!is_array($entityIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($entityIds as $entityId) {
                    $deliverydate = Mage::getModel('birdlibrary/bird')->load($entityId);
                    $deliverydate->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($entityIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName = 'birdlibrary.csv';
        $content = $this->getLayout()->createBlock('birdlibrary/adminhtml_bird_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'birdlibrary.xml';
        $content = $this->getLayout()->createBlock('birdlibrary/adminhtml_bird_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     * Flush Stores Images Cache action
     */
    public function flushAction()
    {
        if (Mage::helper('birdlibrary/image')->flushImagesCache()) {
            $this->_getSession()->addSuccess('Cache image successfully flushed');
        } else {
            $this->_getSession()->addError('There was error during flushing cache');
        }

        $this->_forward('index');
    }

    public function uploadAction()
    {
        try {
            $uploader = new Mage_Core_Model_File_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->addValidateCallback('catalog_product_image',
                Mage::helper('catalog/image'), 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                Mage::getSingleton('birdlibrary/product_media_config')->getBaseMediaPath()
            );

            Mage::dispatchEvent('bird_library_gallery_upload_image_after', array(
                'result' => $result,
                'action' => $this
            ));

            /**
             * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
             */
            $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
            $result['path'] = str_replace(DS, "/", $result['path']);

            $result['url'] = Mage::getSingleton('birdlibrary/product_media_config')->getMediaUrl($result['file']);
            $result['file'] = $result['file'];
            $result['cookie'] = array(
                'name' => session_name(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain()
            );

        } catch (Exception $e) {
            $result = array(
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

    protected function _isAllowed()
    {
        return true;
    }
}