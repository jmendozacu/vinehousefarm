<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml product grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mageplace_Gallery_Block_Adminhtml_Review_Photo_Grid extends Mageplace_Gallery_Block_Adminhtml_Photo_Grid
{

    public function __construct()
    {
        parent::__construct();

        $this->setRowClickCallback('MP.review.gridRowClick');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(false);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('photo_id',
            array(
                'header'       => $this->__('ID'),
                'index'        => 'photo_id',
                'filter_index' => 'main_table.photo_id',
                'width'        => '80px',
                'type'         => 'number',
            )
        );

        $this->addColumn('photos',
            array(
                'header'   => $this->__('Photo'),
                'index'    => 'image',
                'renderer' => 'mpgallery/adminhtml_photo_grid_column_renderer_thumbs',
                'sortable' => false,
                'filter'   => false,
                'width'    => '60px'
            )
        );

        $this->addColumn('name',
            array(
                'header'       => $this->__('Name'),
                'index'        => 'name',
                'filter_index' => 'main_table.name',
            )
        );

        $this->addColumn('album_ids',
            array(
                'header'                    => $this->__('Album'),
                'index'                     => 'album_ids',
                'type'                      => 'options',
                'options'                   => $this->_getAlbums(),
                'renderer'                  => 'mpgallery/adminhtml_photo_grid_column_renderer_albums',
                'sortable'                  => false,
                'filter_condition_callback' => array(
                    $this,
                    '_filterAlbumCondition'
                )
            )
        );

        $this->addColumn('is_active',
            array(
                'header'       => $this->__('Active'),
                'index'        => 'is_active',
                'filter_index' => 'main_table.is_active',
                'type'         => 'options',
                'width'        => '70px',
                'options'      => $this->_getYesNo()
            )
        );
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/photoGrid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/jsonPhotoInfo', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        return $this;
    }
}
