<?php
/**
 *
 * Description
 *
 * @package	tsmart
 * @subpackage
 * @author
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for the tsmart Component
 *
 * @package		tsmart
 * @author
 */
class TsmartViewOrders extends tsmViewAdmin {

	function display($tpl = null) {


		//Load helpers
		if (!class_exists('CurrencyDisplay'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		if(!class_exists('vmPSPlugin')) require(VMPATH_PLUGINLIBS.DS.'vmpsplugin.php');
		$orderStatusModel=tmsModel::getModel('orderstatus');
		$orderStates = $orderStatusModel->getOrderStatusList(true);

		$this->SetViewTitle( 'ORDER');

		$orderModel = tmsModel::getModel();

		$curTask = vRequest::getCmd('task');
		if ($curTask == 'edit') {
			tsmConfig::loadJLang('com_tsmart_shoppers',TRUE);
			tsmConfig::loadJLang('com_tsmart_orders', true);

			//For getOrderStatusName
			if (!class_exists('ShopFunctions'))	require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');

			// Load addl models
			$userFieldsModel = tmsModel::getModel('userfields');

			// Get the data
			$tsmart_order_id = vRequest::getInt('tsmart_order_id');
			$order = $orderModel->getOrder($tsmart_order_id);

			if(empty($order['details'])){
				JFactory::getApplication()->redirect('index.php?option=com_tsmart&view=orders',tsmText::_('com_tsmart_ORDER_NOTFOUND'));;
			}

			$_orderID = $order['details']['BT']->tsmart_order_id;
			$orderbt = $order['details']['BT'];
			$orderst = (array_key_exists('ST', $order['details'])) ? $order['details']['ST'] : $orderbt;
			$orderbt ->invoiceNumber = $orderModel->getInvoiceNumber($orderbt->tsmart_order_id);

			$currency = CurrencyDisplay::getInstance('',$order['details']['BT']->tsmart_vendor_id);

			$this->assignRef('currency', $currency);

			$_userFields = $userFieldsModel->getUserFields(
					 'account'
					, array('captcha' => true, 'delimiters' => true) // Ignore these types
					, array('delimiter_userinfo','user_is_vendor' ,'username','name','password', 'password2', 'agreed', 'address_type') // Skips
			);
			$userFieldsCart = $userFieldsModel->getUserFields(
				'cart'
				, array('captcha' => true, 'delimiters' => true) // Ignore these types
				, array('delimiter_userinfo','user_is_vendor' ,'username','password', 'password2', 'agreed', 'address_type') // Skips
			);
			$_userFields = array_merge($userFieldsCart,$_userFields);

			//Fallback for customer_note
			if(empty($orderbt->customer_note) and !empty($orderbt->oc_note)){
				$orderbt->customer_note = $orderbt->oc_note;
			}

			$userfields = $userFieldsModel->getUserFieldsFilled(
					 $_userFields
					,$orderbt
					,'BT_'
			);

			$_userFields = $userFieldsModel->getUserFields(
					 'shipment'
					, array() // Default switches
					, array('delimiter_userinfo', 'username', 'email', 'password', 'password2', 'agreed', 'address_type') // Skips
			);

			$shipmentfields = $userFieldsModel->getUserFieldsFilled(
					 $_userFields
					,$orderst
					,'ST_'
			);

			// Create an array to allow orderlinestatuses to be translated
			// We'll probably want to put this somewhere in ShopFunctions...
			$_orderStatusList = array();
			foreach ($orderStates as $orderState) {
				//$_orderStatusList[$orderState->tsmart_orderstate_id] = $orderState->order_status_name;
				//When I use update, I have to use this?
				$_orderStatusList[$orderState->order_status_code] = tsmText::_($orderState->order_status_name);
			}

			$_itemStatusUpdateFields = array();
			$_itemAttributesUpdateFields = array();
			foreach($order['items'] as $_item) {
				$_itemStatusUpdateFields[$_item->tsmart_order_item_id] = JHtml::_('select.genericlist', $orderStates, "item_id[".$_item->tsmart_order_item_id."][order_status]", 'class="selectItemStatusCode"', 'order_status_code', 'order_status_name', $_item->order_status, 'order_item_status'.$_item->tsmart_order_item_id,true);

			}

			if(!isset($_orderStatusList[$orderbt->order_status])){
				if(empty($orderbt->order_status)){
					$orderbt->order_status = 'unknown';
				}
				$_orderStatusList[$orderbt->order_status] = tsmText::_('com_tsmart_UNKNOWN_ORDER_STATUS');
			}

			$this->lists['search'] = '';

			/* Assign the data */
			$this->assignRef('orderdetails', $order);
			$this->assignRef('orderID', $_orderID);
			$this->assignRef('userfields', $userfields);
			$this->assignRef('shipmentfields', $shipmentfields);
			$this->assignRef('orderstatuslist', $_orderStatusList);
			$this->assignRef('itemstatusupdatefields', $_itemStatusUpdateFields);
			$this->assignRef('itemattributesupdatefields', $_itemAttributesUpdateFields);
			$this->assignRef('orderbt', $orderbt);
			$this->assignRef('orderst', $orderst);
			$this->assignRef('tsmart_shipmentmethod_id', $orderbt->tsmart_shipmentmethod_id);

			/* Data for the Edit Status form popup */
			$_currentOrderStat = $order['details']['BT']->order_status;
			// used to update all item status in one time
			$_orderStatusSelect = JHtml::_('select.genericlist', $orderStates, 'order_status', 'style="width:100px;"', 'order_status_code', 'order_status_name', $_currentOrderStat, 'order_items_status',true);
			$this->assignRef('orderStatSelect', $_orderStatusSelect);
			$this->assignRef('currentOrderStat', $_currentOrderStat);

			/* Toolbar */
			if (JVM_VERSION < 3) { $backward="back"; $list='back';} else {$backward='backward';$list='list';}
			JToolBarHelper::custom( 'prevItem', $backward,'','com_tsmart_ITEM_PREVIOUS',false);
			JToolBarHelper::custom( 'nextItem', 'forward','','com_tsmart_ITEM_NEXT',false);
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'cancel', $list,'','com_tsmart_ORDER_LIST_LBL',false,false);

		}
		else if ($curTask == 'editOrderItem') {
			if(!class_exists('calculationHelper')) require(VMPATH_ADMIN.DS.'helpers'.DS.'calculationh.php');

			$this->assignRef('orderstatuses', $orderStates);

			$model = tmsModel::getModel();
			$orderId = vRequest::getString('orderId', '');
			$orderLineItem = vRequest::getVar('orderLineId', '');
			$this->assignRef('tsmart_order_id', $orderId);
			$this->assignRef('tsmart_order_item_id', $orderLineItem);

			$orderItem = $model->getOrderLineDetails($orderId, $orderLineItem);
			$this->assignRef('orderitem', $orderItem);
		}
		else {
			$this->setLayout('orders');

			$model = tmsModel::getModel();
			$this->addStandardDefaultViewLists($model,'created_on');
			$orderStatusModel =tmsModel::getModel('orderstatus');
			$orderstates = vRequest::getCmd('order_status_code','');
			$this->lists['state_list'] = $orderStatusModel->renderOSList($orderstates,'order_status_code',FALSE,' onchange="this.form.submit();" ');
			$orderslist = $model->getOrdersList();

			$this->assignRef('orderstatuses', $orderStates);

			if(!class_exists('CurrencyDisplay'))require(VMPATH_ADMIN.DS.'helpers'.DS.'currencydisplay.php');

			/* Apply currency This must be done per order since it's vendor specific */
			$_currencies = array(); // Save the currency data during this loop for performance reasons

			if ($orderslist) {

			    foreach ($orderslist as $tsmart_order_id => $order) {

				    if(!empty($order->order_currency)){
					    $currency = $order->order_currency;
				    } else if($order->tsmart_vendor_id){
					    if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
					    $currObj = tsmartModelVendor::getVendorCurrency($order->tsmart_vendor_id);
				        $currency = $currObj->tsmart_currency_id;
					}
				    //This is really interesting for multi-X, but I avoid to support it now already, lets stay it in the code
				    if (!array_key_exists('curr'.$currency, $_currencies)) {

					    $_currencies['curr'.$currency] = CurrencyDisplay::getInstance($currency,$order->tsmart_vendor_id);
				    }

				    $order->order_total = $_currencies['curr'.$currency]->priceDisplay($order->order_total);
				    $order->invoiceNumber = $model->getInvoiceNumber($order->tsmart_order_id);
			    }

			}

			//update order items button
			/*$q = 'SELECT * FROM #__tsmart_order_items WHERE `product_discountedPriceWithoutTax` IS NULL ';
			$db = JFactory::getDBO();
			$db->setQuery($q);
			//$res = $db->loadRow();
			if(true) {
				JToolBarHelper::custom('updateCustomsOrderItems', 'new', 'new', vmText::_('com_tsmart_REPORT_UPDATEORDERITEMS'),false);
				vmError('com_tsmart_UPDATEORDERITEMS_WARN');
			}*/
			/*
			 * UpdateStatus removed from the toolbar; don't understand how this was intented to work but
			 * the order ID's aren't properly passed. Might be readded later; the controller needs to handle
			 * the arguments.
			 */

			/* Toolbar */
			//JToolBarHelper::customX( 'CreateOrderHead', 'new','new','New',false);
			JToolBarHelper::save('updatestatus', tsmText::_('com_tsmart_UPDATE_STATUS'));

			if (vmAccess::manager('orders.delete')) {
				JToolBarHelper::spacer('80');
				JToolBarHelper::deleteList();
			}

			/* Assign the data */
			$this->assignRef('orderslist', $orderslist);

			$this->pagination = $model->getPagination();

		}
		if(JFactory::getApplication()->isSite()) {
			$bar = JToolBar::getInstance( 'toolbar' );
			$bar->appendButton( 'Link', 'back', 'com_tsmart_LEAVE', 'index.php?option=com_tsmart&manage=0' );
		}

		shopFunctions::checkSafePath();

		parent::display($tpl);
	}

}

