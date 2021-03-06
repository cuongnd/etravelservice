<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved by the author.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: custom.php 3057 2011-04-19 12:59:22Z Electrocity $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model for tsmart Customs Fields
 *
 * @package		tsmart
 */
class tsmartModelCustom extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct('tsmart_custom_id');
		$this->setMainTable('customs');
		$this->setToggleName('admin_only');
		$this->setToggleName('is_hidden');
	}

	/** @return autorized Types of data **/
	static function getCustomTypes () {

		return array('S' => 'com_tsmart_CUSTOM_STRING',
			'P' => 'com_tsmart_CUSTOM_PROPERTY',
			'B' => 'com_tsmart_CUSTOM_BOOLEAN',
			'D' => 'com_tsmart_DATE',
			'T' => 'com_tsmart_TIME',
			'M' => 'com_tsmart_IMAGE',
			'X' => 'com_tsmart_CUSTOM_EDITOR',
			'Y' => 'com_tsmart_CUSTOM_TEXTAREA',
			'C' => 'com_tsmart_MULTIVARIANT',
			'A' => 'com_tsmart_CHILD_GENERIC_VARIANT',
			'E' => 'com_tsmart_CUSTOM_EXTENSION',
			'G' => 'com_tsmart_CUSTOM_GROUP',
			'R'=>'com_tsmart_RELATED_PRODUCTS',
			'Z'=>'com_tsmart_RELATED_CATEGORIES'
		);

		// 'U'=>'com_tsmart_CUSTOM_CART_USER_VARIANT',
		// 'C'=>'com_tsmart_CUSTOM_PRODUCT_CHILD',
		// 'G'=>'com_tsmart_CUSTOM_PRODUCT_CHILD_GROUP',
		//
	}

    /**
     * Gets a single custom by tsmart_custom_id
     * .
     * @param string $type
     * @param string $mime mime type of custom, use for exampel image
     * @return customobject
     */
    function getCustom($id = 0){

		if(!empty($id)) $this->_id = (int)$id;

    	if(empty($this->_cache[$this->_id])){

    		$this->_cache[$this->_id] = $this->getTable('customs');
			$this->_cache[$this->_id]->load($this->_id);

		    $this->_cache[$this->_id]->_varsToPushParam = self::getVarsToPush($this->_cache[$this->_id]->field_type);

			$this->_cache[$this->_id]->customfield_params = '';
		    if ($this->_cache[$this->_id]->field_type == 'E') {
			    JPluginHelper::importPlugin ('vmcustom');
			    $dispatcher = JDispatcher::getInstance ();
			    $retValue = $dispatcher->trigger ('plgVmDeclarePluginParamsCustomVM3', array(&$this->_cache[$this->_id]));
		    }
			//exaample 	vm2 withParent="0"|parentOrderable="0"|
			//			vm3 withParent="1"|parentOrderable="1"|
			$this->_cache[$this->_id]->_xParams = 'custom_params';
			if(!empty($this->_cache[$this->_id]->_varsToPushParam)){
				tsmTable::bindParameterable($this->_cache[$this->_id],'custom_params',$this->_cache[$this->_id]->_varsToPushParam);
			}
    	}

  		return $this->_cache[$this->_id];

    }


    /**
	 * Retrieve a list of customs from the database. This is meant only for backend use
	 *
	 * @author Kohl Patrick
	 * @author Max Milbers
	 * @return object List of custom objects
	 */
    function getCustoms($custom_parent_id,$search = false){

	    $query='* FROM `#__tsmart_customs` ';

		$where = array();
		if($custom_parent_id){
			$where[] = ' `custom_parent_id` ='.(int)$custom_parent_id;
		}

		if($search){
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape( $search, true ) . '%"' ;
			$where[] = ' `custom_title` LIKE '.$search;
		}

		if (count ($where) > 0) {
			$whereString = ' WHERE (' . implode (' AND ', $where) . ') ';
		}
		else {
			$whereString = '';
		}
	    $datas = new stdClass();
		$datas->items = $this->exeSortSearchListQuery(0, $query, '',$whereString,$this->_getOrdering());

		if (!class_exists('VmHTML')) require(VMPATH_ADMIN.DS.'helpers'.DS.'html.php');
		$field_types = self::getCustomTypes() ;

		foreach ($datas->items as $key => & $data) {

			if (!empty($data->custom_parent_id)){
				$g = $this->getCustom($data->custom_parent_id);
				$data->group_title = $g->custom_title;
			}
			else {
				$data->group_title =  '-' ;
			}
			if(!empty($field_types[$data->field_type ])){
				$data->field_type_display = tsmText::_( $field_types[$data->field_type ] );
			} else {
				$data->field_type_display = 'not valid, delete this line';
				vmError('The field with id '.$data->tsmart_custom_id.' and title '.$data->custom_title.' is not longer valid, please delete it from the list');
			}

		}

		return $datas;
    }


	/**
	 * Displays a possibility to select created custom
	 *
	 * @author Max Milbers
	 * @author Patrick Kohl
	 */
	public function displayCustomSelection () {

		$customslist = $this->getParentList ();
		if (isset($this->tsmart_custom_id)) {
			$value = $this->tsmart_custom_id;
		}
		else {
			$value = vRequest::getInt ('custom_parent_id', 0);
		}
		return VmHTML::row ('select', 'com_tsmart_CUSTOM_GROUP', 'custom_parent_id', $customslist, $value);
	}

	/**
	 * Retrieve a list of layouts from the default and chosen templates directory.
	 *
	 * We may use here the getCustoms function of the custom model or write something simular
	 *
	 * @author Max Milbers
	 * @param name of the view
	 * @return object List of flypage objects
	 */
	function getCustomsList ($publishedOnly = FALSE) {

		// get custom parents
		$q = 'SELECT `tsmart_custom_id` AS value ,custom_title AS text FROM `#__tsmart_customs` WHERE custom_parent_id="0" AND field_type <> "R" AND field_type <> "Z" ';
		if ($publishedOnly) {
			$q .= 'AND `published`=1';
		}
		if ($ID = vRequest::getInt ('tsmart_custom_id', 0)) {
			$q .= ' AND `tsmart_custom_id`!=' . (int)$ID;
		}
		$db = JFactory::getDBO();
		$db->setQuery ($q);

		$result = $db->loadObjectList ();

		return $result;
	}

	/**
	 *
	 * Enter description here ...
	 *
	 * @param unknown_type $excludedId
	 * @return unknown|multitype:
	 */
	function getParentList ($excludedId = 0) {
		$db = JFactory::getDBO();
		$db->setQuery (' SELECT tsmart_custom_id as value,custom_title as text FROM `#__tsmart_customs` WHERE `field_type` ="G" and tsmart_custom_id!=' . $excludedId);
		return $db->loadObjectList ();
	}


	/**
	 * Creates a clone of a given custom id
	 *
	 * @author Max Milbers
	 * @param int $tsmart_product_id
	 */

	public function createClone($id){
		$this->tsmart_custom_id = $id;
		$row = $this->getTable('customs');
		$row->load( $id );
		$row->tsmart_custom_id = 0;
		$row->custom_title = $row->custom_title.' Copy';

		if (!$clone = $row->store()) {
			vmError('createClone failed for '. $id );
		}
		return $clone;
	}


	/* Save and delete from database
	 *  all Child product custom_fields relation
	 * 	@ var   $table	: the xref table(eg. product,category ...)
	 * 	@array $data	: array of customfields
	 * 	@int     $id		: The concerned id (eg. product_id)
	 **/
	public function saveChildCustomRelation($table,$datas) {

		vRequest::vmCheckToken('Invalid token in saveChildCustomRelation');
		//Table whitelist
		$tableWhiteList = array('product','category','manufacturer');
		if(!in_array($table,$tableWhiteList)) return false;

		$db = JFactory::getDBO();
		// delete existings from modelXref and table customfields
		foreach ($datas as $child_id =>$fields) {
			$fields['tsmart_'.$table.'_id']=$child_id;
			$db->setQuery( 'DELETE PC FROM `#__tsmart_'.$table.'_customfields` as `PC`, `#__tsmart_customs` as `C` WHERE `PC`.`tsmart_custom_id` = `C`.`tsmart_custom_id` AND field_type="C" and tsmart_'.$table.'_id ='.$child_id );
			if(!$db->execute()){
				vmError('Error in deleting child relation '); //.$db->getQuery()); Dont give hackers too much info
			}

			$tableCustomfields = $this->getTable($table.'_customfields');
			$tableCustomfields->bindChecknStore($fields);

		}

	}


	public function store(&$data){

		if(!vmAccess::manager('custom.edit')){
			vmWarn('Insufficient permission to edit custom');
			return false;
		}

		if(!empty($data['params'])){
			foreach($data['params'] as $k=>$v){
				$data[$k] = $v;
			}
		}

		//I think this is obsolete, note by Max
		if(empty($data['tsmart_vendor_id'])){
			if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
			$data['tsmart_vendor_id'] = tsmartModelVendor::getLoggedVendor();
		} else {
			$data['tsmart_vendor_id'] = (int) $data['tsmart_vendor_id'];
		}

		$table = $this->getTable('customs');

		if(!empty($data['custom_jplugin_id'])){

			$tb = '#__extensions';
			$ext_id = 'extension_id';

			$db = JFactory::getDBO();

			$q = 'SELECT `element` FROM `' . $tb . '` WHERE `' . $ext_id . '` = "'.$data['custom_jplugin_id'].'"';
			$db->setQuery($q);
			$cElement = $db->loadResult();
			if(empty($cElement)){

				if(!empty($data['tsmart_custom_id'])){
					$table->load($data['tsmart_custom_id']);
					$cElement = $table->custom_element;
				} else if(!empty($data['custom_element'])){
					$cElement = $data['custom_element'];
				}

				$q = 'SELECT * FROM `' . $tb . '` WHERE `element` = "'.$cElement.'" ';
				$db->setQuery($q);
				if($jids=$db->loadAssocList()){

					$newJid = 0;
					foreach($jids as $jid){
						$newJid = $jid[$ext_id];
						if($jid['enabled'] == 1 and $jid['state'] == 0){
							break;
						}
					}
					vmdebug('Available entries ',$newJid,$jids);
					if(!empty($newJid)){
						$q = 'UPDATE `#__tsmart_customs` SET `custom_jplugin_id`="'.$jid.'" WHERE `custom_jplugin_id` = "'.$data['custom_jplugin_id'].'"';
						$db->setQuery($q);
						$db->execute();
						$data['custom_jplugin_id'] = $newJid;
						vmInfo('Old Plugin id was not available, updated entries with '.$ext_id.' = '.$newJid.' found for the same element');
					}
				} else {
					vmWarn('could not load custom_element for plugin, testing if current custom_jplugin_id is still available '.$q);
				}
			}

			if(!empty($cElement)){
				$data['custom_element'] = $cElement;
			}
			$q = 'UPDATE `#__extensions` SET `enabled`= 1, `state` = 0 WHERE `extension_id` = "'.$data['custom_jplugin_id'].'"';
			$db->setQuery($q);
			$db->execute();
		}

		$table->field_type = $data['field_type'];
		if($table->field_type == 'C'){
			//vmInfo();
			//$data['is_cart_attribute'] = 1;
		}
		$table->custom_element = $data['custom_element'];
		$table->custom_jplugin_id = $data['custom_jplugin_id'];
		$table->_xParams = 'custom_params';

		if(!empty($data['is_input'])){
			if(empty($data['layout_pos'])) $data['layout_pos'] = 'addtocart';
		}

		//We are in the custom and so the table contains the field_type, else not!!
		self::setParameterableByFieldType($table,$table->field_type);
		if(empty($data['tsmart_custom_id']) and !vmAccess::manager('custom.create')){
			vmWarn('Insufficient permission to create custom');
			return false;
		}
		$table->bindChecknStore($data);

		JPluginHelper::importPlugin('vmcustom');
		$dispatcher = JDispatcher::getInstance();
		$error = $dispatcher->trigger('plgVmOnStoreInstallPluginTable', array('custom' , $data, $table));

		return $table->tsmart_custom_id ;

	}

	/**
	 * Sets the xParams field and $varsToPush to the used table object,
	 * called in the store functions Customfields::storeProductCustomfields and custom store
	 * @param $table
	 * @param $type
	 * @param int $custom_element
	 * @param int $custom_jplugin_id
	 */
	static function setParameterableByFieldType(&$table, $type, $custom_element=0,$custom_jplugin_id=0){

		//$type = $table->field_type;
		if($custom_element===0){
			$custom_element = $table->custom_element;
		}

		if($custom_jplugin_id===0){
			$custom_jplugin_id = $table->custom_jplugin_id;
		}

		$varsToPush = self::getVarsToPush($type);
		$xParams = $table->_xParams;

		if ($type == 'E') {
			JPluginHelper::importPlugin ('vmcustom');
			$dispatcher = JDispatcher::getInstance ();
			//We call here vmplugin->getTablePluginParams which sets the xParam and the varsToPush of the Plugin
			vmdebug('setParameterableByFieldType before trigger plgVmGetTablePluginParams ',$custom_element, $custom_jplugin_id, $xParams,$varsToPush);
			$retValue = $dispatcher->trigger ('plgVmGetTablePluginParams', array('custom',$custom_element, $custom_jplugin_id, &$xParams, &$varsToPush));
		}
		$xParams = $table->_xParams;
		if(!empty($varsToPush)){
			$table->setParameterable($xParams,$varsToPush,TRUE);
		}

	}

	static function getVarsToPush($type){

		$varsToPush = 0;
		if($type=='A'){
			$varsToPush = array(
				'withParent'        => array(0, 'int'),
				'parentOrderable'   => array(0, 'int'),
				'wPrice'		=> array(0, 'int')
			);
		} else if($type=='P'){
			$varsToPush = array(
			'round' 	=> array('', 'int')
			);
		} else if($type=='C'){
			$varsToPush = array(
				'usecanonical' 	=> array(0, 'int'),
				'showlabels'	=> array(0, 'int'),
				'sCustomId'		=> array(0, 'int', 'scustom'),
				'selectoptions'	=> array(0, 'int'),
				'clabels'   	=> array(0, 'int'),
				'options'		=> array(0, 'int')
			);
		} else if($type=='S' or $type=='B'){
			$varsToPush = array(
				'addEmpty'		=> array(0, 'int'),
				'selectType'	=> array(0, 'int')
			);
		} else if($type=='M'){
			$varsToPush = array(
				'width'		=> array(tsmConfig::get('img_width',90), 'string'),
				'height'	=> array(tsmConfig::get('img_width',90), 'string'),
				'addEmpty'		=> array(0, 'int'),
				'selectType'	=> array(1, 'int')
			);
		} else if($type=='R'){
			$varsToPush = array(
				'wPrice'	=> array(0, 'int'),
				'wImage'	=> array(1, 'int'),
				'wDescr'	=> array(0, 'int'),
				'width'		=> array(tsmConfig::get('img_width',90), 'string'),
				'height'	=> array(tsmConfig::get('img_width',90), 'string')
			);
		} else if($type=='Z'){
			$varsToPush = array(
				'wImage'	=> array(1, 'int'),
				'wDescr'	=> array(0, 'int'),
				'width'		=> array(tsmConfig::get('img_width',90), 'string'),
				'height'	=> array(tsmConfig::get('img_width',90), 'string')
			);
		}
		return $varsToPush;
	}


	/**
	 * Delete all record ids selected
	 *
	 * @author Max Milbers
	 * @return boolean True is the delete was successful, false otherwise.
	 */
	public function remove($ids) {

		if(!vmAccess::manager('custom.delete')){
			vmWarn('Insufficient permissions to delete custom');
			return false;
		}

		$table = $this->getTable($this->_maintablename);
		$customfields = $this->getTable ('product_customfields');

		foreach($ids as $id) {
			if (!$table->delete((int)$id)) {
				vmError(get_class( $this ).'::remove '.$id.' failed ');
				return false;
			} else {
				//Delete this customfield also in all product_customfield tables
				if (!$customfields->delete ($id, 'tsmart_custom_id')) {
					vmError ('Custom delete Productcustomfield delete failed');
					$ok = FALSE;
				}
			}
		}
		return true;
	}

}
// pure php no closing tag
