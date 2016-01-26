<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */ 
class Vinehousefarm_Birdlibrary_Helper_Image extends Vinehousefarm_Birdlibrary_Helper_Data
{
    /**
     * Maximum size for image in bytes
     * Default value is 1M
     *
     * @var int
     */
    const MAX_FILE_SIZE = 1048576;
    /**
     * Minimum image height in pixels
     *
     * @var int
     */
    const MIN_HEIGHT = 20;
    /**
     * Maximum image height in pixels
     *
     * @var int
     */
    const MAX_HEIGHT = 1000;
    /**
     * Minimum image width in pixels
     *
     * @var int
     */
    const MIN_WIDTH = 20;
    /**
     * Maximum image width in pixels
     *
     * @var int
     */
    const MAX_WIDTH = 1000;
    /**
     * Array of image size limitation
     *
     * @var array
     */
    protected $imageSize = array(
        'minheight' => self::MIN_HEIGHT,
        'minwidth' => self::MIN_WIDTH,
        'maxheight' => self::MAX_HEIGHT,
        'maxwidth' => self::MAX_WIDTH,
    );

    /**
     * Remove item image by image filename
     *
     * @param string $imageFile
     *
     * @return bool
     */
    public function removeImage($imageFile)
    {
        $io = new Varien_Io_File();
        $io->open(array('path' => $this->getBaseDir()));
        if ($io->fileExists($imageFile)) {
            return $io->rm($imageFile);
        }

        return false;
    }

    /**
     * Return the base media directory for Item images
     *
     * @return string
     */
    public function getBaseDir()
    {
        return Mage::getModel('birdlibrary/product_media_config')->getBaseMediaPath();
    }

    /**
     * Return URL for resized Item Image
     *
     * @param Varien_Object                     $item
     * @param integer                           $width
     * @param integer                           $height
     *
     * @return bool|string
     */
    public function resize(Varien_Object $item, $width, $height = null)
    {
        if (!$item->getImage()) {
            return false;
        }

        if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH) {
            return false;
        }

        $width = (int) $width;

        if (!is_null($height)) {
            if ($height < self::MIN_HEIGHT || $height > self::MAX_HEIGHT) {
                return false;
            }

            $height = (int) $height;
        }

        $imageFile = $item->getImage();
        $cacheDir = $this->getBaseDir().DS.'cache'.DS.$width;
        $cacheUrl = $this->getBaseUrl().'/cache/'.$width.'/';

        $io = new Varien_Io_File();
        $io->checkAndCreateFolder($cacheDir);
        $io->open(array('path' => $cacheDir));
        if ($io->fileExists($imageFile)) {
            return $cacheUrl . $imageFile;
        }

        try {
            $image = new Varien_Image($this->getBaseDir() . DS . $imageFile);
            $image->resize($width, $height);
            $image->save($cacheDir . DS . $imageFile);

            return $cacheUrl . $imageFile;
        } catch (Exception $e) {
            Mage::logException($e);

            return false;
        }
    }

    /**
     * Return the Base URL for Item images
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return Mage::getModel('birdlibrary/product_media_config')->getBaseMediaUrl();
    }

    /**
     * Removes folder with cached images
     *
     * @return boolean
     */
    public function flushImagesCache()
    {
        $cacheDir = $this->getBaseDir().DS.'cache'.DS;
        $io = new Varien_Io_File();
        if ($io->fileExists($cacheDir, false)) {
            return $io->rmdir($cacheDir, true);
        }

        return true;
    }
}