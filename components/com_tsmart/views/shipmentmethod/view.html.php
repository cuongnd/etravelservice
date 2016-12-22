<?php
/**
*
* Shipment  View
*
* @package	tsmart
* @subpackage Shipment
* @author RickG
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
 * HTML View class for maintaining the list of shipment
 *
 * @package	tsmart
 * @subpackage Shipment
 * @author RickG
 */
class TsmartViewShipmentmethod extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)
		$this->addHelperPath(VMPATH_ADMIN.DS.'helpers');

		if(!class_exists('vmPSPlugin')) require(VMPATH_PLUGINLIBS.DS.'vmpsplugin.php');

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();

		$layoutName = vRequest::getCmd('layout', 'default');
		$this->SetViewTitle();

		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {
			tsmConfig::loadJLang('plg_vmpsplugin', false);

			JForm::addFieldPath(VMPATH_ADMIN . DS . 'fields');

			$shipment = $model->getShipment();

			// Get the payment XML.
			$formFile	= vRequest::filterPath( VMPATH_ROOT .DS. 'plugins' .DS. 'vmshipment' .DS. $shipment->shipment_element .DS. $shipment->shipment_element . '.xml');
			if (file_exists($formFile)){
				$shipment->form = JForm::getInstance($shipment->shipment_element, $formFile, array(),false, '//tsmConfig | //config[not(//tsmConfig)]');
				$shipment->params = new stdClass();
				$varsToPush = vmPlugin::getVarsToPushFromForm($shipment->form);
				tsmTable::bindParameterableToSubField($shipment,$varsToPush);
				$shipment->form->bind($shipment->getProperties());

			} else {
				$shipment->form = null;
			}
			if (!class_exists('VmImage'))
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php');

			 if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
			 $vendor_id = 1;
			 $currency=tsmartModelVendor::getVendorCurrency ($vendor_id);
			 $this->assignRef('vendor_currency', $currency->currency_symbol);

			if($this->showVendors()){
					$vendorList= ShopFunctions::renderVendorList($shipment->tsmart_vendor_id);
					$this->assignRef('vendorList', $vendorList);
			 }

			$this->pluginList = self::renderInstalledShipmentPlugins($shipment->shipment_jplugin_id);
			$this->assignRef('shipment', $shipment);
			$this->shopperGroupList = ShopFunctions::renderShopperGroupList($shipment->tsmart_shoppergroup_ids,true);

			$this->addStandardEditViewCommands($shipment->tsmart_shipmentmethod_id);

		} else {
			JToolBarHelper::custom('cloneshipment', 'copy', 'copy', tsmText::_('com_tsmart_SHIPMENT_CLONE'), true);

			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model);

			$this->shipments = $model->getShipments();
			tsmConfig::loadJLang('com_tsmart_shoppers',TRUE);

			foreach ($this->shipments as &$data){
				// Write the first 5 shoppergroups in the list
				$data->shipmentShoppersList = shopfunctions::renderGuiList($data->tsmart_shoppergroup_ids,'shoppergroups','shopper_group_name','shopper');
			}

			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}
	function getParams($raw) {

		if (!empty($raw)) {
			$params = explode('|', substr($raw, 0,-1));
			foreach($params as $param){
				$item = explode('=',$param);
				if(!empty($item[1])){
					$pair[$item[0]] = str_replace('"','', $item[1]);
				} else {
					$pair[$item[0]] ='';
				}

			}
		}
		return $pair;
	}

	function renderInstalledShipmentPlugins($selected)
	{
		$db = JFactory::getDBO();

		$table = '#__extensions';
		$enable = 'enabled';
		$ext_id = 'extension_id';

		$q = 'SELECT * FROM `'.$table.'` WHERE `folder` = "vmshipment" AND `state`="0" ORDER BY `ordering`,`name` ASC';
		$db->setQuery($q);
		$result = $db->loadAssocList($ext_id);
		if(empty($result)){
			$app = JFactory::getApplication();
			$app -> enqueueMessage(tsmText::_('com_tsmart_NO_SHIPMENT_PLUGINS_INSTALLED'));
		}

		foreach ($result as &$sh) {
			$sh['name'] = tsmText::_($sh['name']);
		}
		$attribs='style= "width: 300px;"';
		return JHtml::_('select.genericlist', $result, 'shipment_jplugin_id', $attribs, $ext_id, 'name', $selected);
	}

}
// pure php no closing tag
