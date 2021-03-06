<?php
/**
 *
 * Data module for shipment
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
 * @version $Id: shipmentmethod.php 9029 2015-10-28 12:51:49Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for shop shipment
 *
 * @package	tsmart
 * @subpackage Shipment
 * @author RickG
 */
class tsmartModelShipmentmethod extends tmsModel {

	//    /** @var integer Primary key */
	//    var $_id;
	/** @var integer Joomla plugin ID */
	var $jplugin_id;
	/** @var integer Vendor ID */
	var $tsmart_vendor_id;

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('shipmentmethods');
		$this->_selectedOrdering = 'ordering';
		$this->setToggleName('shared');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author RickG
	 */
	function getShipment($id = 0) {

		if(!empty($id)) $this->_id = (int)$id;

		if (empty($this->_cache[$this->_id])) {
			$this->_cache[$this->_id] = $this->getTable('shipmentmethods');
			$this->_cache[$this->_id]->load((int)$this->_id);


			if(empty($this->_cache[$this->_id]->tsmart_vendor_id)){
				if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
				$this->_cache[$this->_id]->tsmart_vendor_id = tsmartModelVendor::getLoggedVendor();;
			}

			if ($this->_cache[$this->_id]->shipment_jplugin_id) {
				JPluginHelper::importPlugin ('vmshipment');
				$dispatcher = JDispatcher::getInstance ();
				$blind = 0;
				$retValue = $dispatcher->trigger ('plgVmDeclarePluginParamsShipmentVM3', array(&$this->_cache[$this->_id]));
			}

			if(!empty($this->_cache[$this->_id]->_varsToPushParam)){
				tsmTable::bindParameterable($this->_cache[$this->_id],'shipment_params',$this->_cache[$this->_id]->_varsToPushParam);
			}

			//We still need this, because the table is already loaded, but the keys are set later
			if($this->_cache[$this->_id]->getCryptedFields()){
				if(!class_exists('tsmCrypt')){
					require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcrypt.php');
				}

				if(isset($this->_cache[$this->_id]->modified_on)){
					$date = JFactory::getDate($this->_cache[$this->_id]->modified_on);
					$date = $date->toUnix();
				} else {
					$date = 0;
				}

				foreach($this->_cache[$this->_id]->getCryptedFields() as $field){
					if(isset($this->_cache[$this->_id]->$field)){
						$this->_cache[$this->_id]->$field = tsmCrypt::decrypt($this->_cache[$this->_id]->$field,$date);
					}
				}
			}

			/* Add the shipmentcarreir shoppergroups */
			$q = 'SELECT `tsmart_shoppergroup_id` FROM #__tsmart_shipmentmethod_shoppergroups WHERE `tsmart_shipmentmethod_id` = "'.$this->_id.'"';
			$this->_db->setQuery($q);
			$this->_cache[$this->_id]->tsmart_shoppergroup_ids = $this->_db->loadColumn();
			if(empty($this->_cache[$this->_id]->tsmart_shoppergroup_ids)) $this->_cache[$this->_id]->tsmart_shoppergroup_ids = 0;

		}

		return $this->_cache[$this->_id];
	}

	/**
	 * Retireve a list of shipment from the database.
	 *
	 * @author RickG
	 * @return object List of shipment  objects
	 */
	public function getShipments() {

		$whereString = '';

		$joins = ' FROM `#__tsmart_shipmentmethods` as i ';

		if(tsmConfig::$defaultLang!=tsmConfig::$vmlang and tsmConfig::$langCount>1){
			$langFields = array('shipment_name','shipment_desc');

			$useJLback = false;
			if(tsmConfig::$defaultLang!=tsmConfig::$jDefLang){
				$joins .= ' LEFT JOIN `#__tsmart_shipmentmethods_'.tsmConfig::$jDefLang.'` as ljd';
				$useJLback = true;
			}

			$select = ' i.*';
			foreach($langFields as $langField){
				$expr2 = 'ld.'.$langField;
				if($useJLback){
					$expr2 = 'IFNULL(ld.'.$langField.',ljd.'.$langField.')';
				}
				$select .= ', IFNULL(l.'.$langField.','.$expr2.') as '.$langField.'';
			}
			$joins .= ' LEFT JOIN `#__tsmart_shipmentmethods_'.tsmConfig::$defaultLang.'` as ld using (`tsmart_shipmentmethod_id`)';
			$joins .= ' LEFT JOIN `#__tsmart_shipmentmethods_'.tsmConfig::$vmlang.'` as l using (`tsmart_shipmentmethod_id`)';
		} else {
			$select = ' * ';
			$joins .= ' LEFT JOIN `#__tsmart_shipmentmethods_'.tsmConfig::$vmlang.'` as l USING (`tsmart_shipmentmethod_id`) ';
		}


		$datas =$this->exeSortSearchListQuery(0,$select,$joins,$whereString,' ',$this->_getOrdering() );

		if(isset($datas)){
			if(!class_exists('shopfunctions')) require(VMPATH_ADMIN.DS.'helpers'.DS.'shopfunctions.php');
			foreach ($datas as &$data){
				// Add the shipment shoppergroups
				$q = 'SELECT `tsmart_shoppergroup_id` FROM #__tsmart_shipmentmethod_shoppergroups WHERE `tsmart_shipmentmethod_id` = "'.$data->tsmart_shipmentmethod_id.'"';
				$db = JFactory::getDBO();
				$db->setQuery($q);
				$data->tsmart_shoppergroup_ids = $db->loadColumn();
			}
		}
		return $datas;
	}



