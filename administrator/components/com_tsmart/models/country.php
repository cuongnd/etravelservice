<?php
/**
 *
 * Data module for shop country
 *
 * @package	VirtueMart
 * @subpackage country
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: country.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop country
 *
 * @package	VirtueMart
 * @subpackage country
 */
class VirtueMartModelcountry extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('countries');
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
	 * Retireve a list of country from the database.
	 * This function is used in the backend for the country listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of country objects
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

		$query->select('country.*,COUNT(states.virtuemart_state_id) AS total_state')
			->from('#__virtuemart_countries AS country')
			->leftJoin('#__virtuemart_states AS states using (virtuemart_country_id)')
			->group('country.virtuemart_country_id')
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
			$query->where('country.country_name LIKE '.$search);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'country.virtuemart_country_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'country.ordering')
		{
			$orderCol = $db->quoteName('country.virtuemart_country_id') . ' ' . $orderDirn . ', ' . $db->quoteName('country.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Retireve a list of country from the database.
	 *
	 * This is written to get a list for selecting country. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of country objects
	 */

	function store(&$data){
		if(!vmAccess::manager('country')){
			vmWarn('Insufficient permissions to store country');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('country')){
			vmWarn('Insufficient permissions to remove country');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag