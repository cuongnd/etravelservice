<?php
/**
 *
 * Data module for shop orderstates
 *
 * @package	tsmart
 * @subpackage orderstates
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: orderstates.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for shop orderstates
 *
 * @package	tsmart
 * @subpackage orderstates
 */
class tsmartModelorderstates extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('orderstates');
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
	 * Retireve a list of orderstates from the database.
	 * This function is used in the backend for the orderstates listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of orderstates objects
	 */
	function getItemList($search='') {
		//echo $this->getListQuery()->dump();
		$data=parent::getItems();
		return $data;
	}

	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);

		$query->select('orderstates.*')
			->from('#__tsmart_orderstates AS orderstates')
			->group('orderstates.tsmart_orderstate_id')
			//->leftJoin('#__tsmart_orderstatess AS orderstatess ON orderstatess.tsmart_orderstates_id=orderstates.tsmart_orderstates_id')

		;


		return $query;
	}

	/**
	 * Retireve a list of orderstates from the database.
	 *
	 * This is written to get a list for selecting orderstates. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of orderstates objects
	 */

	function store(&$data){
		if(!vmAccess::manager('orderstates')){
			vmWarn('Insufficient permissions to store orderstates');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('orderstates')){
			vmWarn('Insufficient permissions to remove orderstates');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
