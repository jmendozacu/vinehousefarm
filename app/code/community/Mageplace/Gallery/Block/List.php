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
 * Class Mageplace_Gallery_Block_Album_List
 *
 */
abstract class Mageplace_Gallery_Block_List extends Mageplace_Gallery_Block_List_Abstract
{
    protected function _beforeToHtml()
    {
        foreach(array('top', 'bottom') as $position) {
            $this->addToolbarPosition($position, $this->getAlbumSettings()->getData($this->getGalleryObjectName() . '_display_toolbar_' . $position));
        }

        /** @var Mageplace_Gallery_Block_Album_List_Toolbar|Mageplace_Gallery_Block_Photo_List_Toolbar $toolbar */
        $toolbar = $this->getToolbarBlock();

        $toolbar->setCollection($this->getCollection());

        $this->setChild('toolbar', $toolbar);

        return parent::_beforeToHtml();
    }
}
