<?php
/**
 *
 * Data module for shop airport
 *
 * @package	VirtueMart
 * @subpackage airport
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: airport.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop airport
 *
 * @package	VirtueMart
 * @subpackage airport
 */
class VirtueMartModelItinerary extends VmModel {


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
		$virtuemart_product_id=$input->getInt('virtuemart_product_id',0);
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);

		$query->select('itinerary.*,cityarea.city_area_name')
			->from('#__virtuemart_itinerary AS itinerary')
			->where('itinerary.virtuemart_product_id='.(int)$virtuemart_product_id)
            ->leftJoin('#__virtuemart_cityarea AS cityarea USING(virtuemart_cityarea_id)')
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
		$orderCol = $this->state->get('list.ordering', 'itinerary.virtuemart_itinerary_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'airport.ordering')
		{
			$orderCol = $db->quoteName('itinerary.virtuemart_itinerary_id') . ' ' . $orderDirn . ', ' . $db->quoteName('itinerary.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

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
		if(!vmAccess::manager('airport')){
			vmWarn('Insufficient permissions to store airport');
			return false;
		}
		return parent::store($data);
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
