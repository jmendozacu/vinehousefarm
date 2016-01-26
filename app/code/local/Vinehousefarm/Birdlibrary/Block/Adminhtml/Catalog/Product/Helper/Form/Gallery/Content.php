<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Adminhtml_Catalog_Product_Helper_Form_Gallery_Content
    extends  Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('vinehousefarm/birdlabrary/gallery.phtml');
    }

    protected function _prepareLayout()
    {
        $this->setChild('uploader',
            $this->getLayout()->createBlock('adminhtml/media_uploader')
        );

        $this->getUploader()->getConfig()
            ->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/library/upload'))
            ->setFileField('image')
            ->setFilters(array(
                'images' => array(
                    'label' => Mage::helper('adminhtml')->__('Images (.gif, .jpg, .png)'),
                    'files' => array('*.gif', '*.jpg','*.jpeg', '*.png')
                )
            ));

        Mage::dispatchEvent('bird_library_gallery_prepare_layout', array('block' => $this));

        return $this;
    }

    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Gallery');
    }

    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view Gallery');
    }

    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    protected function _getModel()
    {
        return Mage::registry('current_data');
    }

    public function getImagesJson()
    {
        $images = (array) $this->_getModel()->getGallery();

        if (empty($images)) {
            $collection = Mage::getModel('birdlibrary/gallery')->getCollection()
                ->addFieldToFilter('bird_id', $this->_getModel()->getId());

            if ($collection->getSize()) {
                $images = array();
                foreach ($collection as $image) {
                    $imageData['label'] = (string) $image->getLabel();
                    $imageData['label_default'] = '';
                    $imageData['url'] = Mage::getSingleton('birdlibrary/product_media_config')->getMediaUrl($image->getFile());
                    $imageData['file'] = (string) $image->getFile();
                    $imageData['position'] = (int) $image->getPosition();
                    $imageData['position'] = 0;
                    $imageData['disabled'] = (int) $image->getDisable();
                    $imageData['disabled_default'] = 0;
                    $imageData['product_id'] = (int) $image->getBirdId();
                    $imageData['value_id'] = (int) $image->getId();

                    $images[] =  $imageData;
                }

            }
        }

        return Mage::helper('core')->jsonEncode($images);
    }

    public function getImagesValuesJson()
    {
        $values = array();
        foreach ($this->getMediaAttributes() as $code => $attribute) {
            $values[$code] = $this->_getModel()->getImage();
        }
        return Mage::helper('core')->jsonEncode($values);
    }

    /**
     * Enter description here...
     *
     * @return array
     */
    public function getImageTypes()
    {
        $imageTypes = array();
        foreach ($this->getMediaAttributes() as $code => $attribute) {
            $imageTypes[$code] = array(
                'label' => Mage::helper('birdlibrary')->__('Image'),
                'field' => 'image'
            );
        }
        return $imageTypes;
    }

    public function hasUseDefault()
    {
        return true;
    }

    /**
     * Enter description here...
     *
     * @return array
     */
    public function getMediaAttributes()
    {
        return array(
            'image' => 'image',
        );
    }
}