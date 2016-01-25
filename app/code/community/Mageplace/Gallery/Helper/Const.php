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
 * Class Mageplace_Gallery_Helper_Const
 */
class Mageplace_Gallery_Helper_Const
{
    const EXTENSION_NAME = 'mpgallery';

    const ACL_PATH_ALBUMS      = 'admin/mpgallery/albums';
    const ACL_PATH_PHOTOS      = 'admin/mpgallery/photos';
    const ACL_PATH_MULTIUPLOAD = 'admin/mpgallery/multiupload';
    const ACL_PATH_REVIEWS     = 'admin/mpgallery/reviews';
    const ACL_PATH_PRODUCT     = 'admin/mpgallery/product';

    const ALBUM = 'album';
    const PHOTO = 'photo';

    const ENABLE  = 1;
    const DISABLE = 0;

    const CURRENT_ALBUM        = 'current_album';
    const CURRENT_ACTIVE_ALBUM = 'current_active_album';
    const CURRENT_PHOTO        = 'current_photo';
    const CURRENT_ALBUM_PHOTOS = 'current_album_photo_collection';

    const SORT_BY_POSITION    = 'position';
    const SORT_BY_NAME        = 'name';
    const SORT_BY_UPDATE_DATE = 'update_date';

    const WIDTH_HEIGHT_DELIMITER = 'x';
    const WIDTH                  = 'w';
    const HEIGHT                 = 'h';

    const LIGHTBOX_PHOTOS_PAGE     = 0;
    const LIGHTBOX_PHOTOS_CATEGORY = 1;

    const DISPLAY_TYPE_GRID     = 'grid';
    const DISPLAY_TYPE_LIST     = 'list';
    const DISPLAY_TYPE_SIMPLE   = 'simple';
    const DISPLAY_TYPE_VIEW     = 'view';
    const DISPLAY_TYPE_CAROUSEL = 'carousel';

    const DISPLAY_TYPES_GRID_LIST_SIMPLE = 0;
    const DISPLAY_TYPES_GRID_SIMPLE_LIST = 1;
    const DISPLAY_TYPES_LIST_GRID_SIMPLE = 2;
    const DISPLAY_TYPES_LIST_SIMPLE_GRID = 3;
    const DISPLAY_TYPES_SIMPLE_LIST_GRID = 4;
    const DISPLAY_TYPES_SIMPLE_GRID_LIST = 5;
    const DISPLAY_TYPES_GRID_LIST        = 6;
    const DISPLAY_TYPES_GRID_SIMPLE      = 7;
    const DISPLAY_TYPES_LIST_GRID        = 8;
    const DISPLAY_TYPES_LIST_SIMPLE      = 9;
    const DISPLAY_TYPES_SIMPLE_GRID      = 10;
    const DISPLAY_TYPES_SIMPLE_LIST      = 11;
    const DISPLAY_TYPES_GRID             = 12;
    const DISPLAY_TYPES_LIST             = 13;
    const DISPLAY_TYPES_SIMPLE           = 14;

