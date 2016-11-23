<?php
/**
 *
 * Data module for shop airport
 *
 * @package	tsmart
 * @subpackage airport
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: airport.php 8970 2015-09-06 23:19:17Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');
/**
 * Model class for shop airport
 *
 * @package	tsmart
 * @subpackage airport
 */
class tsmartModelItinerary extends tmsModel {
	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('itinerary');
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
	 * Retireve a list of airport from the database.
	 * This function is used in the backend for the airport listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of airport objects
	 */
	function getItemList($search='') {
		echo $this->getListQuery()->dump();
		$data=parent::getItems();
		return $data;
	}
	function getListQuery()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$tsmart_product_id=$input->getInt('tsmart_product_id',0);
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('itinerary.*,cityarea.city_area_name')
			->from('#__tsmart_itinerary AS itinerary')
			->where('itinerary.tsmart_product_id='.(int)$tsmart_product_id)
			->leftJoin('#__tsmart_cityarea AS cityarea USING(tsmart_cityarea_id)')
			->leftJoin('#__tsmart_itinerary_id_meal_id AS itinerary_id_meal_id ON itinerary_id_meal_id.tsmart_itinerary_id= itinerary.tsmart_itinerary_id')
			->leftJoin('#__tsmart_meal AS meal ON meal.tsmart_meal_id= itinerary_id_meal_id.tsmart_meal_id')
			->select('(SELECT GROUP_CONCAT(meal.meal_name)
             FROM #__tsmart_meal AS meal LEFT JOIN #__tsmart_itinerary_id_meal_id AS itinerary_id_meal_id ON itinerary_id_meal_id.tsmart_meal_id = meal.tsmart_meal_id
            WHERE itinerary_id_meal_id.tsmart_itinerary_id = itinerary.tsmart_itinerary_id) AS list_meal')
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
			$query->where('airport.airport_name LIKE '.$search);
		}
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'itinerary.tsmart_itinerary_id');
		$orderDirn = $this->state->get('list.direction', 'asc');
		if ($orderCol == 'airport.ordering')
		{
			$orderCol = $db->quoteName('itinerary.tsmart_itinerary_id') . ' ' . $orderDirn . ', ' . $db->quoteName('itinerary.ordering');
		}
		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		$query->group('itinerary.tsmart_itinerary_id');
		return $query;
	}
	/**
	 * Retireve a list of airport from the database.
	 *
	 * This is written to get a list for selecting airport. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of airport objects
	 */
	function store(&$data){
		if(!vmAccess::manager('itinerary')){
			vmWarn('Insufficient permissions to store itinerary');
			return false;
		}
		$ok= parent::store($data);
		$tsmart_itinerary_id=$data['tsmart_itinerary_id'];
		if($ok)
		{
			$db=JFactory::getDbo();
			//inser to meal
			$query = $db->getQuery(true);
			$query->delete('#__tsmart_itinerary_id_meal_id')
				->where('tsmart_itinerary_id=' . (int)$tsmart_itinerary_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete meal  in this itinerary', $err);
			}
			$list_meal_id = $data['list_meal_id'];
			foreach ($list_meal_id as $tsmart_meal_id) {
				$query->clear()
					->insert('#__tsmart_itinerary_id_meal_id')
					->set('tsmart_itinerary_id=' . (int)$tsmart_itinerary_id)
					->set('tsmart_meal_id=' . (int)$tsmart_meal_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert meal in this itinerary', $err);
				}
			}

			//inser to activity
			$query = $db->getQuery(true);
			$query->delete('#__tsmart_itinerary_id_activity_id')
				->where('tsmart_itinerary_id=' . (int)$tsmart_itinerary_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete activity  in this itinerary', $err);
			}
			$list_activity_id = $data['list_activity_id'];
			foreach ($list_activity_id as $tsmart_activity_id) {
				$query->clear()
					->insert('#__tsmart_itinerary_id_activity_id')
					->set('tsmart_itinerary_id=' . (int)$tsmart_itinerary_id)
					->set('tsmart_activity_id=' . (int)$tsmart_activity_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert activity in this itinerary', $err);
				}
			}
		}
		return $ok;
	}
	function remove($ids){
		if(!vmAccess::manager('airport')){
			vmWarn('Insufficient permissions to remove airport');
			return false;
		}
		return parent::remove($ids);
	}
}
// pure php no closing tag