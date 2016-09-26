<?php
/**
 *
 * Data module for shop currencies
 *
 * @package	tsmart
 * @subpackage Currency
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package	tsmart
 * @subpackage Currency
 */
class tsmartModelHighlight extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('highlight');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem($id=0) {
		return $this->getData($id);
	}


	/**
	 * Retireve a list of currencies from the database.
	 * This function is used in the backend for the currency listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of currency objects
	 */
	function getItemList($search='') {

		$where = array();

		$user = JFactory::getUser();
		$shared = '';
		if(vmAccess::manager() ){
			$shared = 'OR `shared`="1"';
		}

		if(empty($search)){
			$search = vRequest::getString('search', false);
		}
		// add filters
		if($search){
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape( $search, true ) . '%"' ;
			$where[] = '`title` LIKE '.$search;
		}

		$whereString='';
		if (count($where) > 0) $whereString = ' WHERE '.implode(' AND ', $where) ;

		$data = $this->exeSortSearchListQuery(0,'*',' FROM `#__tsmart_highlight`',$whereString,'',$this->_getOrdering());

		return $data;
	}


	/**
	 * Retireve a list of currencies from the database.
	 *
	 * This is written to get a list for selecting currencies. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of currency objects
	 */

	function store(&$data){
		if(!vmAccess::manager('highlight')){
			vmWarn('Insufficient permissions to store highlight');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('highlight')){
			vmWarn('Insufficient permissions to remove highlight');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag