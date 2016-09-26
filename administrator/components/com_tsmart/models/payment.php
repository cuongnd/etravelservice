<?php
/**
 *
 * Data module for shop currencies
 *
 * @package    tsmart
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

if (!class_exists('VmModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package    tsmart
 * @subpackage Currency
 */
class tsmartModelPayment extends VmModel
{


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct()
	{
		parent::__construct();
		$this->setMainTable('payment');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem($id = 0)
	{
		return $this->getData($id);
	}


	/**
	 * Retireve a list of currencies from the database.
	 * This function is used in the backend for the currency listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of currency objects
	 */
	function getItemList($search = '')
	{
		$data=parent::getItems();
		return $data;
	}
	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('payment.*')
			->from('#__tsmart_payment AS payment')
			->leftJoin('#__tsmart_currencies AS currencies USING(tsmart_currency_id)')
			->select('CONCAT(currencies.currency_code_3," ",currencies.currency_symbol) AS currency_symbol')
		;
		//get list tour apply
		$query1=$db->getQuery(true);
		$query1->select('GROUP_CONCAT(products_en_gb.product_name)')
			->from('#__tsmart_tour_id_payment_id AS tour_id_payment_id')
			->leftJoin('#__tsmart_products_en_gb AS products_en_gb USING(tsmart_product_id)')
			->where('tour_id_payment_id.tsmart_payment_id=payment.tsmart_payment_id')
		;
		$query->select("($query1) AS list_tour");
		//end get list tour apply


		$user = JFactory::getUser();
		$shared = '';
		if (vmAccess::manager()) {
			//$query->where('payment.shared=1','OR');
		}
		$search=vRequest::getCmd('search', false);
		if (empty($search)) {
			$search = vRequest::getString('search', false);
		}
		// add filters
		if ($search) {
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape($search, true) . '%"';
			$query->where('payment.title LIKE '.$search);
		}
		if(empty($this->_selectedOrdering)) vmTrace('empty _getOrdering');
		if(empty($this->_selectedOrderingDir)) vmTrace('empty _selectedOrderingDir');
		$query->order($this->_selectedOrdering.' '.$this->_selectedOrderingDir);
		return $query;
	}

	/**
	 * Retireve a list of currencies from the database.
	 *
	 * This is written to get a list for selecting currencies. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of currency objects
	 */

	function store(&$data)
	{
		$db=JFactory::getDbo();
		if (!vmAccess::manager('payment')) {
			vmWarn('Insufficient permissions to store payment');
			return false;
		}
		$tsmart_payment_id= parent::store($data);
		if($tsmart_payment_id) {
			//insert to tour in payment
			$query = $db->getQuery(true);
			$query->delete('#__tsmart_tour_id_payment_id')
				->where('tsmart_payment_id=' . (int)$tsmart_payment_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete tour in payment', $err);
			}
			$list_tour_id = $data['list_tour_id'];
			foreach ($list_tour_id as $tsmart_product_id) {
				$query->clear()
					->insert('#__tsmart_tour_id_payment_id')
					->set('tsmart_product_id=' . (int)$tsmart_product_id)
					->set('tsmart_payment_id=' . (int)$tsmart_payment_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert tour in this payment', $err);
				}
			}
			//end insert to tour in payment

			//insert to payment method in payment
			$query = $db->getQuery(true);
			$query->delete('#__tsmart_payment_id_payment_method_id')
				->where('tsmart_payment_id=' . (int)$tsmart_payment_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete payment method in payment', $err);
			}
			$list_payment_method_id = $data['list_payment_method_id'];
			foreach ($list_payment_method_id as $tsmart_payment_method_id) {
				$query->clear()
					->insert('#__tsmart_payment_id_payment_method_id')
					->set('tsmart_payment_method_id=' . (int)$tsmart_payment_method_id)
					->set('tsmart_payment_id=' . (int)$tsmart_payment_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert payment method in this payment', $err);
				}
			}
			//end insert to payment method in payment
		}

		return $tsmart_payment_id;
	}

	function remove($ids)
	{
		if (!vmAccess::manager('payment')) {
			vmWarn('Insufficient permissions to remove payment');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag