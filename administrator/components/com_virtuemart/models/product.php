<?php
/**
 *
 * Data module for shop product
 *
 * @package	VirtueMart
 * @subpackage product
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop product
 *
 * @package	VirtueMart
 * @subpackage product
 */
class VirtueMartModelproduct extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('products');
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
	 * Retireve a list of product from the database.
	 * This function is used in the backend for the product listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of product objects
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

		$query->select('product.*')
			->from('#__virtuemart_products AS product')
			//->leftJoin('#__virtuemart_cityarea AS cityarea using (virtuemart_cityarea_id)')
			//->leftJoin('#__virtuemart_states AS states ON states.virtuemart_state_id=cityarea.virtuemart_state_id')
			//->leftJoin('#__virtuemart_countries AS countries ON countries.virtuemart_country_id=states.virtuemart_country_id')
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
			$query->where('product.product_name LIKE '.$search);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'product.virtuemart_product_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'product.ordering')
		{
			$orderCol = $db->quoteName('product.virtuemart_product_id') . ' ' . $orderDirn . ', ' . $db->quoteName('product.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		return $query;
	}

	/**
	 * Retireve a list of product from the database.
	 *
	 * This is written to get a list for selecting product. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of product objects
	 */

	function store(&$data){
		if(!vmAccess::manager('product')){
			vmWarn('Insufficient permissions to store product');
			return false;
		}
		$virtuemart_product_id= parent::store($data);
		$db=JFactory::getDbo();
		//inser to activity
		$query=$db->getQuery(true);
		$query->delete('#__virtuemart_tour_id_activity_id')
			->where('virtuemart_product_id='.(int)$virtuemart_product_id)
		;
		$db->setQuery($query)->execute();
		$err = $db->getErrorMsg();
		if(!empty($err)){
			vmError('can not delete activity in this tour',$err);
		}
		$list_activity_id=$data['list_activity_id'];
		foreach($list_activity_id as $virtuemart_activity_id)
		{
			$query->clear()
				->insert('#__virtuemart_tour_id_activity_id')
				->set('virtuemart_product_id='.(int)$virtuemart_product_id)
				->set('virtuemart_activity_id='.(int)$virtuemart_activity_id)
			;
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if(!empty($err)){
				vmError('can not insert activity in this tour',$err);
			}
		}
		//end inser tour type

		//inser to countries
		$query=$db->getQuery(true);
		$query->delete('#__virtuemart_tour_id_country_id')
			->where('virtuemart_product_id='.(int)$virtuemart_product_id)
		;
		$db->setQuery($query)->execute();
		$err = $db->getErrorMsg();
		if(!empty($err)){
			vmError('can not delete country in this tour',$err);
		}
		$list_virtuemart_country_id=$data['list_virtuemart_country_id'];
		foreach($list_virtuemart_country_id as $virtuemart_country_id)
		{
			$query->clear()
				->insert('#__virtuemart_tour_id_country_id')
				->set('virtuemart_product_id='.(int)$virtuemart_product_id)
				->set('virtuemart_country_id='.(int)$virtuemart_country_id)
			;
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if(!empty($err)){
				vmError('can not insert country in this tour',$err);
			}
		}
		return $virtuemart_country_id;
	}

	function remove($ids){
		if(!vmAccess::manager('product')){
			vmWarn('Insufficient permissions to remove product');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
