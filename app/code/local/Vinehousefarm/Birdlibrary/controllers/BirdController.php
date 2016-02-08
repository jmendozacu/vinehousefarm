<?php
/**
 * @package Vine-House-Farm.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */

class Vinehousefarm_Birdlibrary_BirdController extends Mage_Core_Controller_Front_Action
{
    /**
     * Index page
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle("Bird Library");
        $this->getLayout()->getBlock('breadcrumbs')->addCrumb('home', array('label'=>$this->__('Home'), 'title'=>$this->__('Home'), 'link'=>Mage::getBaseUrl()));
        $this->getLayout()->getBlock('breadcrumbs')->addCrumb('birds', array('label'=>$this->__('Bird Library'), 'title'=>$this->__('Bird Library')));
        $this->renderLayout();
    }

    public function viewAction()
    {
        $this->loadLayout();

        if ($id = $this->getRequest()->get('id', 0)) {
            $bird = Mage::getModel('birdlibrary/bird')->load($id);

            if ($bird) {

                $this->getLayout()->getBlock('head')->setTitle($bird->getTitle());
                $this->getLayout()->getBlock('head')->setBirdName($bird->getBirdName());
                $this->getLayout()->getBlock('head')->setDescription($bird->getDescription());
                $this->getLayout()->getBlock('head')->setKeywords($bird->getKeywords());

                $this->getLayout()->getBlock('breadcrumbs')->addCrumb('home', array('label'=>$this->__('Home'), 'title'=>$this->__('Home'), 'link'=>Mage::getBaseUrl()));
                $this->getLayout()->getBlock('breadcrumbs')->addCrumb('birds', array('label'=>$this->__('Bird Library'), 'title'=>$this->__('Bird Library'), 'link'=>Mage::getUrl('bird-library')));
                $this->getLayout()->getBlock('breadcrumbs')->addCrumb('bird', array('label'=>$this->__($bird->getBirdName()), 'title'=>$this->__($bird->getBirdName())));

                Mage::register('current_bird', $bird);
            }
        }


        $this->renderLayout();
    }
}