<?php
/**
 *
 * Calc View
 *
 * @package	tsmart
 * @subpackage Payment Method
 * @author Max Milbers
 * @author valérie isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 9041 2015-11-05 11:59:38Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * Description
 *
 * @package		tsmart
 * @author valérie isaksen
 */
if (!class_exists('tsmartModelCurrency'))
require(VMPATH_ADMIN . DS . 'models' . DS . 'currency.php');

class TsmartViewPaymentMethod extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)
		$this->addHelperPath(VMPATH_ADMIN.DS.'helpers');

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		if (!class_exists ('vmPSPlugin')) {
			require(VMPATH_PLUGINLIBS . DS . 'vmpsplugin.php');
		}

		$this->user = JFactory::getUser();
		$model = tmsModel::getModel('paymentmethod');

		// TODO logo
		$this->SetViewTitle();

		$layoutName = vRequest::getCmd('layout', 'default');

		$vendorModel = tmsModel::getModel('vendor');

		$vendorModel->setId(1);
		$vendor = $vendorModel->getVendor();
		$currencyModel = tmsModel::getModel('currency');
		$currencyModel = $currencyModel->getItemList($vendor->vendor_currency);
		$this->assignRef('vendor_currency', $currencyModel->currency_symbol);

		if ($layoutName == 'edit') {

			// Load the helper(s)
			if (!class_exists('VmImage'))
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php');

			tsmConfig::loadJLang('plg_vmpsplugin', false);

			JForm::addFieldPath(VMPATH_ADMIN . DS . 'fields');

			$payment = $model->getPayment();

			// Get the payment XML.
			$formFile	= vRequest::filterPath( VMPATH_ROOT .DS. 'plugins'. DS. 'vmpayment' .DS. $payment->payment_element .DS. $payment->payment_element . '.xml');
			if (file_exists($formFile)){

				$payment->form = JForm::getInstance($payment->payment_element, $formFile, array(),false, '//tsmConfig | //config[not(//tsmConfig)]');
				$payment->params = new stdClass();
				$varsToPush = vmPlugin::getVarsToPushFromForm($payment->form);
				tsmTable::bindParameterableToSubField($payment,$varsToPush);
				$payment->form->bind($payment->getProperties());

			} else {
				$payment->form = null;
			}

			$this->assignRef('payment',	$payment);
			$this->vmPPaymentList = self::renderInstalledPaymentPlugins($payment->payment_jplugin_id);
			$this->shopperGroupList = ShopFunctions::renderShopperGroupList($payment->tsmart_shoppergroup_ids, true);

			if($this->showVendors()){
				$vendorList= ShopFunctions::renderVendorList($payment->tsmart_vendor_id);
				$this->assignRef('vendorList', $vendorList);
			}

			$this->addStandardEditViewCommandsPopup( $payment->tsmart_paymentmethod_id);
		} else {
			JToolBarHelper::custom('clonepayment', 'copy', 'copy', tsmText::_('com_tsmart_PAYMENT_CLONE'), true);

			$this->addStandardDefaultViewCommandsPopup();
			$this->addStandardDefaultViewLists($model);

			$this->payments = $model->getPayments();
			tsmConfig::loadJLang('com_tsmart_shoppers',TRUE);

			foreach ($this->payments as &$data){
				// Write the first 5 shoppergroups in the list
				$data->paymShoppersList = shopfunctions::renderGuiList($data->tsmart_shoppergroup_ids,'shoppergroups','shopper_group_name','payment' );
			}

			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}

	function renderInstalledPaymentPlugins($selected){

		$db = JFactory::getDBO();

		$q = 'SELECT * FROM `#__extensions` WHERE `folder` = "vmpayment" and `state`="0"  ORDER BY `ordering`,`name` ASC';
		$db->setQuery($q);
		$result = $db->loadAssocList('extension_id');
		if(empty($result)){
			$app = JFactory::getApplication();
			$app -> enqueueMessage(tsmText::_('com_tsmart_NO_PAYMENT_PLUGINS_INSTALLED'));
		}

		$listHTML='<select id="payment_jplugin_id" name="payment_jplugin_id" style= "width: 300px;">';

		foreach($result as $paym){
			if($paym['extension_id']==$selected) $checked='selected="selected"'; else $checked='';
			// Get plugin info
			$listHTML .= '<option '.$checked.' value="'.$paym['extension_id'].'">'.tsmText::_($paym['name']).'</option>';

		}
		$listHTML .= '</select>';

		return $listHTML;
	}

}
// pure php not tag
