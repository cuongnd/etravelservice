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
class tsmartModeldiscount extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('discounts');
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

		//echo $this->getListQuery()->dump();
		$items=parent::getItems();

        return $items;
	}

	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('discounts.*')
			->from('#__tsmart_discounts AS discounts')
			->leftJoin('#__tsmart_products_en_gb AS products_en_gb USING(tsmart_product_id)')
			->select('products_en_gb.product_name')
		;
		if ($search = $this->getState('filter.search')) {
			$search = '"%' . $db->escape($search, true) . '%"';
			$query->where('discounts.discount_name LIKE ' . $search);
		}
		if ($discount_code = $this->getState('filter.discount_code')) {
			$discount_code = '"%' . $db->escape($discount_code, true) . '%"';
			$query->where('discounts.discount_code LIKE ' . $discount_code);
		}
		if ($creation_from = $this->getState('filter.creation_from')) {
			$query->where('discounts.created_on >= ' . $query->q(JFactory::getDate($creation_from)->toSql()));
		}
		if ($creation_to = $this->getState('filter.creation_to')) {
			$query->where('discounts.created_on <= ' .$query->q(JFactory::getDate($creation_to)->toSql()));
		}
		$state = $this->getState('filter.state');
		if ($state!='') {
			$query->where('discounts.published = ' .$state);
		}



        if(empty($this->_selectedOrdering)) vmTrace('empty _getOrdering');
		if(empty($this->_selectedOrderingDir)) vmTrace('empty _selectedOrderingDir');
		$query->order($this->_selectedOrdering.' '.$this->_selectedOrderingDir);
		echo $query->dump();
		return $query;
	}
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$discount_code = $this->getUserStateFromRequest($this->context . '.filter.discount_code', 'filter_discount_code', '', 'String');
		$this->setState('filter.discount_code', $discount_code);

		$creation_from = $this->getUserStateFromRequest($this->context . '.filter.creation_from', 'filter_creation_from', '', 'String');
		$this->setState('filter.creation_from', $creation_from);

		$creation_to = $this->getUserStateFromRequest($this->context . '.filter.creation_to', 'filter_creation_to', '', 'String');
		$this->setState('filter.creation_to', $creation_to);

		$state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', 0, 'string');
		$this->setState('filter.state', $state);

	}



	/**
	 * Retireve a list of currencies from the database.
	 *
	 * This is written to get a list for selecting currencies. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of currency objects
	 */

	function store(&$data){

		if(!vmAccess::manager('discount')){
			vmWarn('Insufficient permissions to store discount');
			return false;
		}
		$db=JFactory::getDbo();
		$tsmart_discount_id= parent::store($data);
		if($tsmart_discount_id) {
			//inser to excusionaddon
			$query = $db->getQuery(true);
			$query->delete('#__tsmart_discount_id_departure_id')
				->where('tsmart_discount_id=' . (int)$tsmart_discount_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete departure in discount', $err);
			}
			$list_departure_date = $data['list_departure_date'];
			foreach ($list_departure_date as $tsmart_service_class_id) {
				$query->clear()
					->insert('#__tsmart_discount_id_departure_id')
					->set('tsmart_departure_id=' . (int)$tsmart_service_class_id)
					->set('tsmart_discount_id=' . (int)$tsmart_discount_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert departure in this discount', $err);
				}
			}
			$query->clear();
			$query->delete('#__tsmart_discount_id_service_class_id')
				->where('tsmart_discount_id=' . (int)$tsmart_discount_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete service class in discount', $err);
			}
			$list_service_class = $data['list_service_class'];
			foreach ($list_service_class as $tsmart_service_class_id) {
				$query->clear()
					->insert('#__tsmart_discount_id_service_class_id')
					->set('tsmart_service_class_id=' . (int)$tsmart_service_class_id)
					->set('tsmart_discount_id=' . (int)$tsmart_discount_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert service class in this discount', $err);
				}
			}

		}
		return $tsmart_discount_id;

	}

	function remove($ids){
		if(!vmAccess::manager('discount')){
			vmWarn('Insufficient permissions to remove discount');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag