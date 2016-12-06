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
class tsmartModelAccommodation extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('accommodation');
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
		$app=JFactory::getApplication();
		$input=$app->input;
		$tsmart_product_id=$input->getInt('tsmart_product_id',0);
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('itinerary.*,cityarea.city_area_name,accommodation.tsmart_accommodation_id')
			->from('#__tsmart_itinerary AS itinerary')
			->where('itinerary.tsmart_product_id='.(int)$tsmart_product_id)
			->leftJoin('#__tsmart_accommodation AS accommodation USING(tsmart_itinerary_id)')
			->leftJoin('#__tsmart_cityarea AS cityarea ON cityarea.tsmart_cityarea_id=itinerary.tsmart_cityarea_id')
			->where('itinerary.tsmart_cityarea_id is not null')
			->group('itinerary.tsmart_cityarea_id')
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
			$query->where('itinerary.title LIKE '.$search);
		}
		$orderCol = $this->state->get('list.ordering', 'itinerary.ordering');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'itinerary.ordering')
		{
			$orderCol = $db->quoteName('itinerary.title') . ' ' . $orderDirn . ', ' . $db->quoteName('itinerary.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		echo $query->dump();
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

		if(!vmAccess::manager('accommodation')){
			vmWarn('Insufficient permissions to store accommodation');
			return false;
		}
		$tsmart_accommodation_id= parent::store($data);
		if($tsmart_accommodation_id)
		{
			$list_hotel_service_class=$data['list_hotel_service_class'];

			foreach($list_hotel_service_class as $tsmart_service_class_id=>$list_hotel){
                $query=$this->_db->getQuery(true);
                $query->delete('#__tsmart_hotel_id_service_class_id_accommodation_id')
                    ->where('tsmart_service_class_id='.(int)$tsmart_service_class_id)
                    ->where('tsmart_accommodation_id='.(int)$tsmart_accommodation_id)
                    ;
                $this->_db->setQuery($query);
                $ok=$this->_db->execute();
                if(!$ok)
                {
                    throw new Exception($this->_db->getErrorMsg());
                }
				foreach($list_hotel as $key=> $tsmart_hotel_id){
                    $tsmart_service_class_id=$tsmart_service_class_id?$tsmart_service_class_id:0;
                    $tsmart_hotel_id=$tsmart_hotel_id?$tsmart_hotel_id:0;
					$table_hotel_id_service_class_id_accommodation_id=$this->getTable('hotel_id_service_class_id_accommodation_id');
					$table_hotel_id_service_class_id_accommodation_id->tsmart_service_class_id=$tsmart_service_class_id;
					$table_hotel_id_service_class_id_accommodation_id->tsmart_hotel_id=$tsmart_hotel_id;
					$table_hotel_id_service_class_id_accommodation_id->tsmart_accommodation_id=$tsmart_accommodation_id;
                    if($tsmart_hotel_id&&$tsmart_service_class_id&&$tsmart_accommodation_id)
                    {
                        $table_hotel_id_service_class_id_accommodation_id->store(true);
                    }
					$errors=$table_hotel_id_service_class_id_accommodation_id->getErrors();
					if(count($errors))
					{
						throw new Exception($table_hotel_id_service_class_id_accommodation_id->getError());
					}

				}

			}

			$list_room_service_class=$data['list_room_service_class'];

			foreach($list_room_service_class as $tsmart_service_class_id=>$list_room){
                $query=$this->_db->getQuery(true);
                $query->delete('#__tsmart_room_id_service_class_id_accommodation_id')
                    ->where('tsmart_service_class_id='.(int)$tsmart_service_class_id)
                    ->where('tsmart_accommodation_id='.(int)$tsmart_accommodation_id)
                    ;
                $this->_db->setQuery($query);
                $ok=$this->_db->execute();
                if(!$ok)
                {
                    throw new Exception($this->_db->getErrorMsg());
                }
				foreach($list_room as $key=> $tsmart_room_id){
                    $tsmart_service_class_id=$tsmart_service_class_id?$tsmart_service_class_id:0;
                    $tsmart_room_id=$tsmart_room_id?$tsmart_room_id:0;
					$table_room_id_service_class_id_accommodation_id=$this->getTable('room_id_service_class_id_accommodation_id');
					$table_room_id_service_class_id_accommodation_id->tsmart_service_class_id=$tsmart_service_class_id;
					$table_room_id_service_class_id_accommodation_id->tsmart_room_id=$tsmart_room_id;
					$table_room_id_service_class_id_accommodation_id->tsmart_accommodation_id=$tsmart_accommodation_id;
                    if($tsmart_room_id&&$tsmart_service_class_id&&$tsmart_accommodation_id)
                    {
                        $table_room_id_service_class_id_accommodation_id->store(true);
                    }
					$errors=$table_room_id_service_class_id_accommodation_id->getErrors();
					if(count($errors))
					{
						throw new Exception($table_room_id_service_class_id_accommodation_id->getError());
					}

				}

			}
		}
	}

	function remove($ids){
		if(!vmAccess::manager('accommodation')){
			vmWarn('Insufficient permissions to remove accommodation');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag