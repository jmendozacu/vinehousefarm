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
 * Class Mageplace_Gallery_Model_Mysql4_Abstract
 */
abstract class Mageplace_Gallery_Model_Mysql4_Abstract extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_storeTable;
    protected $_customerGroupTable;
    protected $_productTable;
    protected $_albumTable;
    protected $_photoTable;
    private $_imageForDelete;

    abstract protected function _helper();

    abstract public function getDisplayFields();

    abstract public function getDesignFields();

    abstract public function getSizeFields();

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        parent::_afterLoad($object);

        if (!$object->getId()) {
            return $this;
        }

        // process survey to stores relation
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->_storeTable, 'store_id')
            ->where($this->getIdFieldName() . ' = ?', $object->getId());

        if ($storesArray = $this->_getReadAdapter()->fetchCol($select)) {
            $object->setData('store_id', $storesArray);
        } else {
            $object->setData('store_id', array());
        }

        // process survey to customer groups relation
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->_customerGroupTable, 'group_id')
            ->where($this->getIdFieldName() . ' = ?', $object->getId());

        if ($customer_group_ids = $this->_getReadAdapter()->fetchCol($select)) {
            $object->setData('customer_group_ids', $customer_group_ids);
        } else {
            $object->setData('customer_group_ids', array());
        }

        return $this;
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);

        $id = $object->getId();

        if (!$id) {
            $object->setCreationDate(Mage::getSingleton('core/date')->gmtDate());
        }

        $object->setUpdateDate(Mage::getSingleton('core/date')->gmtDate());

        $object->setName(Mage::helper('mpgallery')->cleanText($object->getName()));

        $urlKey      = $object->getUrlKey();
        $emptyUrlKey = '' === $urlKey || null === $urlKey ? true : false;

        if ($emptyUrlKey) {
            $urlKey = $object->getName();
            $object->setUrlKey($urlKey);
        }

        $newUrlKey = preg_replace('/[^a-z0-9\-\_]/i', '-', strtolower(trim($urlKey)));
        if ($newUrlKey != $urlKey) {
            $object->setUrlKey($newUrlKey);
            if (!$emptyUrlKey) {
                Mage::getSingleton('adminhtml/session')->addWarning(
                    Mage::helper('mpgallery')->__('The URL key was changed')
                );
            }
        }

        $selectMain = $this->_getReadAdapter()
            ->select()
            ->from($this->getMainTable(), 'url_key')
            ->where('url_key = ?', $newUrlKey);

        if ($id) {
            $selectMain->where($this->getIdFieldName() . ' != ?', $id);
        }

        $selectAdd = $this->_getReadAdapter()
            ->select()
            ->from($this->getMainTable() == $this->_albumTable ? $this->_photoTable : $this->_albumTable, 'url_key')
            ->where('url_key = ?', $newUrlKey);

        if ($this->_getReadAdapter()->fetchOne($selectMain) || $this->_getReadAdapter()->fetchOne($selectAdd)) {
            $selectMain = $this->_getReadAdapter()
                ->select()
                ->from($this->getMainTable(), 'url_key')
                ->where('url_key LIKE ?', $newUrlKey . '%');

            if ($id) {
                $selectMain->where($this->getIdFieldName() . ' != ?', $id);
            }

            $data = (array)$this->_getReadAdapter()->fetchCol($selectMain);

            $selectAdd = $this->_getReadAdapter()
                ->select()
                ->from($this->getMainTable() == $this->_albumTable ? $this->_photoTable : $this->_albumTable, 'url_key')
                ->where('url_key LIKE ?', $newUrlKey . '%');

            $dataAdd = (array)$this->_getReadAdapter()->fetchCol($selectAdd);

            $data = array_merge($data, $dataAdd);

            if (!$id || in_array($newUrlKey . '-' . $id, $data)) {
                $countArray = array();
                foreach ($data as $row) {
                    $countArray[] = (int)preg_replace('/^\-/', '', substr($row, strlen($newUrlKey)));
                }

                $newUrlKey .= '-' . (max($countArray) + 1);
            } else {
                $newUrlKey .= '-' . $id;
            }

            $object->setUrlKey($newUrlKey);
            if (!$emptyUrlKey) {
                Mage::getSingleton('adminhtml/session')->addWarning(
                    Mage::helper('mpgallery')->__('This URL Key already exists, so it was stored like: %s', $newUrlKey)
                );
            }
        }

        $imageFile = $object->getData('image_file');
        if (empty($imageFile)) {
            $files = $_FILES;
        } else {
            $files['image'] = $imageFile;
        }

        if (!empty($files)) {
            foreach ($files as $key => $file) {
                $imgFile = $object->getData($key);
                if ($object->getData($key . '_imageuploadtype')) {
                    if (!empty($imgFile['path'])) {
                        $info     = pathinfo($imgFile['path']);
                        $realpath = realpath($imgFile['path']);

                        if (file_exists($realpath) && is_file($realpath)) {
                            if (!is_readable($realpath)) {
                                throw new Mageplace_Gallery_Exception(Mage::helper('mpgallery')->__('Image file not readable. Check file permission.'));
                            }

                            if (empty($file['name'])) {
                                $file['name'] = $info['basename'];
                            }

                            if (empty($file['type'])) {
                                $file['type'] = Mage::helper('mpgallery')->returnMIMEType($realpath);
                            }

                            if (empty($file['tmp_name'])) {
                                $file['tmp_name'] = $realpath;
                            }

                            if (!isset($file['error'])) {
                                $file['error'] = UPLOAD_ERR_OK;
                            }

                            if (empty($file['size'])) {
                                $file['size'] = filesize($realpath);
                            }
                        } else {
                            throw new Mageplace_Gallery_Exception(Mage::helper('mpgallery')->__('Image file not founded. Check file location.'));
                        }

                    } else {
                        $file['error'] = UPLOAD_ERR_NO_FILE;
                    }
                } else {
                    $imgFile['move'] = 0;
                    $object->setData($key, $imgFile);
                }

                if ($file['error'] === UPLOAD_ERR_OK) {
                    $newName = $object->getUrlKey() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

                    $uploader = new Mageplace_Gallery_Model_Varien_File_Uploader($file, empty($imgFile['move']) ? false : true);
                    $uploader->setAllowedExtensions(Mageplace_Gallery_Model_Varien_File_Uploader::$ALLOWED_EXTENSIONS);
                    $uploader->addValidateCallback('catalog_product_image', Mage::helper('catalog/image'), 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                    $result = $uploader->save($this->_helper()->getImageDir(), $newName);
                    if (!empty($result['file'])) {
                        if ($imgFile) {
                            if (!empty($imgFile['value']) && !empty($imgFile['delete'])) {
                                $imgFileName = str_replace($this->_helper()->getImageUrl(), '', strval($imgFile['value']));
                                if (@unlink($this->_helper()->getImageDir($imgFileName))) {
                                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mpgallery')->__('Old image file was successfully deleted'));
                                } else {
                                    Mage::getSingleton('adminhtml/session')->addWarning(Mage::helper('mpgallery')->__('Old image file was not deleted'));
                                }
                            }
                        }

                        $object->setData($key, $result['file']);
                    } else {
                        if (!$imgFile) {
                            throw new Mageplace_Gallery_Exception(Mage::helper('mpgallery')->__('Image file upload error'));
                        }
                    }

                } else {
                    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                        if (!empty($imgFile['value'])) {
                            $imgFileName = str_replace($this->_helper()->getImageUrl(), '', strval($imgFile['value']));

                            if (empty($imgFile['delete'])) {
                                $imgFile = $imgFileName;
                            } else {
                                if (@unlink($this->_helper()->getImageDir($imgFileName))) {
                                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mpgallery')->__('Old image file was successfully deleted'));
                                } else {
                                    Mage::getSingleton('adminhtml/session')->addWarning(Mage::helper('mpgallery')->__('Old image file was not deleted'));
                                }

                                $imgFile = '';
                            }

                        } else {
                            $imgFile = '';
                        }


                        $object->setData($key, $imgFile);
                    } else {
                        $object->setData($key, '');
                    }
                }
            }
        }

        if (is_array($object->getData('image'))) {
            $object->unsetData('image');
        }

        foreach (array_keys($this->getSizeFields()) as $sizeFieldName) {
            $size = $object->getData($sizeFieldName);
            if (null === $size || '' === $size) {
                continue;
            }

            $width = $height = '';

            if (is_array($size)) {
                if (isset($size[Mageplace_Gallery_Helper_Const::WIDTH]) && isset($size[Mageplace_Gallery_Helper_Const::HEIGHT])) {
                    $width  = $size[Mageplace_Gallery_Helper_Const::WIDTH];
                    $height = $size[Mageplace_Gallery_Helper_Const::HEIGHT];
                } else {
                    list($width, $height) = $size;
                }
            } elseif (is_string($size)) {
                if (strpos($size, Mageplace_Gallery_Helper_Const::WIDTH_HEIGHT_DELIMITER) > 0) {
                    continue;
                } else {
                    $width = $height = (int)$size;
                }
            } else {
                $width = $height = (int)$size;
            }

            if (!empty($width) || !empty($height)) {
                if (empty($width)) {
                    $width = $height;
                }
                if (empty($height)) {
                    $height = $width;
                }
                //var_dump($width, $height); die;
                $object->setData($sizeFieldName, $width . Mageplace_Gallery_Helper_Const::WIDTH_HEIGHT_DELIMITER . $height);
            } else {
                $object->setData($sizeFieldName, null);
            }
        }

        return $this;
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);

        $condition = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . ' = ?', $object->getId());

        // process object to store relation
        if ($object->hasData('stores')) {
            $stores = $object->getData('stores');
        } else {
            $stores = $object->getData('store_id');
        }

        if(is_array($stores)) {
            if (empty($stores) || in_array('0', $stores, true)) {
                $stores = array('0');
            }
        } else {
            $stores = array_map('intval', explode(',', strval($stores)));
        }

        $this->_getWriteAdapter()->delete($this->_storeTable, $condition);

        foreach ($stores as $store) {
            $this->_getWriteAdapter()->insert(
                $this->_storeTable,
                array(
                    $this->getIdFieldName() => $object->getId(),
                    'store_id'              => $store
                )
            );
        }

        // process object to customer group relation
        if ($object->hasData('customer_group_ids')) {
            $this->_getWriteAdapter()->delete($this->_customerGroupTable, $condition);

            if ($object->getOnlyForRegistered()) {
                $customer_group_ids = (array)$object->getData('customer_group_ids');
                if (!count($customer_group_ids)) {
                    $customer_group_ids = array_keys(Mage::helper('customer')->getGroups()->toOptionHash());
                }

                foreach ($customer_group_ids as $customer_group_id) {
                    $this->_getWriteAdapter()->insert(
                        $this->_customerGroupTable,
                        array(
                            $this->getIdFieldName() => $object->getId(),
                            'group_id'              => $customer_group_id
                        )
                    );
                }
            }
        }

        return $this;
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $this->_imageForDelete = $object->getImage();

        return parent::_beforeDelete($object);
    }

    protected function _afterDelete(Mage_Core_Model_Abstract $object)
    {
        parent::_afterDelete($object);

        if ($this->_imageForDelete) {
            Mage::log($this->_helper()->getImageDir($this->_imageForDelete));
            @unlink($this->_helper()->getImageDir($this->_imageForDelete));
        }

        return $this;
    }

    public function getTitleById($id)
    {
        /** @var Zend_Db_Select $select */
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getMainTable(), 'name')
            ->where($this->getIdFieldName() . ' = ?', $id);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function getIdByUrlKey($urlKey)
    {
        /** @var Zend_Db_Select $select */
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getMainTable(), $this->getIdFieldName())
            ->where('url_key = ?', $urlKey);

        return (int)$this->_getReadAdapter()->fetchOne($select);
    }
}