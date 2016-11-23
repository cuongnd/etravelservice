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
class tsmartModelRelation extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('relation');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem() {
		$app=JFactory::getApplication();
		$tsmart_product_id=$app->input->get('tsmart_product_id',0,'int');
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('related.*')
			->from('#__tsmart_related AS related')
			->where('related.tsmart_product_id='.(int)$tsmart_product_id)
			;
		return $db->setQuery($query)->loadObject();
	}


	/**
	 * Retireve a list of currencies from the database.
	 * This function is used in the backend for the currency listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of currency objects
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
		$query->select('relation.*')
			->from('#__tsmart_relation AS relation')
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
			$query->where('relation.title LIKE '.$search);
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
		if(!vmAccess::manager('relation')){
			vmWarn('Insufficient permissions to store relation');
			return false;
		}
		$db=JFactory::getDbo();
		$ok= parent::store($data);
		if($ok) {
			//inser to tour
			$tsmart_related_id=$data['tsmart_related_id'];
			$query = $db->getQuery(true);

			$query->delete('#__tsmart_tour_id_related_id')
				->where('tsmart_related_id=' . (int)$tsmart_related_id);

			$db->setQuery($query)->execute();

			$err = $db->getErrorMsg();

			if (!empty($err)) {

				vmError('can not delete tour in this related', $err);

			}

			$list_tsmart_product_id = $data['list_tsmart_product_id'];

			foreach ($list_tsmart_product_id as $tsmart_product_id) {

				$query->clear()
					->insert('#__tsmart_tour_id_related_id')
					->set('tsmart_related_id=' . (int)$tsmart_related_id)
					->set('tsmart_product_id=' . (int)$tsmart_product_id);

				$db->setQuery($query)->execute();

				$err = $db->getErrorMsg();

				if (!empty($err)) {

					vmError('can not insert tour in this related', $err);

				}

			}

			//end inser tour
		}
		return $ok;

	}

	function remove($ids){
		if(!vmAccess::manager('relation')){
			vmWarn('Insufficient permissions to remove relation');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag