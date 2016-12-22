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
class tsmartModelcoupon extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('coupons');
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
		$query->select('coupons.*')
			->from('#__tsmart_coupons AS coupons')
			->leftJoin('#__tsmart_products_en_gb AS products_en_gb USING(tsmart_product_id)')
			->select('products_en_gb.product_name')
		;
		if ($search = $this->getState('filter.search')) {
			$search = '"%' . $db->escape($search, true) . '%"';
			$query->where('coupons.coupon_name LIKE ' . $search);
		}
		if ($coupon_code = $this->getState('filter.coupon_code')) {
			$coupon_code = '"%' . $db->escape($coupon_code, true) . '%"';
			$query->where('coupons.coupon_code LIKE ' . $coupon_code);
		}
		if ($creation_from = $this->getState('filter.creation_from')) {
			$query->where('coupons.created_on >= ' . $query->q(JFactory::getDate($creation_from)->toSql()));
		}
		if ($creation_to = $this->getState('filter.creation_to')) {
			$query->where('coupons.created_on <= ' .$query->q(JFactory::getDate($creation_to)->toSql()));
		}
		$state = $this->getState('filter.state');
		if ($state!='') {
			$query->where('coupons.published = ' .$state);
		}



        if(empty($this->_selectedOrdering)) vmTrace('empty _getOrdering');
		if(empty($this->_selectedOrderingDir)) vmTrace('empty _selectedOrderingDir');
		$query->order($this->_selectedOrdering.' '.$this->_selectedOrderingDir);
		return $query;
	}
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$coupon_code = $this->getUserStateFromRequest($this->context . '.filter.coupon_code', 'filter_coupon_code', '', 'String');
		$this->setState('filter.coupon_code', $coupon_code);

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

		if(!vmAccess::manager('coupon')){
			vmWarn('Insufficient permissions to store coupon');
			return false;
		}
		$db=JFactory::getDbo();
		$tsmart_coupon_id= parent::store($data);
		if($tsmart_coupon_id) {
			//inser to excusionaddon
			$query = $db->getQuery(true);
			$query->delete('#__tsmart_coupon_id_departure_id')
				->where('tsmart_coupon_id=' . (int)$tsmart_coupon_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete departure in coupon', $err);
			}
			$list_departure_date = $data['list_departure_date'];
			foreach ($list_departure_date as $tsmart_service_class_id) {
				$query->clear()
					->insert('#__tsmart_coupon_id_departure_id')
					->set('tsmart_departure_id=' . (int)$tsmart_service_class_id)
					->set('tsmart_coupon_id=' . (int)$tsmart_coupon_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert departure in this coupon', $err);
				}
			}
			$query->clear();
			$query->delete('#__tsmart_coupon_id_service_class_id')
				->where('tsmart_coupon_id=' . (int)$tsmart_coupon_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete service class in coupon', $err);
			}
			$list_service_class = $data['list_service_class'];
			foreach ($list_service_class as $tsmart_service_class_id) {
				$query->clear()
					->insert('#__tsmart_coupon_id_service_class_id')
					->set('tsmart_service_class_id=' . (int)$tsmart_service_class_id)
					->set('tsmart_coupon_id=' . (int)$tsmart_coupon_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert service class in this coupon', $err);
				}
			}

		}
		return $tsmart_coupon_id;

	}

	function remove($ids){
		if(!vmAccess::manager('coupon')){
			vmWarn('Insufficient permissions to remove coupon');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag