<?php
/**
*
* Data model for shopper group
*
* @package	tsmart
* @subpackage ShopperGroup
* @author Markus Öhler
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: shoppergroup.php 8953 2015-08-19 10:30:52Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for shopper group
 *
 * @package	tsmart
 * @subpackage ShopperGroup
 * @author Markus Öhler
 */
class tsmartModelShopperGroup extends tmsModel {

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct('tsmart_shoppergroup_id');
		$this->setMainTable('shoppergroups');

	}

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Markus Öhler
     */
    function getShopperGroup($id = 0) {

		return $this->getData($id);

	}


    /**
     * Retireve a list of shopper groups from the database.
     *
     * @author Markus Öhler
     * @param boolean $onlyPublished
     * @param boolean $noLimit True if no record count limit is used, false otherwise
     * @return object List of shopper group objects
     */
    function getShopperGroups($onlyPublished=false, $noLimit = false) {

		VmConfig::loadJLang('com_tsmart_shoppers',TRUE);
	    $query = 'SELECT * FROM `#__tsmart_shoppergroups`  ';
		if($onlyPublished){
			$query .= ' WHERE `published` = "1" ';
		}
		$query .= 'ORDER BY `tsmart_vendor_id`,`shopper_group_name` ';

		if ($noLimit) {
			$this->_data = $this->_getList($query);
		}
		else {
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

	    return $this->_data;
    }

/*	function makeDefault($id,$kind = 1) {

		//Prevent making anonymous Shoppergroup as default
		$adId = $this->getDefault(1);
		 $anonymous_sg_id = $adId->tsmart_shoppergroup_id;
		if($adId == $id){
			$group = $this->getShoppergroupById($id);
			vmError(vmText::sprintf('com_tsmart_SHOPPERGROUP_CANT_MAKE_DEFAULT',$group->shopper_group_name,$id));
			return false;
		}
		$db = JFactory::getDBO();
		$db->setQuery('UPDATE  `#__tsmart_shoppergroups`  SET `default` = 0 WHERE `default`<"2"');
		if (!$db->execute()) return ;
		$db->setQuery('UPDATE  `#__tsmart_shoppergroups`  SET `default` = "'.$kind.'" WHERE tsmart_shoppergroup_id='.(int)$id);
		if (!$db->execute()) return ;
		return true;
	}/*

	/**
	 *
	 * Get default shoppergroup for anonymous and non anonymous
	 * @param unknown_type $kind
	 */
	function getDefault($kind = 1, $onlyPublished = FALSE, $vendorId = 1){

		static $default = array();
		$kind = $kind + 1;
		if(!isset($default[$vendorId][$kind])){
			$q = 'SELECT * FROM `#__tsmart_shoppergroups` WHERE `default` = "'.$kind.'" AND (`tsmart_vendor_id` = "'.$vendorId.'" OR `shared` = "1") ';
			if($onlyPublished){
				$q .= ' AND `published`="1" ';
			}
			$db = JFactory::getDBO();
			$db->setQuery($q);

			if(!$res = $db->loadObject()){
				$app = JFactory::getApplication();
				$app->enqueueMessage('Attention no standard shopper group set '.$db->getErrorMsg());
				$default[$vendorId][$kind] = false;
			} else {
				if(!$res = $this->getShopperGroup($res->tsmart_shoppergroup_id)){

				}
				VmConfig::loadJLang('com_tsmart_shoppers',TRUE);
				$res->shopper_group_name = tsmText::_($res->shopper_group_name);
				$res->shopper_group_desc = tsmText::_($res->shopper_group_desc);
				//vmdebug('my default shoppergroup ',$res);
				$default[$vendorId][$kind] =  $res;
			}
		}

		return $default[$vendorId][$kind];

	}

	function appendShopperGroups(&$shopperGroups,$user,$onlyPublished = FALSE,$vendorId=1){

		$this->mergeSessionSgrps($shopperGroups);

		$testshopperGroups = array();
		foreach($shopperGroups as $groupId){
			$group = $this->getData($groupId);
			if(!$group->sgrp_additional){
				$testshopperGroups[] = $groupId;
			}
		}

		if(count($testshopperGroups)<1){

			$_defaultShopperGroup = $this->getDefault($user->guest,$onlyPublished,$vendorId);
			if(!in_array($_defaultShopperGroup->tsmart_shoppergroup_id,$shopperGroups)){
				$shopperGroups[] = $_defaultShopperGroup->tsmart_shoppergroup_id;
			}
		}

		$this->removeSessionSgrps($shopperGroups);

	}

	function mergeSessionSgrps(&$ids){
		$session = JFactory::getSession();
		$shoppergroup_ids = $session->get('vm_shoppergroups_add',array(),'vm');

		$ids = array_merge($ids,(array)$shoppergroup_ids);
		$ids = array_unique($ids);
		//$session->set('vm_shoppergroups_add',array(),'vm');
		//vmdebug('mergeSessionSgrps',$shoppergroup_ids,$ids);
	}

	function removeSessionSgrps(&$ids){
		$session = JFactory::getSession();
		$shoppergroup_ids_remove = $session->get('vm_shoppergroups_remove',0,'vm');
		if($shoppergroup_ids_remove!==0){

			if(!is_array($shoppergroup_ids_remove)){
				$shoppergroup_ids_remove = (array) $shoppergroup_ids_remove;
			}

			foreach($shoppergroup_ids_remove as $k => $id){
				if(in_array($id,$ids)){
					$key=array_search($id, $ids);
					if($key!==FALSE){
						unset($ids[$key]);
					}
				}
			}
		}

	}

	function store(&$data){
		if(!vmAccess::manager('shoppergroup.edit')){
			vmWarn('Insufficient permissions to store shoppergroup');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){

		if(!vmAccess::manager('shoppergroup.edit')){
			vmWarn('Insufficient permissions to delete shoppergroup');
			return false;
		}

		$table = $this->getTable($this->_maintablename);

		$defaultSgId = $this->getDefault(0);
		$anonymSgId = $this->getDefault(1);
		$db = JFactory::getDBO();
		foreach($ids as $id){

			//Test if shoppergroup is default
			if($id == $defaultSgId->tsmart_shoppergroup_id){

				$db->setQuery('SELECT shopper_group_name FROM `#__tsmart_shoppergroups`  WHERE `tsmart_shoppergroup_id` = "'.(int)$id.'"');
				$name = $db->loadResult();
				vmError(tsmText::sprintf('com_tsmart_SHOPPERGROUP_DELETE_CANT_DEFAULT',tsmText::_($name),$id));
				continue;
			}

			//Test if shoppergroup is default
			if($id == $anonymSgId->tsmart_shoppergroup_id){
				$db->setQuery('SELECT shopper_group_name FROM `#__tsmart_shoppergroups`  WHERE `tsmart_shoppergroup_id` = "'.(int)$id.'"');
				$name = $db->loadResult();
				vmError(tsmText::sprintf('com_tsmart_SHOPPERGROUP_DELETE_CANT_DEFAULT',tsmText::_($name),$id));
				continue;
			}

			//Test if shoppergroup has members
			$db->setQuery('SELECT * FROM `#__tsmart_vmuser_shoppergroups`  WHERE `tsmart_shoppergroup_id` = "'.(int)$id.'"');
			if($db->loadResult()){
				$db->setQuery('SELECT shopper_group_name FROM `#__tsmart_shoppergroups`  WHERE `tsmart_shoppergroup_id` = "'.(int)$id.'"');
				$name = $db->loadResult();
				vmError(tsmText::sprintf('com_tsmart_SHOPPERGROUP_DELETE_CANT_WITH_MEMBERS',tsmText::_($name),$id));
				continue;
			}

			if (!$table->delete($id)) {
				vmError(get_class( $this ).'::remove '.$id);
				return false;
		    }
		}

		return true;
	}

	/**
	 * Retrieves the Shopper Group Info of the SG specified by $id
	 *
	 * @param int $id
	 * @param boolean $default_group
	 * @return array
	 */
  	static function getShoppergroupById($id, $default_group = false) {
    	$tsmart_vendor_id = 1;
    	$db = JFactory::getDBO();

    	$q =  'SELECT `#__tsmart_shoppergroups`.`tsmart_shoppergroup_id`, `#__tsmart_shoppergroups`.`shopper_group_name`, `default` AS default_shopper_group FROM `#__tsmart_shoppergroups`';

    	if (!empty($id) && !$default_group) {
      		$q .= ', `#__tsmart_vmuser_shoppergroups`';
      		$q .= ' WHERE `#__tsmart_vmuser_shoppergroups`.`tsmart_user_id`="'.(int)$id.'" AND ';
      		$q .= '`#__tsmart_shoppergroups`.`tsmart_shoppergroup_id`=`#__tsmart_vmuser_shoppergroups`.`tsmart_shoppergroup_id`';
    	}
    	else {
    		$q .= ' WHERE `#__tsmart_shoppergroups`.`tsmart_vendor_id`="'.(int)$tsmart_vendor_id.'" AND `default`="2"';
    	}

    	$db->setQuery($q);
    	return $db->loadAssocList();
  	}

}
// pure php no closing tag