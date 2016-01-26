<?php
/**
 * @package Vine-House-Farm
 * @author A.Treitjak <a.treitjak@gmail.com>
 * @copyright 2012 - 2015 BelVG.com
 */ 
class Vinehousefarm_Common_Model_Orderpreparation_Pdf_AdviceList extends MDN_Orderpreparation_Model_Pdf_PickingList
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
        if (isset($this->_settings['periods']) && !$this->_settings['periods']) {
            $periods = implode(' - ', $this->_settings['periods']);
        }

        //fond de l'entete

        // insert le logo
        $this->insertLogo($page, $StoreId);

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
        $this->defineFont($page,14,self::FONT_MODE_BOLD);
        $this->drawTextInBlock($page, Mage::getStoreConfig('general/store_information/name'), 164, $this->y, $this->_PAGE_WIDTH - 199, 50, 'l');
        $this->y -= 20;
        $left_column = $this->y;
        $this->defineFont($page,11,self::FONT_MODE_REGULAR);
        $contact_address = explode('<br />', nl2br(Mage::getStoreConfig('general/store_information/address')));
        foreach ($contact_address as $address_line) {
            $this->drawTextInBlock($page, $address_line, 164, $this->y, 199, 50, 'l');
            $this->y -= 15;
        }

        $this->y -= 15;
        $this->drawTextInBlock($page, Mage::helper('authoriselist')->__('Phone: ') . Mage::getStoreConfig('general/store_information/creareseo_company_no'), 164, $this->y, 199, 50, 'l');
        $this->y -= 15;
        $this->drawTextInBlock($page, Mage::helper('authoriselist')->__('Fax: ') . Mage::getStoreConfig('general/store_information/creareseo_fax'), 164, $this->y, 199, 50, 'l');
        $this->y -= 15;
        $this->drawTextInBlock($page, Mage::helper('authoriselist')->__('Web: ') . 'www.vinehousefarm.co.uk', 164, $this->y, 199, 50, 'l');
        $this->y -= 15;
        $this->drawTextInBlock($page, Mage::helper('authoriselist')->__('eMail: ') . Mage::getStoreConfig('trans_email/ident_general/email'), 164, $this->y, 199, 50, 'l');
        $this->y -= 15;
        $this->drawTextInBlock($page, Mage::helper('authoriselist')->__('Vat Reqistration no.: ') . Mage::getStoreConfig('general/store_information/merchant_vat_number'), 164, $this->y, 199, 50, 'l');

        $this->y = $left_column;

//        $this->defineFont($page,9,self::FONT_MODE_BOLD);
//        $this->drawTextInBlock($page, date('d/m/Y H:m:s'), 10, $this->y, $this->_PAGE_WIDTH - 25, 50, 'r');

        //barre grise sous le titre
