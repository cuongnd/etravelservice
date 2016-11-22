<?php
/**
 *
 * Data module for shop room
 *
 * @package	tsmart
 * @subpackage room
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: room.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for shop room
 *
 * @package	tsmart
 * @subpackage room
 */
class tsmartModelroom extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('room');
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
	 * Retireve a list of room from the database.
	 * This function is used in the backend for the room listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of room objects
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
		$input=JFactory::getApplication()->input;
		$tsmart_hotel_id=$input->getInt('tsmart_hotel_id',0);
		$query->select('room.*,hotel.tsmart_hotel_id,hotel.hotel_name AS hotel_name')
			->from('#__tsmart_room AS room')
			->leftJoin('#__tsmart_hotel AS hotel using (tsmart_hotel_id)')

		;
		if($tsmart_hotel_id)
		{
			$query->where('room.tsmart_hotel_id='.(int)$tsmart_hotel_id);
		}
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
			$query->where('room.room_name LIKE '.$search);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'room.tsmart_room_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'room.ordering')
		{
			$orderCol = $db->quoteName('room.tsmart_room_id') . ' ' . $orderDirn . ', ' . $db->quoteName('room.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		echo $query->dump();
		return $query;
	}

	/**
	 * Retireve a list of room from the database.
	 *
	 * This is written to get a list for selecting room. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of room objects
	 */

	function store(&$data){
		if(!vmAccess::manager('room')){
			vmWarn('Insufficient permissions to store room');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('room')){
			vmWarn('Insufficient permissions to remove room');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