    static $DISPLAY_TYPES = array(
        self::DISPLAY_TYPES_GRID_LIST_SIMPLE => array(self::DISPLAY_TYPE_GRID, self::DISPLAY_TYPE_LIST, self::DISPLAY_TYPE_SIMPLE),
        self::DISPLAY_TYPES_GRID_SIMPLE_LIST => array(self::DISPLAY_TYPE_GRID, self::DISPLAY_TYPE_SIMPLE, self::DISPLAY_TYPE_LIST),
        self::DISPLAY_TYPES_LIST_GRID_SIMPLE => array(self::DISPLAY_TYPE_LIST, self::DISPLAY_TYPE_GRID, self::DISPLAY_TYPE_SIMPLE),
        self::DISPLAY_TYPES_LIST_SIMPLE_GRID => array(self::DISPLAY_TYPE_LIST, self::DISPLAY_TYPE_SIMPLE, self::DISPLAY_TYPE_GRID),
        self::DISPLAY_TYPES_SIMPLE_LIST_GRID => array(self::DISPLAY_TYPE_SIMPLE, self::DISPLAY_TYPE_LIST, self::DISPLAY_TYPE_GRID),
        self::DISPLAY_TYPES_SIMPLE_GRID_LIST => array(self::DISPLAY_TYPE_SIMPLE, self::DISPLAY_TYPE_GRID, self::DISPLAY_TYPE_LIST),
        self::DISPLAY_TYPES_GRID_LIST        => array(self::DISPLAY_TYPE_GRID, self::DISPLAY_TYPE_LIST),
        self::DISPLAY_TYPES_GRID_SIMPLE      => array(self::DISPLAY_TYPE_GRID, self::DISPLAY_TYPE_SIMPLE),
        self::DISPLAY_TYPES_LIST_GRID        => array(self::DISPLAY_TYPE_LIST, self::DISPLAY_TYPE_GRID),
        self::DISPLAY_TYPES_LIST_SIMPLE      => array(self::DISPLAY_TYPE_LIST, self::DISPLAY_TYPE_SIMPLE),
        self::DISPLAY_TYPES_SIMPLE_LIST      => array(self::DISPLAY_TYPE_SIMPLE, self::DISPLAY_TYPE_LIST),
        self::DISPLAY_TYPES_SIMPLE_GRID      => array(self::DISPLAY_TYPE_SIMPLE, self::DISPLAY_TYPE_GRID),
        self::DISPLAY_TYPES_GRID             => array(self::DISPLAY_TYPE_GRID),
        self::DISPLAY_TYPES_LIST             => array(self::DISPLAY_TYPE_LIST),
        self::DISPLAY_TYPES_SIMPLE           => array(self::DISPLAY_TYPE_SIMPLE),
    );

    static $DISPLAY_TYPES_BY_MODE = array(
        self::DISPLAY_TYPE_GRID   => array(
            self::DISPLAY_TYPES_GRID_LIST_SIMPLE,
            self::DISPLAY_TYPES_GRID_SIMPLE_LIST,
            self::DISPLAY_TYPES_LIST_GRID_SIMPLE,
            self::DISPLAY_TYPES_SIMPLE_LIST_GRID,
            self::DISPLAY_TYPES_SIMPLE_GRID_LIST,
            self::DISPLAY_TYPES_GRID_LIST,
            self::DISPLAY_TYPES_GRID_SIMPLE,
            self::DISPLAY_TYPES_LIST_GRID,
            self::DISPLAY_TYPES_SIMPLE_GRID,
            self::DISPLAY_TYPES_GRID,
        ),
        self::DISPLAY_TYPE_LIST   => array(
            self::DISPLAY_TYPES_GRID_LIST_SIMPLE,
            self::DISPLAY_TYPES_GRID_SIMPLE_LIST,
            self::DISPLAY_TYPES_LIST_GRID_SIMPLE,
            self::DISPLAY_TYPES_SIMPLE_LIST_GRID,
            self::DISPLAY_TYPES_SIMPLE_GRID_LIST,
            self::DISPLAY_TYPES_GRID_LIST,
            self::DISPLAY_TYPES_LIST_GRID,
            self::DISPLAY_TYPES_LIST_SIMPLE,
            self::DISPLAY_TYPES_SIMPLE_LIST,
            self::DISPLAY_TYPES_LIST,
        ),
        self::DISPLAY_TYPE_SIMPLE => array(
            self::DISPLAY_TYPES_GRID_LIST_SIMPLE,
            self::DISPLAY_TYPES_GRID_SIMPLE_LIST,
            self::DISPLAY_TYPES_LIST_GRID_SIMPLE,
            self::DISPLAY_TYPES_SIMPLE_LIST_GRID,
            self::DISPLAY_TYPES_SIMPLE_GRID_LIST,
            self::DISPLAY_TYPES_GRID_SIMPLE,
            self::DISPLAY_TYPES_LIST_SIMPLE,
            self::DISPLAY_TYPES_SIMPLE_GRID,
            self::DISPLAY_TYPES_SIMPLE_LIST,
            self::DISPLAY_TYPES_SIMPLE,

        ),
    );
}