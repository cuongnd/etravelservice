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
class VirtueMartModelhoteladdon extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('hotel_addon');
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
		$data=parent::getItems();
		return $data;
	}

	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query1=$db->getQuery(true);
		$query1->select('GROUP_CONCAT(products_en_gb.product_name)')
			->from('#__virtuemart_products_en_gb AS products_en_gb')
			->leftJoin('#__virtuemart_products AS products  USING(virtuemart_product_id)')
			->leftJoin('#__virtuemart_tour_id_hotel_addon_id AS tour_id_hotel_addon_id1 USING(virtuemart_product_id)')
			->where('tour_id_hotel_addon_id1.virtuemart_hotel_addon_id=hotel_addon.virtuemart_hotel_addon_id')
		;

		$query->select('hotel_addon.*,hotel.hotel_name,cityarea.city_area_name')
			->from('#__virtuemart_hotel_addon AS hotel_addon')
			->leftJoin('#__virtuemart_hotel AS hotel USING(virtuemart_hotel_id)')
			->leftJoin('#__virtuemart_cityarea AS cityarea ON cityarea.virtuemart_cityarea_id=hotel.virtuemart_cityarea_id')
			->select('('.$query1.') AS tours')
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
			$query->where('hotel_addon.hotel_addon_name LIKE '.$search);
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
		if(!vmAccess::manager('hotel')){
			vmWarn('Insufficient permissions to store hotel');
			return false;
		}
		$db=JFactory::getDbo();
		$virtuemart_hotel_addon_id= parent::store($data);
		if($virtuemart_hotel_addon_id) {
			//inser to excusionaddon
			$query = $db->getQuery(true);
			$query->delete('#__virtuemart_tour_id_hotel_addon_id')
				->where('virtuemart_hotel_addon_id=' . (int)$virtuemart_hotel_addon_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete tour in hotel_addon', $err);
			}
			$list_tour_id = $data['list_tour_id'];
			foreach ($list_tour_id as $virtuemart_product_id) {
				$query->clear()
					->insert('#__virtuemart_tour_id_hotel_addon_id')
					->set('virtuemart_product_id=' . (int)$virtuemart_product_id)
					->set('virtuemart_hotel_addon_id=' . (int)$virtuemart_hotel_addon_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert tour in this hotel_addon', $err);
				}
			}
			$hotel_addon_type=$data['hotel_addon_type'];
			$hotel_addon_date_price_table=$this->getTable('hotel_addon_date_price');
			//end insert group size
			$vail_from=$data['vail_from'];
			$vail_from=JFactory::getDate($vail_from);
			$vail_to=$data['vail_to'];
			$vail_to=JFactory::getDate($vail_to);
			$data_price=$data['data_price'];
			$data_price=base64_decode($data_price);
			require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
			$data_price = up_json_decode($data_price, false, 512, JSON_PARSE_JAVASCRIPT);
			$item_mark_up_type=$data_price->item_mark_up_type;
			foreach($list_tour_id as $tour_id)
			{

				$single_room=$data_price->items->single_room;
				$double_twin_room=$data_price->items->double_twin_room;
				$triple_room=$data_price->items->triple_room;

				while ($vail_from->getTimestamp() <= $vail_to->getTimestamp()) {

					$date=$vail_from->format('Y-m-d');
					$hotel_addon_date_price_table->id=0;
					$hotel_addon_date_price_table->jload(array('date'=>$date,'virtuemart_product_id'=>$tour_id,'hotel_addon_type'=>$hotel_addon_type));
					$hotel_addon_date_price_table->date=$date;
					$hotel_addon_date_price_table->virtuemart_product_id=$tour_id;
					$hotel_addon_date_price_table->hotel_addon_type=$hotel_addon_type;
					$hotel_addon_date_price_table->single_room_net_price=$single_room->net_price;
					$hotel_addon_date_price_table->doulble_twin_room_net_price=$double_twin_room->net_price;
					$hotel_addon_date_price_table->triple_room_net_price=$triple_room->net_price;

					//tax
					$hotel_addon_date_price_table->single_room_tax=$single_room->tax;
					$hotel_addon_date_price_table->doulble_twin_room_tax=$double_twin_room->tax;
					$hotel_addon_date_price_table->triple_room_tax=$triple_room->tax;
					if($item_mark_up_type=='percent')
					{
						$hotel_addon_date_price_table->single_room_mark_up_percent=$single_room->mark_up_percent;
						$hotel_addon_date_price_table->doulble_twin_room_mark_up_percent=$double_twin_room->mark_up_percent;
						$hotel_addon_date_price_table->triple_room_mark_up_percent=$triple_room->mark_up_percent;
					}else{
						$hotel_addon_date_price_table->single_room_mark_up_amout=$single_room->mark_up_amount;
						$hotel_addon_date_price_table->doulble_twin_room_mark_up_amount=$double_twin_room->mark_up_amount;
						$hotel_addon_date_price_table->triple_room_mark_up_amout=$triple_room->mark_up_amount;
					}
					$ok=$hotel_addon_date_price_table->store();
					if(!$ok)
					{
						throw new  Exception($hotel_addon_date_price_table->getError());
					}
					$vail_from->modify('+1 day');

				}

			}


		}
		return $virtuemart_hotel_addon_id;

	}

	function remove($ids){
		if(!vmAccess::manager('hotel')){
			vmWarn('Insufficient permissions to remove hotel');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag