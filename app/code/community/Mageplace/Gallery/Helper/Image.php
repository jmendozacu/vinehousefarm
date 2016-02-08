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
 * Class Mageplace_Gallery_Helper_Image
 */
class Mageplace_Gallery_Helper_Image extends Mage_Catalog_Helper_Image
{
    protected $_item;

    /**
     * Initialize Helper to work with Image
     *
     * @param Mageplace_Gallery_Model_Abstract $item
     * @param string                           $attributeName
     * @param mixed                            $imageFile
     *
     * @return $this
     */
    public function initialize(Mageplace_Gallery_Model_Abstract $item, $attributeName = 'image', $imageFile = null)
    {
        $this->_reset();
        $this->_setModel(Mage::getModel('mpgallery/image'));
        $this->_getModel()->setDestinationSubdir($attributeName);
        $this->_getModel()->setBaseMediaPath($item->helper()->getBaseDir());
        $this->setItem($item);

        $this->setWatermark(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_image")
        );
        $this->setWatermarkImageOpacity(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_imageOpacity")
        );
        $this->setWatermarkPosition(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_position")
        );
        $this->setWatermarkSize(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_size")
        );

        if ($imageFile) {
            $this->setImageFile($imageFile);
        } else {
            // add for work original size
            $this->_getModel()->setBaseFile($item->getData('image'));
        }

        return $this;
    }

    public function resizeBySize($size)
    {
        if ($size) {
            $this->_getModel()->setSize($size);
        }

        $this->_scheduleResize = true;

        return $this;

    }

    /**
     * Set current item (album, photo, etc)
     *
     * @param Mageplace_Gallery_Model_Abstract $item
     *
     * @return $this
     */
    protected function setItem($item)
    {
        $this->_item = $item;

        return $this;
    }

    /**
     * Get current Product
     *
     * @return Mageplace_Gallery_Model_Abstract
     */
    protected function getItem()
    {
        return $this->_item;
    }

    /**
     * Set current Image model
     *
     * @param Mage_Catalog_Model_Product_Image $model
     *
     * @return $this
     */
    protected function _setModel($model)
    {
        $this->_model = $model;

        return $this;
    }

    /**
     * Get current Image model
     *
     * @return Mageplace_Gallery_Model_Image
     */
    protected function _getModel()
    {
        return $this->_model;
    }

    public function __toString()
    {
        try {
            $model = $this->_getModel();

            if ($this->getImageFile()) {
                $model->setBaseFile($this->getImageFile());
            } else {
                $model->setBaseFile($this->getItem()->getData('image'));
            }

            if ($model->isCached()) {
                return $model->getUrl();
            } else {
                if ($this->_scheduleRotate) {
                    $model->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $model->resize();
                }

                if ($this->getWatermark()) {
                    $model->setWatermark($this->getWatermark());
                }

                $url = $model->saveFile()->getUrl();
            }
        } catch (Exception $e) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }

        return $url;
    }

}