	/**
	 * Bind the post data to the shipment tables and save it
	 *
	 * @author Max Milbers
	 * @return boolean True is the save was successful, false otherwise.
	 */
	public function store(&$data) {

		if ($data) {
			$data = (array)$data;
		}

		if(!vmAccess::manager('shipmentmethod.edit')){
			vmWarn('Insufficient permissions to store shipmentmethod');
			return false;
		} else if( empty($data['tsmart_shipment_id']) and !vmAccess::manager('shipmentmethod.create')){
			vmWarn('Insufficient permission to create shipmentmethod');
			return false;
		}

		if(!empty($data['params'])){
			foreach($data['params'] as $k=>$v){
				$data[$k] = $v;
			}
		}

		if(empty($data['tsmart_vendor_id'])){
			if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
			$data['tsmart_vendor_id'] = tsmartModelVendor::getLoggedVendor();
		} else {
			$data['tsmart_vendor_id'] = (int) $data['tsmart_vendor_id'];
		}

		$tb = '#__extensions';
		$ext_id = 'extension_id';

		$q = 'SELECT `element` FROM `' . $tb . '` WHERE `' . $ext_id . '` = "'.$data['shipment_jplugin_id'].'"';
		$db = JFactory::getDBO();
		$db->setQuery($q);
		$data['shipment_element'] = $db->loadResult();

		$table = $this->getTable('shipmentmethods');

		if(isset($data['shipment_jplugin_id'])){

			$q = 'UPDATE `#__extensions` SET `enabled`= 1 WHERE `extension_id` = "'.$data['shipment_jplugin_id'].'"';
			$db->setQuery($q);
			$db->execute();



			JPluginHelper::importPlugin('vmshipment');
			$dispatcher = JDispatcher::getInstance();
			//bad trigger, we should just give it data, so that the plugins itself can check the data to be stored
			//so this trigger is now deprecated and will be deleted in vm2.2
			$retValue = $dispatcher->trigger('plgVmSetOnTablePluginParamsShipment',array( $data['shipment_element'],$data['shipment_jplugin_id'],&$table));

			$retValue = $dispatcher->trigger('plgVmSetOnTablePluginShipment',array( &$data,&$table));

		}

		$table->bindChecknStore($data);

		$xrefTable = $this->getTable('shipmentmethod_shoppergroups');
		$xrefTable->bindChecknStore($data);

		if (!class_exists('vmPSPlugin')) require(VMPATH_PLUGINLIBS . DS . 'vmpsplugin.php');
		JPluginHelper::importPlugin('vmshipment');
		//Add a hook here for other shipment methods, checking the data of the choosed plugin
		$dispatcher = JDispatcher::getInstance();
		$retValues = $dispatcher->trigger('plgVmOnStoreInstallShipmentPluginTable', array(  $data['shipment_jplugin_id']));

		return $table->tsmart_shipmentmethod_id;
	}
	/**
	 * Creates a clone of a given shipmentmethod id
	 *
	 * @author Valérie Isaksen
	 * @param int $tsmart_shipmentmethod_id
	 */

	public function createClone ($id) {

		if(!vmAccess::manager('shipmentmethod.create')){
			vmWarn('Insufficient permissions to store shipmentmethod');
			return false;
		}

		$this->setId ($id);
		$shipment = $this->getShipment();
		$shipment->tsmart_shipmentmethod_id = 0;
		$shipment->shipment_name = $shipment->shipment_name.' Copy';
		$clone = $this->store($shipment);
		return $clone;
	}

	function remove($ids){
		if(!vmAccess::manager('shipmentmethod.delete')){
			vmWarn('Insufficient permissions to remove shipmentmethod');
			return false;
		}
		return parent::remove($ids);
	}
}

//no closing tag
