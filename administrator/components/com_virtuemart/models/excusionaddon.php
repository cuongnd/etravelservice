<?php
/**
 *
 * Data module for shop currencies
 *
 * @package	VirtueMart
 * @subpackage Currency
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package	VirtueMart
 * @subpackage Currency
 */
class VirtueMartModelexcusionaddon extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('excusionaddon');
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
		$data=parent::getItems();
		return $data;

	}
	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('excusionaddon.*')
			->from('#__virtuemart_excusionaddon AS excusionaddon')
			->leftJoin('me1u8_virtuemart_cityarea AS cityarea USING(virtuemart_cityarea_id)')
			->select('cityarea.title AS cityarea_name')
		;
		$query1=$db->getQuery(true);
		$query1->select('GROUP_CONCAT(products_en_gb.product_name)')
			->from('#__virtuemart_tour_id_excusionaddon_id AS tour_id_excusionaddon_id')
			->leftJoin('#__virtuemart_products_en_gb AS products_en_gb USING(virtuemart_product_id)')
			->where('tour_id_excusionaddon_id.virtuemart_excusionaddon_id=excusionaddon.virtuemart_excusionaddon_id')
		;
		$query->select("($query1) AS list_tour");
		$user = JFactory::getUser();
		$shared = '';
		if (vmAccess::manager()) {
			//$query->where('excusionaddon.shared=1','OR');
		}
		$search=vRequest::getCmd('search', false);
		if (empty($search)) {
			$search = vRequest::getString('search', false);
		}
		// add filters
		if ($search) {
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape($search, true) . '%"';
			$query->where('excusionaddon.title LIKE '.$search);
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

	function store(&$data){
		if(!vmAccess::manager('excusionaddon')){
			vmWarn('Insufficient permissions to store excusionaddon');
			return false;
		}
		$db=JFactory::getDbo();
		$virtuemart_excusionaddon_id= parent::store($data);
		if($virtuemart_excusionaddon_id) {
			//inser to excusionaddon
			$query = $db->getQuery(true);
			$query->delete('#__virtuemart_tour_id_excusionaddon_id')
				->where('virtuemart_excusionaddon_id=' . (int)$virtuemart_excusionaddon_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete tour in excusionaddon', $err);
			}
			$list_tour_id = $data['list_tour_id'];
			foreach ($list_tour_id as $virtuemart_product_id) {
				$query->clear()
					->insert('#__virtuemart_tour_id_excusionaddon_id')
					->set('virtuemart_product_id=' . (int)$virtuemart_product_id)
					->set('virtuemart_excusionaddon_id=' . (int)$virtuemart_excusionaddon_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert tour in this excusionaddon', $err);
				}
			}
			//end insert group size
		}

		return $virtuemart_excusionaddon_id;
	}

	function remove($ids){
		if(!vmAccess::manager('excusionaddon')){
			vmWarn('Insufficient permissions to remove excusionaddon');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag