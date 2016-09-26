<?php
/**
*
* Model paymentmethod
*
* @package	tsmart
* @subpackage  Payment
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2014 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: paymentmethod.php 9029 2015-10-28 12:51:49Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

class tsmartModelPaymentmethod extends VmModel{

	function __construct() {
		parent::__construct();
		$this->setMainTable('paymentmethods');
		$this->_selectedOrdering = 'ordering';
		$this->setToggleName('shared');
	}

	/**
	 * Gets the tsmart_paymentmethod_id with a plugin and vendorId
	 *
	 * @author Max Milbers
	 */
	public function getIdbyCodeAndVendorId($jpluginId,$vendorId=1){
	 	if(!$jpluginId) return 0;
	 	$q = 'SELECT `tsmart_paymentmethod_id` FROM #__tsmart_paymentmethods WHERE `payment_jplugin_id` = "'.$jpluginId.'" AND `tsmart_vendor_id` = "'.$vendorId.'" ';
		$db = JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadResult();
	}

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
	public function getPayment($id = 0){

		if(!empty($id)) $this->_id = (int)$id;

		if (empty($this->_cache[$this->_id])) {
			$this->_cache[$this->_id] = $this->getTable('paymentmethods');
			$this->_cache[$this->_id]->load((int)$this->_id);

			if(empty($this->_cache->tsmart_vendor_id)){
				if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
				$this->_cache[$this->_id]->tsmart_vendor_id = tsmartModelVendor::getLoggedVendor();
			}

			if($this->_cache[$this->_id]->payment_jplugin_id){
				JPluginHelper::importPlugin('vmpayment');
				$dispatcher = JDispatcher::getInstance();
				$retValue = $dispatcher->trigger ('plgVmDeclarePluginParamsPaymentVM3', array(&$this->_cache[$this->_id]));
			}

			if(!empty($this->_cache[$this->_id]->_varsToPushParam)){
				tsmTable::bindParameterable($this->_cache[$this->_id],'payment_params',$this->_cache[$this->_id]->_varsToPushParam);
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

			$q = 'SELECT `tsmart_shoppergroup_id` FROM #__tsmart_paymentmethod_shoppergroups WHERE `tsmart_paymentmethod_id` = "'.$this->_id.'"';
			$this->_db->setQuery($q);
			$this->_cache[$this->_id]->tsmart_shoppergroup_ids = $this->_db->loadColumn();
			if(empty($this->_cache[$this->_id]->tsmart_shoppergroup_ids)) $this->_cache[$this->_id]->tsmart_shoppergroup_ids = 0;

		}

		return $this->_cache[$this->_id];
	}

	/**
	 * Retireve a list of calculation rules from the database.
	 *
     * @author Max Milbers
     * @param string $onlyPuiblished True to only retreive the publish Calculation rules, false otherwise
     * @param string $noLimit True if no record count limit is used, false otherwise
	 * @return object List of calculation rule objects
	 */
	public function getPayments($onlyPublished=false, $noLimit=false) {

		$where = array();
		if ($onlyPublished) {
			$where[] = ' `published` = 1';
		}

		$whereString = '';
		if (count($where) > 0) $whereString = ' WHERE '.implode(' AND ', $where) ;


		$joins = ' FROM `#__tsmart_paymentmethods` as i ';

		if(VmConfig::$defaultLang!=VmConfig::$vmlang and Vmconfig::$langCount>1){
			$langFields = array('payment_name','payment_desc');

			$useJLback = false;
			if(VmConfig::$defaultLang!=VmConfig::$jDefLang){
				$joins .= ' LEFT JOIN `#__tsmart_paymentmethods_'.VmConfig::$jDefLang.'` as ljd';
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
			$joins .= ' LEFT JOIN `#__tsmart_paymentmethods_'.VmConfig::$defaultLang.'` as ld using (`tsmart_paymentmethod_id`)';
			$joins .= ' LEFT JOIN `#__tsmart_paymentmethods_'.VmConfig::$vmlang.'` as l using (`tsmart_paymentmethod_id`)';
		} else {
			$select = ' * ';
			$joins .= ' LEFT JOIN `#__tsmart_paymentmethods_'.VmConfig::$vmlang.'` as l USING (`tsmart_paymentmethod_id`) ';
		}

		$datas =$this->exeSortSearchListQuery(0,$select,$joins,$whereString,' ',$this->_getOrdering() );

		if(isset($datas)){

			if(!class_exists('shopfunctions')) require(VMPATH_ADMIN.DS.'helpers'.DS.'shopfunctions.php');
			foreach ($datas as &$data){
				/* Add the paymentmethod shoppergroups */
				$q = 'SELECT `tsmart_shoppergroup_id` FROM #__tsmart_paymentmethod_shoppergroups WHERE `tsmart_paymentmethod_id` = "'.$data->tsmart_paymentmethod_id.'"';
				$db = JFactory::getDBO();
				$db->setQuery($q);
				$data->tsmart_shoppergroup_ids = $db->loadColumn();

			}

		}
		return $datas;
	}


	/**
	 * Bind the post data to the paymentmethod tables and save it
     *
     * @author Max Milbers
     * @return boolean True is the save was successful, false otherwise.
	 */
    public function store(&$data){

		if ($data) {
			$data = (array)$data;
		}

		if(!vmAccess::manager('paymentmethod.edit')){
			vmWarn('Insufficient permissions to store paymentmethod');
			return false;
		} else if( empty($data['tsmart_payment_id']) and !vmAccess::manager('paymentmethod.create')){
			vmWarn('Insufficient permission to create paymentmethod');
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
	  	}

		$table = $this->getTable('paymentmethods');

		if(isset($data['payment_jplugin_id'])){

			$q = 'SELECT `element` FROM `#__extensions` WHERE `extension_id` = "'.$data['payment_jplugin_id'].'"';
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$data['payment_element'] = $db->loadResult();

			$q = 'UPDATE `#__extensions` SET `enabled`= 1 WHERE `extension_id` = "'.$data['payment_jplugin_id'].'"';
			$db->setQuery($q);
			$db->execute();

			JPluginHelper::importPlugin('vmpayment');
			$dispatcher = JDispatcher::getInstance();
			$retValue = $dispatcher->trigger('plgVmSetOnTablePluginParamsPayment',array( $data['payment_element'],$data['payment_jplugin_id'],&$table));

		}

		$table->bindChecknStore($data);


		$xrefTable = $this->getTable('paymentmethod_shoppergroups');
		$xrefTable->bindChecknStore($data);

		if (!class_exists('vmPSPlugin')) require(VMPATH_PLUGINLIBS . DS . 'vmpsplugin.php');
			JPluginHelper::importPlugin('vmpayment');
			//Add a hook here for other payment methods, checking the data of the choosed plugin
			$dispatcher = JDispatcher::getInstance();
			$retValues = $dispatcher->trigger('plgVmOnStoreInstallPaymentPluginTable', array(  $data['payment_jplugin_id']));

		return $table->tsmart_paymentmethod_id;
	}



	/**
	 * Due the new plugin system this should be obsolete
	 * function to render the payment plugin list
	 *
	 * @author Max Milbers
	 *
	 * @param radio list of creditcards
	 * @return html
	 */
	public function renderPaymentList($selectedPaym=0){

		$payms = self::getPayments(false,true);
		$listHTML='';
		foreach($payms as $item){
			$checked='';
			if($item->tsmart_paymentmethod_id==$selectedPaym){
				$checked='"checked"';
			}
			$listHTML .= '<input type="radio" name="tsmart_paymentmethod_id" value="'.$item->tsmart_paymentmethod_id.'" '.$checked.'>'.$item->payment_name.' <br />';
			$listHTML .= ' <br />';
		}

		return $listHTML;

	}

	public function createClone ($id) {

		if(!vmAccess::manager('paymentmethod.create')){
			vmWarn('Insufficient permissions to store paymentmethod');
			return false;
		}

		$this->setId ($id);
		$payment = $this->getPayment();
		$payment->tsmart_paymentmethod_id = 0;
		$payment->payment_name = $payment->payment_name.' Copy';
		if (!$clone = $this->store($payment)) {
			JError::raiseError(500, 'createClone '. $payment->getError() );
		}
		return $clone;
	}

	function remove($ids){
		if(!vmAccess::manager('paymentmethod.delete')){
			vmWarn('Insufficient permissions to remove paymentmethod');
			return false;
		}
		return parent::remove($ids);
	}

}
