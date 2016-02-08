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
 * Class Mageplace_Gallery_Adminhtml_Gallery_AlbumController
 */
class Mageplace_Gallery_Adminhtml_Gallery_AlbumController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAlbum()
    {
        $album = Mage::getModel('mpgallery/album');

        if (!$albumId = (int)$this->getRequest()->getParam('album_id')) {
            $albumId = (int)$this->getRequest()->getParam('id');
        }

        if ($albumId > 0) {
            $album->load($albumId);
        }

        if ($activeTabId = (string)$this->getRequest()->getParam('active_tab_id')) {
            Mage::getSingleton('admin/session')->setActiveTabId($activeTabId);
        }

        Mage::register('album', $album);
        Mage::register('current_album', $album);

        Mage::getSingleton('mpgallery/session')->setLastAlbumId($albumId);

        return $album;
    }

    protected function _initAction()
    {
        $galeryTitle = $this->__('Gallery');
        $albumsTitle = $this->__('Albums');

        $this
            ->loadLayout()
            ->_setActiveMenu('mpgallery/albums')
            ->_addBreadcrumb($galeryTitle, $galeryTitle)
            ->_addBreadcrumb($albumsTitle, $albumsTitle);

        if (method_exists($this, '_title')) {
            $this
                ->_title($galeryTitle)
                ->_title($albumsTitle);
        }

        return $this;
    }

    public function indexAction()
    {
        $this->_forward('edit');
    }

    public function addAction()
    {
        Mage::getSingleton('admin/session')->unsActiveTabId();

        $this->_forward('edit');
    }

    public function editAction()
    {
        $params['_current'] = true;
        $redirect           = false;

        $parentId = (int)$this->getRequest()->getParam('parent');

        $albumId     = (int)$this->getRequest()->getParam('id');
        $prevAlbumId = Mage::getSingleton('admin/session')->getLastEditedAlbum();


        if ($prevAlbumId && !$this->getRequest()->getQuery('isAjax') && !$this->getRequest()->getParam('clear')) {
            $this->getRequest()->setParam('id', $prevAlbumId);
        }

        if ($redirect) {
            $this->_redirect('*/*/edit', $params);

            return;
        }

        if (!($album = $this->_initAlbum())) {
            return;
        }

        $this->_title($albumId ? $album->getName() : $this->__('New Album'));

        $data = Mage::getSingleton('adminhtml/session')->getAlbumData();
        if (isset($data['general'])) {
            $album->addData($data['general']);
        }
        if (isset($data['additional'])) {
            $album->addData($data['additional']);
        }
        Mage::getSingleton('adminhtml/session')->unsAlbumData();

        if ($this->getRequest()->getQuery('isAjax')) {
            $breadcrumbsPath = $album->getPath();
            if (empty($breadcrumbsPath)) {
                $breadcrumbsPath = Mage::getSingleton('admin/session')->getAlbumDeletedPath();
                if (!empty($breadcrumbsPath)) {
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    if (count($breadcrumbsPath) <= 1) {
                        $breadcrumbsPath = '';
                    } else {
                        array_pop($breadcrumbsPath);
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }

            Mage::getSingleton('admin/session')->setLastEditedAlbum($album->getId());

            $this->loadLayout();

            $response = new Varien_Object(array(
                'content'  => $this->getLayout()->getBlock('mp.album.edit')->getFormHtml()
                    . $this->getLayout()->getBlock('mp.album.tree')->getBreadcrumbsJavascript($breadcrumbsPath, 'editingAlbumBreadcrumbs'),
                'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
            ));

            $this->getResponse()->setBody(
                Zend_Json::encode($response->getData())
            );

            return;
        }

        $this->_initAction();

        $this->getLayout()
            ->getBlock('head')
            ->setCanLoadExtJs(true);

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()
                ->getBlock('head')
                ->setCanLoadTinyMce(true);
        }

        $this->_addBreadcrumb(
            $this->__('Manage Albums'),
            $this->__('Manage Albums')
        );

        $this->renderLayout();
    }

    public function saveAction()
    {
        if (!$album = $this->_initAlbum()) {
            return;
        }

        $refreshTree = 'false';
        if ($post = $this->getRequest()->getPost()) {
            try {
                $album->addData($post['general']);
                if (!$album->getId()) {
                    $parentId = $this->getRequest()->getParam('parent');
                    if (!$parentId) {
                        $parentId = Mageplace_Gallery_Model_Album::TREE_ROOT_ID;
                    }

                    $parentAlbum = Mage::getModel('mpgallery/album')->load($parentId);
                    $album->setPath($parentAlbum->getPath());
                }

                $album->addData(array(
                    'image_imageuploadtype' => $post['image_imageuploadtype'],
                    'image'                 => $post['image'],
                ));

                $album->addData($post['display']);
                $album->addData($post['design']);
                $album->addData($post['sizes']);

                if ($useConfig = $this->getRequest()->getPost('use_config')) {
                    foreach ($useConfig as $attributeCode => $config) {
                        $album->setData($attributeCode, null);
                    }
                }

                $album->save();
                $this->_getSession()->addSuccess($this->__('The album has been saved'));

                $refreshTree = 'true';
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()
                    ->addError($e->getMessage())
                    ->setAlbumData($post);

                $refreshTree = 'false';
            }
        }

        $url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $album->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, ' . $refreshTree . ');</script>'
        );
    }

    public function deleteAction()
    {
        if (!$album = $this->_initAlbum()) {
            return;
        }

        if ($album->getId()) {
            try {
                $album->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The album has been deleted'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current' => true, 'id' => $album->getId())));

                return;
            }
        }

        $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current' => true, 'id' => null)));
    }

    public function moveAction()
    {
        $album = $this->_initAlbum();
        if (!$album->getId()) {
            return $this->getResponse()->setBody($this->__('Album move error'));
        }

        try {
            $parentNodeId = $this->getRequest()->getPost('pid', false);
            $prevNodeId   = $this->getRequest()->getPost('aid', false);
            $album->move($parentNodeId, $prevNodeId);

            return $this->getResponse()->setBody("SUCCESS");
        } catch (Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
            Mage::logException($e);
        }
    }

    public function treeAction()
    {
        $album = $this->_initAlbum();

        $block = $this->getLayout()->createBlock('mpgallery/adminhtml_album_tree');
        $root  = $block->getRoot();
        $this->getResponse()->setBody(Zend_Json::encode(array(
            'data'       => $block->getTree(),
            'parameters' => array(
                'text'         => $block->buildNodeName($root),
                'draggable'    => false,
                'allowDrop'    => (int)$root->getIsVisible(),
                'id'           => (int)$root->getId(),
                'expanded'     => (int)$block->getIsWasExpanded(),
                'album_id'     => (int)$album->getId(),
                'root_visible' => (int)$root->getIsVisible()
            )
        )));
    }

    public function albumsJsonAction()
    {
        if ($this->getRequest()->getParam('expand_all')) {
            Mage::getSingleton('admin/session')->setIsGalleryTreeWasExpanded(true);
        } else {
            Mage::getSingleton('admin/session')->setIsGalleryTreeWasExpanded(false);
        }

        if (!$album = $this->_initAlbum()) {
            return;
        }

        if ($album->getId()) {
            $this->getResponse()->setBody(
                $this->getLayout()
                    ->createBlock('mpgallery/adminhtml_album_tree')
                    ->getTreeJson($album)
            );
        }
    }

    public function albumCheckboxJsonAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mpgallery/adminhtml_album_tree_checkboxes')
                ->getAlbumChildrenJson($this->getRequest()->getParam('album'))
        );
    }

    public function refreshPathAction()
    {
        if (!$album = $this->_initAlbum()) {
            return;
        }

        if ($id = $album->getId()) {
            $this->getResponse()->setBody(
                Zend_Json::encode(array(
                    'id'   => $id,
                    'path' => $album->getPath(),
                ))
            );
        }
    }

    public function stateAction()
    {
        if ($this->getRequest()->getParam('isAjax') && $this->getRequest()->getParam('container')) {
            $container      = $this->getRequest()->getParam('container');
            $containerValue = (int)$this->getRequest()->getParam('value');

            Mage::getSingleton('mpgallery/session')->setFieldsetState($container, $containerValue);

            $this->getResponse()->setBody('success');
        }
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(Mageplace_Gallery_Helper_Const::ACL_PATH_ALBUMS);
    }
}