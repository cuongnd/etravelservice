<?php

/**
 * Controller for the cart
 *
 * @package	tsmart
 * @subpackage Cart
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2014 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 9012 2015-10-09 11:49:32Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * Controller for the cart view
 *
 * @package tsmart
 * @subpackage Cart
 */
class TsmartControllerCart extends JControllerLegacy {


	public function __construct() {
		parent::__construct();
		if (tsmConfig::get('use_as_catalog', 0)) {
			$app = JFactory::getApplication();
			$app->redirect('index.php');
		} else {
			if (!class_exists('tsmartCart'))
			require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
			if (!class_exists('calculationHelper'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'calculationh.php');
		}
		$this->useSSL = tsmConfig::get('useSSL', 0);
		$this->useXHTML = false;

	}

	public function display($cachable = false, $urlparams = false){

		if(tsmConfig::get('use_as_catalog', 0)){
			// Get a continue link
			$tsmart_category_id = shopFunctionsF::getLastVisitedCategoryId();
			$categoryLink = '';
			if ($tsmart_category_id) {
				$categoryLink = '&tsmart_category_id=' . $tsmart_category_id;
			}
			$ItemId = shopFunctionsF::getLastVisitedItemId();
			$ItemIdLink = '';
			if ($ItemId) {
				$ItemIdLink = '&Itemid=' . $ItemId;
			}

			$continue_link = JRoute::_('index.php?option=com_tsmart&view=category' . $categoryLink . $ItemIdLink, FALSE);
			$app = JFactory::getApplication();
			$app ->redirect($continue_link,'This is a catalogue, you cannot acccess the cart');
		}

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$tmpl = vRequest::getCmd('tmpl',false);
		if ($viewType == 'raw' and $tmpl == 'component') {
			$viewType = 'html';
		}

		$viewName = vRequest::getCmd('view', $this->default_view);
		$viewLayout = vRequest::getCmd('layout', 'default');

		$view = $this->getView($viewName, $viewType, '', array('layout' => $viewLayout));

		$view->assignRef('document', $document);

		$cart = tsmartCart::getCart();

		$cart->order_language = vRequest::getString('order_language', $cart->order_language);
		if(!isset($force))$force = tsmConfig::get('oncheckout_opc',true);
		$cart->prepareCartData(false);
		$html=true;
		if ($cart->tsmart_shipmentmethod_id==0 and (($s_id = tsmConfig::get('set_automatic_shipment',false)) > 0)){
			vRequest::setVar('tsmart_shipmentmethod_id', $s_id);
			$cart->setShipmentMethod($force, !$html);
		}
		if ($cart->tsmart_paymentmethod_id==0 and (($s_id = tsmConfig::get('set_automatic_payment',false)) > 0) and $cart->products){
			vRequest::setVar('tsmart_paymentmethod_id', $s_id);
			$cart->setPaymentMethod($force, !$html);
		}

		$request = vRequest::getRequest();
		$task = vRequest::getCmd('task');
		if(($task == 'confirm' or isset($request['confirm'])) and !$cart->getInCheckOut()){

			$cart->confirmDone();
			$view = $this->getView('cart', 'html');
			$view->setLayout('order_done');
			$cart->_fromCart = false;
			$view->display();
			return true;
		} else {
			//$cart->_inCheckOut = false;
			$redirect = (isset($request['checkout']) or $task=='checkout');
			$cart->_inConfirm = false;
			$cart->checkoutData($redirect);
		}

		$cart->_fromCart = false;

		$view->display();

		return $this;
	}

	public function updatecart($html=true,$force = null){

		$cart = tsmartCart::getCart();
		$cart->_fromCart = true;
		$cart->_redirected = false;
		if(vRequest::get('cancel',0)){
			$cart->_inConfirm = false;
		}
		if($cart->getInCheckOut()){
			vRequest::setVar('checkout',true);
		}

		$cart->saveCartFieldsInCart();

		if($cart->updateProductCart()){
			vmInfo('com_tsmart_PRODUCT_UPDATED_SUCCESSFULLY');
		}

		//Maybe better in line 133
		$STsameAsBT = vRequest::getInt('STsameAsBT', null);
		if(isset($STsameAsBT)){
			$cart->STsameAsBT = $STsameAsBT;
		}

		$currentUser = JFactory::getUser();
		if(!$currentUser->guest){
			$cart->selected_shipto = vRequest::getVar('shipto', $cart->selected_shipto);
			if(!empty($cart->selected_shipto)){
				$userModel = tmsModel::getModel('user');
				$stData = $userModel->getUserAddressList($currentUser->id, 'ST', $cart->selected_shipto);

				if(isset($stData[0]) and is_object($stData[0])){
					$stData = get_object_vars($stData[0]);
					$cart->ST = $stData;
					$cart->STsameAsBT = 0;
				} else {
					$cart->selected_shipto = 0;
				}
			}
			if(empty($cart->selected_shipto)){
				$cart->STsameAsBT = 1;
				$cart->selected_shipto = 0;
				//$cart->ST = $cart->BT;
			}
		} else {
			$cart->selected_shipto = 0;
			if(!empty($cart->STsameAsBT)){
				//$cart->ST = $cart->BT;
			}
		}

		if(!isset($force))$force = tsmConfig::get('oncheckout_opc',true);

		$cart->setShipmentMethod($force, !$html);
		$cart->setPaymentMethod($force, !$html);

		$cart->prepareCartData();

		$coupon_code = trim(vRequest::getString('coupon_code', ''));
		if(!empty($coupon_code)){
			$msg = $cart->setCouponCode($coupon_code);
			if($msg) vmInfo($msg);
			$cart->setOutOfCheckout();
		}

		if ($html) {
			$this->display();
		} else {
			$json = new stdClass();
			ob_start();
			$this->display ();
			$json->msg = ob_get_clean();
			echo json_encode($json);
			jExit();
		}

	}


	public function updatecartJS(){
		$this->updatecart(false);
	}


	/**
	 * legacy
	 * @deprecated
	 */
	public function confirm(){
		$this->updatecart();
	}

	public function setshipment(){
		$this->updatecart(true,true);
	}

	public function setpayment(){
		$this->updatecart(true,true);
	}

	/**
	 * Add the product to the cart
	 * @access public
	 */
	public function add() {
		$mainframe = JFactory::getApplication();
		if (tsmConfig::get('use_as_catalog', 0)) {
			$msg = tsmText::_('com_tsmart_PRODUCT_NOT_ADDED_SUCCESSFULLY');
			$type = 'error';
			$mainframe->redirect('index.php', $msg, $type);
		}
		$cart = tsmartCart::getCart();
		if ($cart) {
			$tsmart_product_ids = vRequest::getInt('tsmart_product_id');
			$error = false;
			$cart->add($tsmart_product_ids,$error);
			if (!$error) {
				$msg = tsmText::_('com_tsmart_PRODUCT_ADDED_SUCCESSFULLY');
				$type = '';
			} else {
				$msg = tsmText::_('com_tsmart_PRODUCT_NOT_ADDED_SUCCESSFULLY');
				$type = 'error';
			}

			$mainframe->enqueueMessage($msg, $type);
			$mainframe->redirect(JRoute::_('index.php?option=com_tsmart&view=cart', FALSE));

		} else {
			$mainframe->enqueueMessage('Cart does not exist?', 'error');
		}
	}

	/**
	 * Add the product to the cart, with JS
	 * @access public
	 */
	public function addJS() {

		$this->json = new stdClass();
		$cart = tsmartCart::getCart(false);
		if ($cart) {
			$view = $this->getView ('cart', 'json');
			$tsmart_category_id = shopFunctionsF::getLastVisitedCategoryId();
			$categoryLink='';
			if ($tsmart_category_id) {
				$categoryLink = '&view=category&tsmart_category_id=' . $tsmart_category_id;
			}

			$continue_link = JRoute::_('index.php?option=com_tsmart' . $categoryLink);

			$tsmart_product_ids = vRequest::getInt('tsmart_product_id');

			$view = $this->getView ('cart', 'json');
			$errorMsg = 0;

			$products = $cart->add($tsmart_product_ids, $errorMsg );


			$view->setLayout('padded');
			$this->json->stat = '1';

			if(!$products or count($products) == 0){
				$product_name = vRequest::get('pname');
				$tsmart_product_id = vRequest::getInt('pid');
				if($product_name && $tsmart_product_id) {
					$view->product_name = $product_name;
					$view->tsmart_product_id = $tsmart_product_id;
				} else {
					$this->json->stat = '2';
				}
				$view->setLayout('perror');
			}

			$view->assignRef('products',$products);
			$view->assignRef('errorMsg',$errorMsg);

			ob_start();
			$view->display ();
			$this->json->msg = ob_get_clean();
		} else {
			$this->json->msg = '<a href="' . JRoute::_('index.php?option=com_tsmart', FALSE) . '" >' . tsmText::_('com_tsmart_CONTINUE_SHOPPING') . '</a>';
			$this->json->msg .= '<p>' . tsmText::_('com_tsmart_MINICART_ERROR') . '</p>';
			$this->json->stat = '0';
		}
		echo json_encode($this->json);
		jExit();
	}

	/**
	 * Add the product to the cart, with JS
	 *
	 * @access public
	 */
	public function viewJS() {

		if (!class_exists('tsmartCart'))
		require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = tsmartCart::getCart(false);
		$cart -> prepareCartData();
		$data = $cart -> prepareAjaxData(true);

		echo json_encode($data);
		Jexit();
	}

	/**
	 * For selecting couponcode to use, opens a new layout
	 */
	public function edit_coupon() {

		$view = $this->getView('cart', 'html');
		$view->setLayout('edit_coupon');

		// Display it all
		$view->display();
	}

	/**
	 * Store the coupon code in the cart
	 * @author Max Milbers
	 */
	public function setcoupon() {

		$this->updatecart();
	}


	/**
	 * For selecting shipment, opens a new layout
	 */
	public function edit_shipment() {


		$view = $this->getView('cart', 'html');
		$view->setLayout('select_shipment');

		// Display it all
		$view->display();
	}

	/**
	 * To select a payment method
	 */
	public function editpayment() {

		$view = $this->getView('cart', 'html');
		$view->setLayout('select_payment');

		// Display it all
		$view->display();
	}

	/**
	 * Delete a product from the cart
	 * @access public
	 */
	public function delete() {
		$mainframe = JFactory::getApplication();
		/* Load the cart helper */
		$cart = tsmartCart::getCart();
		if ($cart->removeProductCart())
		$mainframe->enqueueMessage(tsmText::_('com_tsmart_PRODUCT_REMOVED_SUCCESSFULLY'));
		else
		$mainframe->enqueueMessage(tsmText::_('com_tsmart_PRODUCT_NOT_REMOVED_SUCCESSFULLY'), 'error');

		$this->display();
	}

	public function getManager(){
		$id = vmAccess::getBgManagerId();
		return JFactory::getUser( $id );
	}

	/**
	 * Change the shopper
	 *
	 * @author Maik Künnemann
	 */
	public function changeShopper() {
		vRequest::vmCheckToken() or jexit ('Invalid Token');
		$app = JFactory::getApplication();

		$redirect = vRequest::getString('redirect',false);
		if($redirect){
			$red = $redirect;
		} else {
			$red = JRoute::_('index.php?option=com_tsmart&view=cart');
		}

		$id = vmAccess::getBgManagerId();
		$current = JFactory::getUser( );;
		$manager = vmAccess::manager('user');
		if(!$manager){
			$app->enqueueMessage(tsmText::sprintf('com_tsmart_CART_CHANGE_SHOPPER_NO_PERMISSIONS', $current->name .' ('.$current->username.')'), 'error');
			$app->redirect($red);
			return false;
		}

		$userID = vRequest::getCmd('userID');
		if($manager and !empty($userID) and $userID!=$current->id ){
			if($userID == $id){

			} else if(vmAccess::manager('user',$userID)){
			//if($newUser->authorise('core.admin', 'com_tsmart') or $newUser->authorise('vm.user', 'com_tsmart')){
				$app->enqueueMessage(tsmText::sprintf('com_tsmart_CART_CHANGE_SHOPPER_NO_PERMISSIONS', $current->name .' ('.$current->username.')'), 'error');
				$app->redirect($red);
			}
		}

		$searchShopper = vRequest::getString('searchShopper');

		if(!empty($searchShopper)){
			$this->display();
			return false;
		}

		//update session
		$session = JFactory::getSession();
		$adminID = $session->get('vmAdminID');
		if(!isset($adminID)) {
			if(!class_exists('tsmCrypt'))
				require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcrypt.php');
			$session->set('vmAdminID', tsmCrypt::encrypt($current->id));
		}
		$newUser = JFactory::getUser($userID);
		$session->set('user', $newUser);

		//update cart data
		$cart = tsmartCart::getCart();
		$usermodel = tmsModel::getModel('user');
		$data = $usermodel->getUserAddressList(vRequest::getCmd('userID'), 'BT');

		if(isset($data[0])){
			foreach($data[0] as $k => $v) {
				$data[$k] = $v;
			}
		}

		$cart->BT['email'] = $newUser->email;

		$cart->ST = 0;
		$cart->STsameAsBT = 1;
		$cart->selected_shipto = 0;
		$cart->tsmart_shipmentmethod_id = 0;
		$cart->saveAddressInCart($data, 'BT');

		$msg = tsmText::sprintf('com_tsmart_CART_CHANGED_SHOPPER_SUCCESSFULLY', $newUser->name .' ('.$newUser->username.')');

		if(empty($userID)){
			$red = JRoute::_('index.php?option=com_tsmart&view=user&task=editaddresscart&addrtype=BT');
			$msg = tsmText::sprintf('com_tsmart_CART_CHANGED_SHOPPER_SUCCESSFULLY','');
		}

		$app->enqueueMessage($msg, 'info');
		$app->redirect($red);
	}


	function cancel() {

		$cart = tsmartCart::getCart();
		if ($cart) {
			$cart->setOutOfCheckout();
		}
		$this->display();
	}

}

//pure php no Tag
