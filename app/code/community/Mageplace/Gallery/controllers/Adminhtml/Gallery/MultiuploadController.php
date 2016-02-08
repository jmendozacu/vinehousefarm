<?php
/**
 * Mageplace Flash Magazine
 *
 * @category    Mageplace
 * @package     Mageplace_Flashmagazine
 * @copyright   Copyright (c) 2010 Mageplace. (http://www.mageplace.com)
 * @license     http://www.mageplace.com/disclaimer.html
 */

/**
 * Class Mageplace_Gallery_Adminhtml_Gallery_MultiuploadController
 */
class Mageplace_Gallery_Adminhtml_Gallery_MultiuploadController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->_usedModuleName = 'mpgallery';

        $this->loadLayout()
            ->_setActiveMenu('mpgallery/multiupload')
            ->_title($this->__('Gallery'))
            ->_title($this->__('Photos Multiupload'))
            ->_addBreadcrumb($this->__('Gallery'), $this->__('Gallery'))
            ->_addBreadcrumb($this->__('Photos Multiupload'), $this->__('Photos Multiupload'));

        $this->getLayout()
            ->getBlock('head')
            ->setCanLoadExtJs(true);

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    public function saveAction()
    {
        if ((!$post = $this->getRequest()->getPost()) || empty($post['source_type'])
            || (($post['source_type'] == 'file') && empty($_FILES['upload_package']['size']))
            || (($post['source_type'] == 'dir') && empty($post['input_dir']))
        ) {
            $this->_getSession()->addError($this->__('Please fill all required fields'));

            return $this->_redirect('*/*/');
        }

        $success = $error = 0;
        if ($post['source_type'] == 'dir') {
            $allowed_extensions = Mageplace_Gallery_Model_Varien_File_Uploader::$ALLOWED_EXTENSIONS;
            if ((!$post['input_dir'] = realpath($post['input_dir'])) && !is_dir($post['input_dir'])) {
                $this->_getSession()->addError($this->__('Please fill all required fields'));

                return $this->_redirect('*/*/index');
            }

            $counter = 0;
            $dir     = @dir($post['input_dir']);
            while (($file = $dir->read()) !== false) {
                if (is_dir($dir->path . DS . $file)) {
                    continue;
                }

                $info = pathinfo($file);
                if (!empty($info['extension']) && in_array($info['extension'], $allowed_extensions)) {
                    $basefile = realpath($dir->path) . DS . $info['basename'];
                    $name     = $post['type_page_title'] == 'filename' ? $info['filename'] : $post['page_title'] . '_' . ++$counter;

                    $imgFileParams             = array();
                    $imgFileParams['name']     = $name . '.' . $info['extension'];
                    $imgFileParams['type']     = Mage::helper('mpgallery')->returnMIMEType($basefile);
                    $imgFileParams['tmp_name'] = $basefile;
                    $imgFileParams['error']    = 0;
                    $imgFileParams['size']     = filesize($basefile);

                    try {
                        $photo = Mage::getModel('mpgallery/photo')
                            ->setName($name)
                            ->setData('album_ids', $post['album_ids'])
                            ->setData('image_file', $imgFileParams)
                            ->setData('image_imageuploadtype', true)
                            ->setData('image', array(
                                'path' => $basefile,
                                'move' => !empty($post['delete_files']),
                            ))
                            ->setData('design_use_parent_settings', 1)
                            ->setData('display_use_parent_settings', 1)
                            ->setData('size_use_parent_settings', 1)
                            ->save();

                        if ($photo->getId()) {
                            ++$success;
                        }

                    } catch (Exception $e) {
                        Mage::logException($e);
                        ++$error;
                    }

                }
            }

            $dir->close();

        } else {
            $file = $_FILES['upload_package'];
            if (pathinfo($file['name'], PATHINFO_EXTENSION) != 'zip') {
                $this->_getSession()->addError($this->__('Upload file must be zip archive'));

                return $this->_redirect('*/*/');
            }

            if (!class_exists('ZipArchive', false)) {
                $this->_getSession()->addError($this->__('Unrecoverable error: Zip library missing'));

                return $this->_redirect('*/*/');
            }

            $zip = zip_open($file['tmp_name']);
            if (!$zip) {
                $this->_getSession()->addError($this->__('Can\'t open zip file'));

                return $this->_redirect('*/*/');
            }

            $counter = 0;
            while ($zipEntry = zip_read($zip)) {
                if (zip_entry_open($zip, $zipEntry, "r")) {
                    $tmpImgBuf = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));

                    $name = $post['type_page_title'] == 'filename' ? pathinfo(zip_entry_name($zipEntry), PATHINFO_FILENAME) : $post['page_title'] . '_' . ++$counter;

                    $tmpImgFile = tmpfile();
                    if (fwrite($tmpImgFile, $tmpImgBuf)) {
                        $streamData = stream_get_meta_data($tmpImgFile);

                        $imgFileParams             = array();
                        $imgFileParams['name']     = $name . '.' . pathinfo(zip_entry_name($zipEntry), PATHINFO_EXTENSION);
                        $imgFileParams['type']     = Mage::helper('mpgallery')->returnMIMEType($streamData['uri']);
                        $imgFileParams['tmp_name'] = $streamData['uri'];
                        $imgFileParams['error']    = UPLOAD_ERR_OK;
                        $imgFileParams['size']     = zip_entry_filesize($zipEntry);

                        try {
                            $photo = Mage::getModel('mpgallery/photo')
                                ->setName($name)
                                ->setData('album_ids', $post['album_ids'])
                                ->setData('image_file', $imgFileParams)
                                ->setData('image_imageuploadtype', true)
                                ->setData('image', array(
                                    'path' => $streamData['uri'],
                                    'move' => 0,
                                ))
                                ->setData('design_use_parent_settings', 1)
                                ->setData('display_use_parent_settings', 1)
                                ->setData('size_use_parent_settings', 1)
                                ->save();

                            if ($photo->getId()) {
                                ++$success;
                            }

                        } catch (Exception $e) {
                            Mage::logException($e);
                            ++$error;
                        }
                    }

                    fclose($tmpImgFile);
                    zip_entry_close($zipEntry);
                }
            }
        }

        if ($success > 0) {
            $this->_getSession()->addSuccess($this->__('Total of %d photo(s) were created', $success));
        }

        if ($error > 0) {
            $this->_getSession()->addError($this->__("Total of %d photo(s) weren't created", $error));
        }

        $this->_redirect('*/gallery/photos');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(Mageplace_Gallery_Helper_Const::ACL_PATH_MULTIUPLOAD);
    }
}