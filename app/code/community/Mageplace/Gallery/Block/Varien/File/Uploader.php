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
 * Class Mageplace_Gallery_Block_Varien_File_Uploader
 */
class Mageplace_Gallery_Block_Varien_File_Uploader extends Varien_File_Uploader
{
    static $ALLOWED_EXTENSIONS = array('jpg', 'jpeg', 'gif', 'png');

    protected $_move = true;

    function __construct($fileId, $move = true)
    {
        $this->_setUploadFileId($fileId);
        if (!file_exists($this->_file['tmp_name'])) {
            $code = empty($this->_file['tmp_name']) ? self::TMP_NAME_EMPTY : 0;
            throw new Exception('File was not uploaded.', $code);
        } else {
            $this->_fileExists = true;
        }

        $this->_move = $move;
    }

    public function save($destinationFolder, $newFileName = null)
    {
        $this->_validateFile();

        if ($this->_allowCreateFolders) {
            $this->_createDestinationFolder($destinationFolder);
        }

        if (!is_writable($destinationFolder)) {
            throw new Exception('Destination folder is not writable or does not exists.');
        }

        $this->_result = false;

        $destinationFile = $destinationFolder;
        $fileName        = isset($newFileName) ? $newFileName : $this->_file['name'];
        $fileName        = self::getCorrectFileName($fileName);
        if ($this->_enableFilesDispersion) {
            $fileName = $this->correctFileNameCase($fileName);
            $this->setAllowCreateFolders(true);
            $this->_dispretionPath = self::getDispretionPath($fileName);
            $destinationFile .= $this->_dispretionPath;
            $this->_createDestinationFolder($destinationFile);
        }

        if ($this->_allowRenameFiles) {
            $fileName = self::getNewFileName(self::_addDirSeparator($destinationFile) . $fileName);
        }

        $destinationFile = self::_addDirSeparator($destinationFile) . $fileName;

        $this->_result = $this->_moveFile($this->_file['tmp_name'], $destinationFile);

        if ($this->_result) {
            @chmod($destinationFile, 0777);
            if ($this->_enableFilesDispersion) {
                $fileName = str_replace(DIRECTORY_SEPARATOR, '/',
                        self::_addDirSeparator($this->_dispretionPath)) . $fileName;
            }
            $this->_uploadedFileName = $fileName;
            $this->_uploadedFileDir  = $destinationFolder;
            $this->_result           = $this->_file;
            $this->_result['path']   = $destinationFolder;
            $this->_result['file']   = $fileName;

            $this->_afterSave($this->_result);
        }

        return $this->_result;
    }

    protected function _moveFile($tmpPath, $destPath)
    {
        if ($this->_move) {
            if (is_uploaded_file($tmpPath)) {
                return move_uploaded_file($tmpPath, $destPath);
            } else {
                $copy = copy($tmpPath, $destPath);
                @unlink($tmpPath);

                return $copy;
            }
        } else {
            return copy($tmpPath, $destPath);
        }
    }

    private function _setUploadFileId($fileId)
    {
        if ($fileId == '') {
            throw new Exception('Invalid parameter given. A valid files upload array identifier is expected.');
        } elseif (is_array($fileId)) {
            $this->_file = $fileId;
        } else {
            $this->_file = $_FILES[$fileId];
        }

        if (empty($this->_file)) {
            throw new Exception('Files upload array is empty', self::TMP_NAME_EMPTY);
        }

        $this->_uploadType = self::SINGLE_STYLE;
    }

    private function _createDestinationFolder($destinationFolder)
    {
        if (!$destinationFolder) {
            return $this;
        }

        if (substr($destinationFolder, -1) == DIRECTORY_SEPARATOR) {
            $destinationFolder = substr($destinationFolder, 0, -1);
        }

        if (!(@is_dir($destinationFolder) || @mkdir($destinationFolder, 0777, true))) {
            throw new Exception("Unable to create directory '{$destinationFolder}'.");
        }

        return $this;
    }
}
