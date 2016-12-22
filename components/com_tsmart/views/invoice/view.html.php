<?php
/**
 *
 * Handle the orders view
 *
 * @package	tsmart
 * @subpackage Orders
 * @author Oscar van Eijk
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 5432 2012-02-14 02:20:35Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmView'))require(VMPATH_SITE.DS.'helpers'.DS.'vmview.php');
if (!class_exists('VmImage')) require(VMPATH_ADMIN.DS.'helpers'.DS.'image.php');


/**
 * Handle the orders view
 */
class TsmartViewInvoice extends VmView {

	var $format = 'html';
	var $doVendor = false;
	var $uselayout	= '';
	var $orderDetails = 0;
	var $invoiceNumber =0;
	var $doctype = 'invoice';
	var $showHeaderFooter = true;

	public function display($tpl = null)
	{

		$document = JFactory::getDocument();
		tsmConfig::loadJLang('com_tsmart_shoppers', true);
		/* It would be so nice to be able to load the override of the FE additionally from here
		 * joomlaWantsThisFolder\language\overrides\en-GB.override.ini
		 * $jlang =JFactory::getLanguage();
		$tag = $jlang->getTag();
		$jlang->load('override', 'language/overrides',$tag,true);*/

		//We never want that the cart is indexed
		$document->setMetaData('robots','NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET');

		if(empty($this->uselayout)){
			$layout = vRequest::getCmd('layout','mail');
		} else {
			$layout = $this->uselayout;
		}
		switch ($layout) {
			case 'invoice':
				$this->doctype = $layout;
				$title = tsmText::_('com_tsmart_INVOICE');
				break;
			case 'deliverynote':
				$this->doctype = $layout;
				$layout = 'invoice';
				$title = tsmText::_('com_tsmart_DELIVERYNOTE');
				break;
			case 'confirmation':
				$this->doctype = $layout;
				$layout = 'confirmation';
				$title = tsmText::_('com_tsmart_CONFIRMATION');
				break;
			case 'mail':
				if (tsmConfig::get('order_mail_html')) {
					$layout = 'mail_html';
				} else {
					$layout = 'mail_raw';
				}
		}
		$this->setLayout($layout);

		$tmpl = vRequest::getCmd('tmpl');

		$this->print = false;
		if($tmpl and !$this->isPdf){
			$this->print = true;
		}

		$this->format = vRequest::getCmd('format','html');
		if($layout == 'invoice'){
			$document->setTitle( tsmText::_('com_tsmart_INVOICE') );
		}
		$order_print=false;

		if ($this->print and $this->format=='html') {
			$order_print=true;
		}

		$orderModel = tmsModel::getModel('orders');
		$orderDetails = $this->orderDetails;

		if($orderDetails==0){
			$orderDetails = $orderModel ->getMyOrderDetails();
			if(!$orderDetails ){
				echo tsmText::_('com_tsmart_CART_ORDER_NOTFOUND');
				vmdebug('com_tsmart_CART_ORDER_NOTFOUND and $orderDetails ',$orderDetails);
				return;
			} else if(empty($orderDetails['details'])){
				echo tsmText::_('com_tsmart_CART_ORDER_DETAILS_NOTFOUND');
				return;
			}
		}

		if(empty($orderDetails['details'])){
			echo tsmText::_('com_tsmart_ORDER_NOTFOUND');
			return 0;
		}
		if(!empty($orderDetails['details']['BT']->order_language)) {
			tsmConfig::loadJLang('com_tsmart',true, $orderDetails['details']['BT']->order_language);
			tsmConfig::loadJLang('com_tsmart_shoppers',true, $orderDetails['details']['BT']->order_language);
			tsmConfig::loadJLang('com_tsmart_orders',true, $orderDetails['details']['BT']->order_language);
		}

		//QuicknDirty, caching of the result tsmartModelCustomfields::calculateModificators must be deleted,
		/*if(!empty($orderDetails['items']) and is_array($orderDetails['items'])){

			$nbPr = count($orderDetails['items']);

			for($k = 0; $k<$nbPr ;$k++){
				$orderDetails['items'][$k]->modificatorSum = null;
			}
			vmdebug('$nbPr',$nbPr);
		}*/

		$this->assignRef('orderDetails', $orderDetails);
        // if it is order print, invoice number should not be created, either it is there, either it has not been created
		if(empty($this->invoiceNumber) and !$order_print){
		    $invoiceNumberDate = array();
			if (  $orderModel->createInvoiceNumber($orderDetails['details']['BT'], $invoiceNumberDate)) {
                if (shopFunctionsF::InvoiceNumberReserved( $invoiceNumberDate[0])) {
	                if  ($this->uselayout!='mail') {
		                $document->setTitle( tsmText::_('com_tsmart_PAYMENT_INVOICE') );
                        return ;
	                }
                }
			    $this->invoiceNumber = $invoiceNumberDate[0];
			    $this->invoiceDate = $invoiceNumberDate[1];
			    if(!$this->invoiceNumber or empty($this->invoiceNumber)){
				    vmError('Cant create pdf, createInvoiceNumber failed');
				    if  ($this->uselayout!='mail') {
					    return ;
				    }
			    }
			} else {
				// Could OR should not create Invoice Number, createInvoiceNumber failed
				if  ($this->uselayout!='mail') {
					return ;
				}
			}
		}

		$tsmart_vendor_id = $orderDetails['details']['BT']->tsmart_vendor_id;

		$emailCurrencyId = $orderDetails['details']['BT']->user_currency_id;
		$exchangeRate=FALSE;
		if(!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS.DS.'vmpsplugin.php');
		  JPluginHelper::importPlugin('vmpayment');
	    $dispatcher = JDispatcher::getInstance();
	    $dispatcher->trigger('plgVmgetEmailCurrency',array( $orderDetails['details']['BT']->tsmart_paymentmethod_id, $orderDetails['details']['BT']->tsmart_order_id, &$emailCurrencyId));
		if(!class_exists('CurrencyDisplay')) require(VMPATH_ADMIN.DS.'helpers'.DS.'currencydisplay.php');
		$currency = CurrencyDisplay::getInstance($emailCurrencyId,$tsmart_vendor_id);
			if ($emailCurrencyId) {
				$currency->exchangeRateShopper=$orderDetails['details']['BT']->user_currency_rate;
			}
		$this->assignRef('currency', $currency);

		//Create BT address fields
		$userFieldsModel = tmsModel::getModel('userfields');
		$_userFields = $userFieldsModel->getUserFields(
				 'account'
				, array('captcha' => true, 'delimiters' => true) // Ignore these types
				, array('delimiter_userinfo','user_is_vendor' ,'username','password', 'password2', 'agreed', 'address_type') // Skips
		);

		$userfields = $userFieldsModel->getUserFieldsFilled( $_userFields ,$orderDetails['details']['BT']);
		$this->assignRef('userfields', $userfields);

		//Create ST address fields
		$orderst = (array_key_exists('ST', $orderDetails['details'])) ? $orderDetails['details']['ST'] : $orderDetails['details']['BT'];

		$shipmentFieldset = $userFieldsModel->getUserFields(
				 'shipment'
				, array() // Default switches
				, array('delimiter_userinfo', 'username', 'email', 'password', 'password2', 'agreed', 'address_type') // Skips
		);

		$shipmentfields = $userFieldsModel->getUserFieldsFilled( $shipmentFieldset ,$orderst );
		$this->assignRef('shipmentfields', $shipmentfields);

		$civility="";
		foreach ($userfields['fields'] as  $field) {
			if ($field['name']=="title") {
				$civility=$field['value'];
				break;
			}
		}
		$company= empty($orderDetails['details']['BT']->company) ?"":$orderDetails['details']['BT']->company.", ";
		$shopperName =  $company. $civility.' '.$orderDetails['details']['BT']->first_name.' '.$orderDetails['details']['BT']->last_name;
		$this->assignRef('shopperName', $shopperName);
		$this->assignRef('civility', $civility);



		// Create an array to allow orderlinestatuses to be translated
		// We'll probably want to put this somewhere in ShopFunctions..
		$orderStatusModel = tmsModel::getModel('orderstatus');
		$_orderstatuses = $orderStatusModel->getOrderStatusList(true);
		$orderstatuses = array();
		foreach ($_orderstatuses as $_ordstat) {
			$orderstatuses[$_ordstat->order_status_code] = tsmText::_($_ordstat->order_status_name);
		}
		$this->assignRef('orderstatuslist', $orderstatuses);
		$this->assignRef('orderstatuses', $orderstatuses);

		$_itemStatusUpdateFields = array();
		$_itemAttributesUpdateFields = array();
		foreach($orderDetails['items'] as $_item) {
// 			$_itemStatusUpdateFields[$_item->tsmart_order_item_id] = JHtml::_('select.genericlist', $orderstatuses, "item_id[".$_item->tsmart_order_item_id."][order_status]", 'class="selectItemStatusCode"', 'order_status_code', 'order_status_name', $_item->order_status, 'order_item_status'.$_item->tsmart_order_item_id,true);
			$_itemStatusUpdateFields[$_item->tsmart_order_item_id] =  $_item->order_status;

		}

		if (empty($orderDetails['shipmentName']) ) {
		    if (!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
		    JPluginHelper::importPlugin('vmshipment');
		    $dispatcher = JDispatcher::getInstance();
		    $returnValues = $dispatcher->trigger('plgVmOnShowOrderFEShipment',array(  $orderDetails['details']['BT']->tsmart_order_id, $orderDetails['details']['BT']->tsmart_shipmentmethod_id, &$orderDetails['shipmentName']));
		}

		if (empty($orderDetails['paymentName']) ) {
		    if(!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS.DS.'vmpsplugin.php');
		    JPluginHelper::importPlugin('vmpayment');
		    $dispatcher = JDispatcher::getInstance();
		    $returnValues = $dispatcher->trigger('plgVmOnShowOrderFEPayment',array( $orderDetails['details']['BT']->tsmart_order_id, $orderDetails['details']['BT']->tsmart_paymentmethod_id,  &$orderDetails['paymentName']));

		}

		$vendorModel = tmsModel::getModel('vendor');
		$vendor = $vendorModel->getVendor($tsmart_vendor_id);
		$vendorModel->addImages($vendor);
		$vendor->vendorFields = $vendorModel->getVendorAddressFields($tsmart_vendor_id);
		if (tsmConfig::get ('enable_content_plugin', 0)) {
			if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
			shopFunctionsF::triggerContentPlugin($vendor, 'vendor','vendor_store_desc');
			shopFunctionsF::triggerContentPlugin($vendor, 'vendor','vendor_terms_of_service');
			shopFunctionsF::triggerContentPlugin($vendor, 'vendor','vendor_legal_info');
		}

		$this->assignRef('vendor', $vendor);

		if (strpos($layout,'mail') !== false) {
			$lineSeparator="<br />";
		} else {
			$lineSeparator="\n";
		}
		$this->assignRef('headFooter', $this->showHeaderFooter);

		//Attention, this function will be removed, it wont be deleted, but it is obsoloete in any view.html.php
	    $vendorAddress= shopFunctionsF::renderVendorAddress($tsmart_vendor_id, $lineSeparator);
		$this->assignRef('vendorAddress', $vendorAddress);

		$vendorEmail = $vendorModel->getVendorEmail($tsmart_vendor_id);
		$vars['vendorEmail'] = $vendorEmail;

		// this is no setting in BE to change the layout !
		//shopFunctionsF::setVmTemplate($this,0,0,$layoutName);

		if (strpos($layout,'mail') !== false) {
		    if ($this->doVendor) {
		    	 //Old text key com_tsmart_MAIL_SUBJ_VENDOR_C
			    $this->subject = tsmText::sprintf('com_tsmart_MAIL_SUBJ_VENDOR_'.$orderDetails['details']['BT']->order_status, $this->shopperName, strip_tags($currency->priceDisplay($orderDetails['details']['BT']->order_total, $currency)), $orderDetails['details']['BT']->order_number);
			    $recipient = 'vendor';
		    } else {
			    $this->subject = tsmText::sprintf('com_tsmart_MAIL_SUBJ_SHOPPER_'.$orderDetails['details']['BT']->order_status, $vendor->vendor_store_name, strip_tags($currency->priceDisplay($orderDetails['details']['BT']->order_total, $currency)), $orderDetails['details']['BT']->order_number );
			    $recipient = 'shopper';
		    }
		    $this->assignRef('recipient', $recipient);
		}

		$tpl = null;

		parent::display($tpl);
	}

	// FE public function renderMailLayout($doVendor=false)
	function renderMailLayout ($doVendor, $recipient) {

		$this->doVendor=$doVendor;
		$this->frompdf=false;
		$this->uselayout = 'mail';

		$attach = tsmConfig::get('attach',false);

		if(empty($this->recipient)) $this->recipient = $recipient;
		if(!empty($attach) and !$doVendor and in_array($this->orderDetails['details']['BT']->order_status,tsmConfig::get('attach_os',0)) ){
			$this->mediaToSend = VMPATH_ROOT.DS.'images'.DS.'stories'.DS.'tsmart'.DS.'vendor'.DS.tsmConfig::get('attach');
		}
		$this->isMail = true;
		$this->display();

	}
	
	static function replaceVendorFields ($txt, $vendor) {
		// TODO: Implement more Placeholders (ordernr, invoicenr, etc.); 
		// REMEMBER TO CHANGE VmVendorPDF::replace_variables IN vmpdf.php, TOO!!!
		// Page nrs. for mails is always "1"
		$txt = str_replace('{vm:pagenum}', "1", $txt);
		$txt = str_replace('{vm:pagecount}', "1", $txt);
		$txt = str_replace('{vm:vendorname}', $vendor->vendor_store_name, $txt);
		$imgrepl='';
		if (!empty($vendor->images)) {
			$img = $vendor->images[0];
			$imgrepl = "<div class=\"vendor-image\">".$img->displayIt($img->file_url,'','',false, '', false, false)."</div>";
		}
		$txt = str_replace('{vm:vendorimage}', $imgrepl, $txt);
		$vendorAddress = shopFunctionsF::renderVendorAddress($vendor->tsmart_vendor_id, "<br/>");
		// Trim the final <br/> from the address, which is inserted by renderVendorAddress automatically!
		if (substr($vendorAddress, -5, 5) == '<br/>') {
			$vendorAddress = substr($vendorAddress, 0, -5);
		}
		$txt = str_replace('{vm:vendoraddress}', $vendorAddress, $txt);
		$txt = str_replace('{vm:vendorlegalinfo}', $vendor->vendor_legal_info, $txt);
		$txt = str_replace('{vm:vendordescription}', $vendor->vendor_store_desc, $txt);
		$txt = str_replace('{vm:tos}', $vendor->vendor_terms_of_service, $txt);
		return "$txt";
	}


}
