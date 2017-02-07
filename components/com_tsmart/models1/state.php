<?php
/**
 *
 * Data module for shop state
 *
 * @package	tsmart
 * @subpackage state
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: state.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for shop state
 *
 * @package	tsmart
 * @subpackage state
 */
class tsmartModelstate extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('states');
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
	 * Retireve a list of state from the database.
	 * This function is used in the backend for the state listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of state objects
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

		$query->select('state.*, countries.flag AS country_flag,countries.country_name,COUNT(cityarea.tsmart_cityarea_id) AS total_city')
			->from('#__tsmart_states AS state')
			->leftJoin('#__tsmart_countries AS countries   using (tsmart_country_id)')
			->leftJoin('#__tsmart_cityarea AS cityarea using (tsmart_state_id)')
			->group('state.tsmart_state_id')
			->leftJoin('#__tsmart_states AS states ON states.tsmart_state_id=cityarea.tsmart_state_id')
			->leftJoin('#__tsmart_airport AS airport ON airport.tsmart_cityarea_id=cityarea.tsmart_cityarea_id')
			->select('GROUP_CONCAT(airport.airport_name,"(",airport.ata_code,")") AS list_airport_name')

		;
		$user = JFactory::getUser();
		$shared = '';
		if (vmAccess::manager()) {
			//$query->where('transferaddon.shared=1','OR');
		}
		$search=vRequest::getCmd('search', false);
		if (empty($search)) {
			$search = vRequest::getString('search', false);
		}
		// add filters
		if ($search) {
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape($search, true) . '%"';
			$query->where('state.state_name LIKE '.$search);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'state.tsmart_state_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'state.ordering')
		{
			$orderCol = $db->quoteName('state.tsmart_state_id') . ' ' . $orderDirn . ', ' . $db->quoteName('state.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Retireve a list of state from the database.
	 *
	 * This is written to get a list for selecting state. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of state objects
	 */

	function store(&$data){
		if(!vmAccess::manager('state')){
			vmWarn('Insufficient permissions to store state');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('state')){
			vmWarn('Insufficient permissions to remove state');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
