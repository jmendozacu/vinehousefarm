<?php
/**
 * @package Default (Template) Project.
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */ 
class Vinehousefarm_Common_Model_Orderpreparation_Pdf_PickingList extends MDN_Orderpreparation_Model_Pdf_PickingList
{
    CONST X_POS_IMAGE = 10;
    CONST X_POS_QTY = 55;
    CONST X_POS_NAME = 80;
    CONST X_POS_BARCODE = 360;
    CONST X_POS_ORDERIDS = 450;
    CONST X_POS_LOCATION = 520;

    CONST WIDTH_IMAGE = 40;
    CONST WIDTH_NAME = 250;
    CONST WIDTH_BARCODE = 80;

    CONST DEFAULT_FONT_SIZE = 10;
    CONST DEFAULT_LINE_HEIGHT = 10;

    /**
     * Dessine le pied de page
     *
     * @param unknown_type $page
     */
    public function drawFooter(&$page) {

        $StoreId = $this->_settings['store_id'];
        if(!$StoreId){
            $StoreId = Mage::app()->getStore()->getStoreId();
        }

//        $page->drawLine(10, $this->_BLOC_FOOTER_HAUTEUR, $this->_PAGE_WIDTH, $this->_BLOC_FOOTER_HAUTEUR);
//
//        $this->y -= 10;

        $this->defineFont($page,10);

//        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.7));
//        $page->drawRectangle(10, $this->_BLOC_FOOTER_HAUTEUR + 15, $this->_BLOC_FOOTER_LARGEUR, 15, Zend_Pdf_Page::SHAPE_DRAW_FILL);

        //rajoute le texte
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.2));
        $this->DrawMultilineText($page, Mage::getStoreConfig('purchase/general/footer_text', $StoreId), 20, $this->_BLOC_FOOTER_HAUTEUR, 10, 0, 15, false);
    }

    /**
     * Dessine l'entete de la page
     */
    public function drawHeader(&$page, $title, $StoreId = null) {

        $StoreId = $this->_settings['store_id'];
        if(!$StoreId){
            $StoreId = Mage::app()->getStore()->getStoreId();
        }

        $type = $this->_settings['type'];
        $periods = implode(' - ', $this->_settings['periods']);

        //fond de l'entete
//        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.7));
//        $page->drawRectangle(10, $this->y, $this->_BLOC_ENTETE_LARGEUR, $this->y - $this->_BLOC_ENTETE_HAUTEUR, Zend_Pdf_Page::SHAPE_DRAW_FILL);

        // insert le logo
//        $this->insertLogo($page, $StoreId);

//        //rajoute l'adresse et coordon�es dans l'entete
//        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
//        $this->defineFont($page,10,self::FONT_MODE_BOLD);
//        $this->DrawMultilineText($page, Mage::getStoreConfig('purchase/general/header_text', $StoreId), 300, $this->y - 10, 10, 0, 15);


        //barre grise sous le bloc d'entete
//        $this->y -= $this->_BLOC_ENTETE_HAUTEUR + 5;
//        $page->setLineWidth(1.5);
//        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.1));
//        $page->drawLine(10, $this->y, $this->_BLOC_ENTETE_LARGEUR, $this->y);

        //nom de l'objet
        $this->y -= 20;
        $name = $title;
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.3));
        $this->defineFont($page,16,self::FONT_MODE_BOLD);
        $this->drawTextInBlock($page, $name, 10, $this->y, $this->_PAGE_WIDTH, 50, 'l');
        $this->defineFont($page,9,self::FONT_MODE_BOLD);
        $this->drawTextInBlock($page, date('d/m/Y H:m:s'), 10, $this->y, $this->_PAGE_WIDTH - 25, 50, 'r');

        //barre grise sous le titre
        $this->y -= 10;
        $page->drawLine(10, $this->y, $this->_BLOC_ENTETE_LARGEUR, $this->y);
        $this->y -= 10;
        $this->defineFont($page,9,self::FONT_MODE_REGULAR);
        $this->drawTextInBlock($page, Mage::helper('authoriselist')->__('Pick List for all orders scheduled for despatch by ' . uc_words($type) . ' on ' . $periods), 10, $this->y, $this->_PAGE_WIDTH, 50, 'c');
    }

    /**
     * Enter description here...
     *
     * @param array $data :
     * ---> key comments contains comments
     * ---> key products contains an array with products
     * -------> each product as data : type_id, picture_path, qty, manufacturer, sku, name, location, barcode
     *
     * @return unknown
     */
    public function getPdf($data = array()) {

        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        //init datas
        $comments = $data['comments'];
        $products = $data['products'];
        $orders = $data['orders'];
        $type = $data['type'];
        $periods = $data['periods'];

        //init pdf object
        if ($this->pdf == null)
            $this->pdf = new Zend_Pdf();

        $needPrint = false;

        foreach ($orders as $order) {
            foreach ($order->getAllItems() as $item) {
                if ($this->needItemPrint($item, $type)) {
                    $needPrint = true;
                }
            }
        }

        if (!$needPrint) {
            return $this;
        }

        //create new page
        if (isset($data['title'])) {
            $titre = $data['title'];
        } else {
            $titre = mage::helper('purchase')->__('Pick List');
        }
        $settings = array();
        $settings['title'] = $titre;
        $settings['store_id'] = 0;
        $settings['type'] = $type;
        $settings['periods'] = $periods;
        $page = $this->NewPage($settings);
        $this->defineFont($page,self::DEFAULT_FONT_SIZE,self::FONT_MODE_BOLD);

        //display comments
//        if ($comments) {
//            $this->y -=20;
//            $offset = $this->DrawMultilineText($page, $comments, 25, $this->y, 12, 0, 18);
//            $this->y -= $offset + 10;
//            $page->drawLine(self::DEFAULT_LINE_HEIGHT, $this->y, $this->_BLOC_ENTETE_LARGEUR, $this->y);
//            $this->y -=10;
//        }

        //display table header
//        $this->drawTableHeader($page);
        $this->y -= 25;
        //$page->setFillColor(new Zend_Pdf_Color_GrayScale(0.2));


        foreach ($orders as $order) {

            $need = false;

            foreach ($order->getAllItems() as $item) {
                if ($this->needItemPrint($item, $type)) {
                    $need = true;
                }
            }

            if (!$need) {
                continue;
            }

            $this->defineFont($page,self::DEFAULT_FONT_SIZE,self::FONT_MODE_BOLD);

            $offset = $page->drawText(Mage::helper('authoriselist')->__('Order Ref:') . ' ' . $order->getIncrementId(), 15, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Customer:') . ' ' . $order->getBillingAddress()->getName(), 175, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Postcode:') . ' ' . $order->getBillingAddress()->getPostcode(), 370, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Labels:') . ' ' . (int) $order->getShippingLabels(), 490, $this->y, 'UTF-8');

            $this->y -= $this->_ITEM_HEIGHT;

            $this->defineFont($page,self::DEFAULT_FONT_SIZE);

            $offset = $page->drawText(Mage::helper('authoriselist')->__('Product Description'), 15, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Code'), 350, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('QTY'), 470, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Picked?'), 530, $this->y, 'UTF-8');

            $this->y -= 5;

            $offset = $page->drawLine(self::DEFAULT_LINE_HEIGHT, $this->y - 4, $this->_BLOC_ENTETE_LARGEUR, $this->y - 4);

            $this->y -= $this->_ITEM_HEIGHT;

            foreach ($order->getAllItems() as $item) {

                $need_item = $this->needItemPrint($item, $type);

                if (!$need_item) {
                    continue;
                }

                $offset =$page->drawText($item->getName(), 15, $this->y, 'UTF-8');
                $page->drawText($item->getSku(), 330, $this->y, 'UTF-8');

                $page->drawText((int) $item->getQtyOrdered(), 475, $this->y, 'UTF-8');

                $page->drawRectangle(535, $this->y + 15, 555, $this->y - 5, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

                $this->y -= $this->_ITEM_HEIGHT;
            }

            if ($offset < 25)
                $offset = 20;
            $this->y -= $offset;

            //line separation
            $page->setLineWidth(0.5);
            $page->drawLine(self::DEFAULT_LINE_HEIGHT, $this->y - 4, $this->_BLOC_ENTETE_LARGEUR, $this->y - 4);
            //$this->y -= $this->_ITEM_HEIGHT;

            //new page (if needed)
            if ($this->y < ($this->_BLOC_FOOTER_HAUTEUR + 40)) {
                $this->drawFooter($page);
                $page = $this->NewPage($settings);
                //$this->drawTableHeader($page);
                $this->y -= 20;
            }
        }

//        foreach ($products as $product) {
//
//            //---------------------------------------------
//            //PICTURE
//            if ($product['picture_path']) {
//                if (file_exists($product['picture_path'])) {
//                    try {
//                        $zendPicture = Zend_Pdf_Image::imageWithPath($product['picture_path']);
//                        $page->drawImage($zendPicture, self::X_POS_IMAGE, $this->y - 15, self::WIDTH_IMAGE, $this->y - 15 + 30);
//                    } catch (Exception $ex) {
//                        mage::logException($ex);
//                    }
//                }
//            }
//
//            //---------------------------------------------
//            //QTY
//            $this->defineFont($page, self::DEFAULT_FONT_SIZE);
//            $page->drawText($product['qty'], self::X_POS_QTY, $this->y - 5, 'UTF-8');
//
//            //---------------------------------------------
//            //PRODUCT NAME
//            $caption = $product['sku'];
//            $manufacturerText = $product['manufacturer'];
//            if ($manufacturerText) {
//                $caption = $manufacturerText . ' - ' . $caption;
//            }
//            $caption .= "\n" . $product['name'];
//
//            $caption .= mage::helper('AdvancedStock/Product_ConfigurableAttributes')->getDescription($product->getId());
//            $caption = $this->WrapTextToWidth($page, $caption, self::WIDTH_NAME);
//            $offset = $this->DrawMultilineText($page, $caption, self::X_POS_NAME, $this->y + 10, self::DEFAULT_FONT_SIZE, 0.2, 16);
//
//            //---------------------------------------------
//            //BARCODE
//            if ($product['barcode']) {
//                try{
//                    $picture = mage::helper('AdvancedStock/Product_Barcode')->getBarcodePicture($product['barcode']);
//                    if ($picture) {
//                        $zendPicture = $this->pngToZendImage($picture);
//                        $page->drawImage($zendPicture, self::X_POS_BARCODE, $this->y - 15, self::X_POS_BARCODE + self::WIDTH_BARCODE, $this->y - 15 + 30);
//                    }
//                }catch(Exception $ex){
//                    mage::logException($ex);
//                }
//            }
//
//            //---------------------------------------------
//            //ORDER LIST AND ORDER QTY
//            if (($product['order_list']) && is_array($product['order_list'])) {
//                $buffer = "";
//                foreach($product['order_list'] as $orderId => $qty){
//                    $buffer .= $this->getOrderIncrementId($orderId) . ' ('.$qty.')'."\n";
//                }
//                $offset = $this->DrawMultilineText($page, $buffer, self::X_POS_ORDERIDS, $this->y + 5, self::DEFAULT_FONT_SIZE, 0.2, 16);
//            }
//
//            //---------------------------------------------
//            //SHELF LOCATION
//            $page->drawText($product['location'], self::X_POS_LOCATION, $this->y, 'UTF-8');
//
//
//            if ($offset < 20)
//                $offset = 20;
//            $this->y -= $offset;
//
//            //line separation
//            $page->setLineWidth(0.5);
//            $page->drawLine(self::DEFAULT_LINE_HEIGHT, $this->y - 4, $this->_BLOC_ENTETE_LARGEUR, $this->y - 4);
//            $this->y -= $this->_ITEM_HEIGHT;
//
//            //new page (if needed)
//            if ($this->y < ($this->_BLOC_FOOTER_HAUTEUR + 40)) {
//                $this->drawFooter($page);
//                $page = $this->NewPage($settings);
//                $this->drawTableHeader($page);
//                $this->y -= 20;
//            }
//        }

        //draw footer
        $this->drawFooter($page);

        //draw pager
        $this->AddPagination($this->pdf);

        $this->_afterGetPdf();

        return $this;
    }

    /**
     * Rajoute la pagination
     *
     */
    public function AddPagination($pdf) {
        //pour chaque page
//        $page_count = count($pdf->pages);
//        for ($i = 0; $i < $page_count; $i++) {
//            if ($i >= $this->firstPageIndex) {
//                //recup la page
//                $page = $pdf->pages[$i];
//                //dessine la pagination
//                $pagination = 'Page ' . ($i + 1 - $this->firstPageIndex) . ' / ' . ($page_count - $this->firstPageIndex);
//                $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.3));
//                $this->defineFont($page,10);
//                $this->drawTextInBlock($page, $pagination, 0, 25, $this->_PAGE_WIDTH - 20, 40, 'r');
//            }
//        }
    }

    /**
     * @return mixed
     */
    public function getEntityPdf()
    {
        return $this->pdf;
    }

    /**
     * @return mixed
     */
    public function getPdfPageLast()
    {
        return end($this->pdf->pages);
    }

    /**
     * Table header
     *
     * @param unknown_type $page
     */
    public function drawTableHeader(&$page) {

        $this->y -= 15;
        $this->defineFont($page,self::DEFAULT_FONT_SIZE);

        $page->drawText(mage::helper('purchase')->__('Qty'), self::X_POS_QTY, $this->y, 'UTF-8');
        $page->drawText(mage::helper('purchase')->__('Product'), self::X_POS_NAME, $this->y, 'UTF-8');
        //$page->drawText(mage::helper('purchase')->__('Barcode'), self::X_POS_BARCODE, $this->y, 'UTF-8');
        $page->drawText(mage::helper('purchase')->__('Order'), self::X_POS_ORDERIDS, $this->y, 'UTF-8');
        //$page->drawText(mage::helper('purchase')->__('Location'), self::X_POS_LOCATION, $this->y, 'UTF-8');

        $this->y -= 8;
        $page->drawLine(self::DEFAULT_LINE_HEIGHT, $this->y, $this->_BLOC_ENTETE_LARGEUR, $this->y);

        $this->y -= 15;
    }

    /**
     * @param $item
     * @param $type
     * @return bool
     */
    protected function needItemPrint($item, $type)
    {
        if (Mage::helper('authoriselist')->isDropShipItem($item)) {
            return false;
        }

        if (Mage::helper('authoriselist')->isSupplierItem($item)) {
            return false;
        }

        if ($item->hasWarehouseCode()) {
            if ($item->getWarehouseCode() === $type) {
                return true;
            }
        } else {
            //TODO need refactoring
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            $pickedFrom = (string)$product->getResource()
                ->getAttribute('default_picked_from')
                ->getFrontend()
                ->getValue($product);

            if (strtolower(trim($pickedFrom)) === strtolower(trim($type))) {
                return true;
            }
        }

        return false;
    }
}