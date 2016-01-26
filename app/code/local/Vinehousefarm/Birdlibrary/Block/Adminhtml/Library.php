<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_Block_Adminhtml_Library extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct()
    {
        $this->_blockGroup      = 'birdlibrary';
        $this->_controller      = 'adminhtml_library';
        $this->_headerText      = $this->__('Bird Library');
        $this->_addButtonLabel  = $this->__('Add Bird');

        $this->addButton(
            'flush_images_cache',
            array(
                'label' => Mage::helper('birdlibrary')->__('Flush Images Cache'),
                'onclick' => 'setLocation(\''.$this->getUrl('*/*/flush').'\')',
            )
        );
        parent::__construct();
    }
}