//        $this->y -= 10;
//        $page->drawLine(10, $this->y, $this->_BLOC_ENTETE_LARGEUR, $this->y);
//        $this->y -= 10;
//        $this->defineFont($page,9,self::FONT_MODE_REGULAR);
//        $this->drawTextInBlock($page, Mage::helper('authoriselist')->__('Pick Lisy all orders scheduled for collatiing and descpatch by ' . uc_words($type) . ' on ' . $periods), 10, $this->y, $this->_PAGE_WIDTH, 50, 'c');
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
        //$this->y -= 25;
        //$page->setFillColor(new Zend_Pdf_Color_GrayScale(0.2));

        $last = $orders->getLastItem();

        foreach ($orders as $order_index => $order) {

            $need = false;

            foreach ($order->getAllItems() as $item) {
                if ($this->needItemPrint($item, $type)) {
                    $need = true;
                }
            }

            if (!$need) {
                continue;
            }

            $this->defineFont($page,11,self::FONT_MODE_BOLD);
            $this->drawTextInBlock($page,  Mage::helper('authoriselist')->__('Order Number:'), 374, $this->y, 70, 50, 'r');
            $this->drawTextInBlock($page,  $order->getIncrementId(), 450, $this->y, 70, 50, 'l');
            $this->y -= 15;
            $this->drawTextInBlock($page,  Mage::helper('authoriselist')->__('Order Date:'), 374, $this->y, 70, 50, 'r');
            $this->drawTextInBlock($page,  $order->getCreatedAtStoreDate()->toString(Varien_Date::DATE_INTERNAL_FORMAT), 450, $this->y, 70, 50, 'l');
            $this->y -= 15;
            $this->drawTextInBlock($page,  Mage::helper('authoriselist')->__('Dispatch Date:'), 374, $this->y, 70, 50, 'r');
            $this->drawTextInBlock($page,  $this->getShippingArrivalDate($order)->toString(Varien_Date::DATE_INTERNAL_FORMAT), 450, $this->y, 70, 50, 'l');
            $this->y -= 15;
            $this->drawTextInBlock($page,  Mage::helper('authoriselist')->__('Payment By:'), 374, $this->y, 70, 50, 'r');
            $this->drawTextInBlock($page,  $order->getPayment()->getMethodInstance()->getTitle(), 450, $this->y, 70, 50, 'l');
            $this->y -= 15;
            //$this->drawTextInBlock($page,  Mage::helper('authoriselist')->__('Contact:'), 374, $this->y, 70, 50, 'r');
            //$this->drawTextInBlock($page,  $order->getShippingAddress()->getName(), 450, $this->y, 70, 50, 'l');

            $this->y -= 100;

            $this->defineFont($page,16,self::FONT_MODE_BOLD);

            $this->drawTextInBlock($page,  'POSTAL ADVICE NOTE', 15, $this->y, $this->_PAGE_WIDTH, 50, 'c');

            $this->y -= 10;

            $page->drawRectangle(20, $this->y, $this->_PAGE_WIDTH - 25, $this->y - 100, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            $page->drawRectangle(($this->_PAGE_WIDTH/2), $this->y, $this->_PAGE_WIDTH - 25, $this->y - 100, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

            $this->defineFont($page,12,self::FONT_MODE_BOLD);

            $this->y -= 20;

            $this->drawTextInBlock($page,  Mage::helper('authoriselist')->__('Delivered To:'), 60, $this->y, 70, 50, 'r');
            $this->drawTextInBlock($page,  Mage::helper('authoriselist')->__('Invoice To:'), 330, $this->y, 70, 50, 'r');

            $billingAddress = $this->_formatAddress($order->getBillingAddress()->format('advice_slips'));
            $shippingAddress = $this->_formatAddress($order->getShippingAddress()->format('advice_slips'));

            $this->y -= 15;

            $this->defineFont($page,10,self::FONT_MODE_REGULAR);

            foreach ($shippingAddress as $key => $value) {
                if ($value !== '') {
                    $page->drawText($value, $x + 45, $this->y, 'UTF-8');
                    $this->y -= 13;
                }
            }

            $this->y += 13 * count($shippingAddress);

            foreach ($billingAddress as $key => $value) {
                if ($value !== '') {
                    $page->drawText($value, $x + 325, $this->y, 'UTF-8');
                    $this->y -= 13;
                }
            }

//            $page->drawText(Mage::helper('authoriselist')->__('Customer:') . ' ' . $order->getBillingAddress()->getName(), 175, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('authoriselist')->__('Postcode:') . ' ' . $order->getBillingAddress()->getPostcode(), 370, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('authoriselist')->__('Labels:') . ' ' . (int) $order->getShippingLabels(), 490, $this->y, 'UTF-8');
//
            $this->y -= 30;
//
            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_BOLD);

            $page->drawText(Mage::helper('authoriselist')->__('Code'), 20, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Product Description'), 100, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('QTY'), 300, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Sale Price'), 340, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Line Price'), 410, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('VAT'), 490, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('Total'), 550, $this->y, 'UTF-8');

            $this->y -= 10;

            $page->drawRectangle(20, $this->y, $this->_PAGE_WIDTH - 25, $this->y, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_REGULAR);

            $this->y -= 15;

            $subtotal = 0;
            $subtax = 0;
            $subtaxincl = 0;

            foreach ($order->getAllItems() as $item) {

                $need_item = $this->needItemPrint($item, $type);

                if (!$need_item) {
                    continue;
                }

//                $need_item = false;
//
//                if ($item->hasWarehouseCode()) {
//                    if ($item->getWarehouseCode() === $type) {
//                        $need_item = true;
//                    }
//                } else {
//                    //TODO need refactoring
//                    $product = Mage::getModel('catalog/product')->load($item->getProductId());
//
//                    if ($product->getDefaultPickedFrom() === $type) {
//                        $need_item = true;
//                    }
//                }

                $page->drawText($item->getSku(), 20, $this->y, 'UTF-8');
                $page->drawText($item->getName(), 110, $this->y, 'UTF-8');

                $page->drawText((int) $item->getQtyOrdered(), 305, $this->y, 'UTF-8');
                $page->drawText(Mage::helper('core')->formatPrice($item->getPrice(), false), 345, $this->y, 'UTF-8');
                $page->drawText(Mage::helper('core')->formatPrice($item->getRowTotal(), false), 415, $this->y, 'UTF-8');
                $subtotal = $subtotal + $item->getRowTotal();
                $page->drawText(Mage::helper('core')->formatPrice($item->getTaxAmount(), false), 485, $this->y, 'UTF-8');
                $subtax = $subtax + $item->getTaxAmount();
                $page->drawText(Mage::helper('core')->formatPrice($item->getRowTotalInclTax(), false), 545, $this->y, 'UTF-8');
                $subtaxincl = $subtaxincl + $item->getRowTotalInclTax();

                $this->y -= 15;
            }

            $page->drawRectangle(20, $this->y, $this->_PAGE_WIDTH - 25, $this->y, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

            $this->y -= 15;

            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_BOLD);
            $page->drawText(Mage::helper('authoriselist')->__('Ex VAT'), 475, $this->y, 'UTF-8');
            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('core')->formatPrice($subtotal, false), 540, $this->y, 'UTF-8');

            $this->y -= 15;

            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_BOLD);
            $page->drawText(Mage::helper('authoriselist')->__('Delivery'), 475, $this->y, 'UTF-8');
            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('core')->formatPrice($order->getShippingAmount(), false), 540, $this->y, 'UTF-8');

            $this->y -= 15;

            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_BOLD);
            $page->drawText(Mage::helper('authoriselist')->__('VAT @ 20%'), 475, $this->y, 'UTF-8');
            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('core')->formatPrice($subtax, false), 540, $this->y, 'UTF-8');

            $this->y -= 15;

            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_BOLD);
            $page->drawText(Mage::helper('authoriselist')->__('Total'), 475, $this->y, 'UTF-8');
            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('core')->formatPrice($subtaxincl, false), 540, $this->y, 'UTF-8');

            $this->y -= 10;

            $page->drawRectangle(20, $this->y, $this->_PAGE_WIDTH - 25, $this->y, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

            $this->defineFont($page,self::DEFAULT_FONT_SIZE, self::FONT_MODE_ITALIC);
            $page->drawText(Mage::helper('authoriselist')->__('Tip:'), 20, $this->y + 35, 'UTF-8');

            $this->y = $this->_PAGE_HEIGHT/2 - 210;

            $this->defineFont($page,9, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('authoriselist')->__('Cut  Here'), 20, $this->y + 35, 'UTF-8');
            $page->drawLine(60, $this->y + 35, $this->_PAGE_WIDTH - 25, $this->y + 35);

            $this->y += 20;

            $page->drawText(Mage::helper('authoriselist')->__('Your Details'), 120, $this->y, 'UTF-8');

            $this->y -= 10;
            $page->drawText($order->getBillingAddress()->getName(), 30, $this->y, 'UTF-8');

            $this->y -= 10;
            $page->drawText($order->getBillingAddress()->getStreetFull(), 30, $this->y, 'UTF-8');

            $this->y -= 10;
            $page->drawText($order->getBillingAddress()->getCity(), 30, $this->y, 'UTF-8');

            $this->y -= 10;
            $page->drawText($order->getBillingAddress()->getRegion(), 30, $this->y, 'UTF-8');

            $this->y -= 10;
            $page->drawText($order->getBillingAddress()->getPostcode(), 30, $this->y, 'UTF-8');
            $page->drawText($order->getBillingAddress()->getTelephone(), 190, $this->y, 'UTF-8');

            $this->y -= 20;
            $page->drawRectangle(30, $this->y, 45, $this->y + 15, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            $page->drawText(Mage::helper('authoriselist')->__('Cheque (payable to Vine House Farm Ltd)'), 50, $this->y + 5, 'UTF-8');

            $this->y -= 20;
            $page->drawRectangle(30, $this->y, 45, $this->y + 15, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            $page->drawText(Mage::helper('authoriselist')->__('Please charge my Credit/Debit Card'), 50, $this->y + 5, 'UTF-8');

            $this->y -= 20;
            for ($i=0; $i <20; $i++) {
                $page->drawRectangle(30 + ($i*15), $this->y, 45 + ($i*15), $this->y + 15, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            }
            $page->drawText(Mage::helper('authoriselist')->__('Card Type (e.g. Visa)'), 340, $this->y + 5, 'UTF-8');
            $page->drawLine(430, $this->y + 3, $this->_PAGE_WIDTH - 25, $this->y + 3);

            $this->y -= 20;
            $page->drawText(Mage::helper('authoriselist')->__('Start Date'), 40, $this->y + 5, 'UTF-8');
            for ($i=0; $i<3; $i++) {
                $page->drawRectangle(90 + ($i*15), $this->y, 105 + ($i*15), $this->y + 15, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            }

            $page->drawText(Mage::helper('authoriselist')->__('Expiry Date'), 150, $this->y + 5, 'UTF-8');

            for ($i=0; $i<3; $i++) {
                $page->drawRectangle(210 + ($i*15), $this->y, 225 + ($i*15), $this->y + 15, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            }

            $this->y -= 20;
            $page->drawText(Mage::helper('authoriselist')->__('Secure Code'), 29, $this->y + 5, 'UTF-8');
            for ($i=0; $i<3; $i++) {
                $page->drawRectangle(90 + ($i*15), $this->y, 105 + ($i*15), $this->y + 15, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            }

            $this->defineFont($page,6, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('authoriselist')->__('(last 3 digits)'), 138, $this->y + 5, 'UTF-8');

            $this->defineFont($page,9, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('authoriselist')->__('Issue No'), 180, $this->y + 5, 'UTF-8');

            for ($i=0; $i<3; $i++) {
                $page->drawRectangle(220 + ($i*15), $this->y, 235 + ($i*15), $this->y + 15, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            }

            $this->defineFont($page,6, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('authoriselist')->__('Delivery Instructions'), 280, $this->y + 10, 'UTF-8');
            $page->drawText(Mage::helper('authoriselist')->__('If out please leave my order'), 280, $this->y + 3, 'UTF-8');

            $this->y -= 20;
            $this->defineFont($page,9, self::FONT_MODE_REGULAR);
            $page->drawText(Mage::helper('authoriselist')->__('Name on Card'), 30, $this->y, 'UTF-8');
            $page->drawLine(90, $this->y, $this->_PAGE_WIDTH - 250, $this->y);

            $this->y -= 20;
            $page->drawText(Mage::helper('authoriselist')->__('Signature'), 30, $this->y, 'UTF-8');
            $page->drawLine(80, $this->y, $this->_PAGE_WIDTH - 250, $this->y);

            if ($last->getId() != $order->getId()) {
                $page = $this->NewPage($settings);
            }
//
//            if ($offset < 25)
//                $offset = 20;
//            $this->y -= $offset;
//
//            //line separation
//            $page->setLineWidth(0.5);
//            $page->drawLine(self::DEFAULT_LINE_HEIGHT, $this->y - 4, $this->_BLOC_ENTETE_LARGEUR, $this->y - 4);
//            //$this->y -= $this->_ITEM_HEIGHT;


//
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

        return $this->pdf;
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
     * Get object created at date affected with object store timezone
     *
     * @return Zend_Date
     */
    protected function getShippingArrivalDate(Mage_Sales_Model_Order $order)
    {
        return Mage::app()->getLocale()->date(
            Varien_Date::toTimestamp($order->getShippingArrivalDate()),
            null,
            null,
            true
        );
    }

    /**
     * Insert the logo in the PDF
     * The logo is the one defined in System -> configuration -> Sales -> Sales -> Invoice and Packing Slip Design
     *
     * @param PDF_Page $page
     */
    protected function insertLogo(&$page, $StoreId = null) {
        try
        {
            if(!$StoreId){
                $StoreId = Mage::app()->getStore()->getStoreId();
            }
            $image = Mage::getStoreConfig('sales/identity/logo', $StoreId);
            if ($image) {
                $image = Mage::getBaseDir('media') . '/sales/store/logo/' . $image;
                if (is_file($image)) {
                    $image = Zend_Pdf_Image::imageWithPath($image);
                    $page->drawImage($image, 20, 645, 20 + 124, 645 + 180);
                }
            }
            unset($image);
        }catch(Exception $ex){
            mage::logException($ex);
        }
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

        return true;
    }
}