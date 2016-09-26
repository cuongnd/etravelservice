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

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

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
		$items=parent::getItems();
        $list_virtuemart_product_id=array();
        $db=$this->_db;
        foreach($items as &$item)
        {
            $list_virtuemart_product_id=$item->list_virtuemart_product_id;
            $list_virtuemart_product_id=explode(',',$list_virtuemart_product_id);
            $virtuemart_hotel_id=$item->virtuemart_hotel_id;
            $virtuemart_hotel_addon_id=$item->virtuemart_hotel_addon_id;

            $list_tour_tour_class=array();
            foreach($list_virtuemart_product_id AS $virtuemart_product_id) {
                $query = $db->getQuery(true);
                $query->select('CONCAT("<a href=index.php?option=com_tsmart&view=product&task=edit&virtuemart_product_id=",products_en_gb.virtuemart_product_id,">",products_en_gb.product_name," </a>","(",GROUP_CONCAT(DISTINCT(service_class.service_class_name) SEPARATOR ","),"."," ",")") AS tour_tour_class')
                    ->from('#__virtuemart_hotel_id_service_class_id_accommodation_id  AS hotel_id_service_class_id_accommodation_id')
                    ->leftJoin('#__virtuemart_tour_id_service_class_id AS tour_id_service_class_id USING(virtuemart_service_class_id)')
                    ->leftJoin('#__virtuemart_service_class AS service_class ON service_class.virtuemart_service_class_id=tour_id_service_class_id.virtuemart_service_class_id')
                    ->where('hotel_id_service_class_id_accommodation_id.virtuemart_hotel_id='.(int)$virtuemart_hotel_id)
                    ->leftJoin('#__virtuemart_hotel_addon AS hotel_addon ON hotel_addon.virtuemart_hotel_id=hotel_id_service_class_id_accommodation_id.virtuemart_hotel_id')
                    ->where('hotel_addon.virtuemart_hotel_addon_id='.(int)$virtuemart_hotel_addon_id)
                    ->leftJoin('#__virtuemart_tour_id_hotel_addon_id AS tour_id_hotel_addon_id ON tour_id_hotel_addon_id.virtuemart_hotel_addon_id=hotel_addon.virtuemart_hotel_addon_id')
                    ->where('tour_id_hotel_addon_id.virtuemart_product_id='.(int)$virtuemart_product_id)
                    ->leftJoin('#__virtuemart_products AS products ON products.virtuemart_product_id=tour_id_hotel_addon_id.virtuemart_product_id')
                    ->innerJoin('#__virtuemart_products_en_gb AS products_en_gb ON products_en_gb.virtuemart_product_id=products.virtuemart_product_id')
                ;
                $db->setQuery($query);
                $list_tour_tour_class[]=$db->loadResult();

            }
            $item->tour_tour_class=implode(' ',$list_tour_tour_class);
        }

        if ($virtuemart_product_id = $this->getState('filter.virtuemart_product_id'))
        {
            foreach($items as $key=>&$item)
            {
                $list_virtuemart_product_id=$item->list_virtuemart_product_id;
                $list_virtuemart_product_id=explode(',',$list_virtuemart_product_id);
                if(!in_array($virtuemart_product_id,$list_virtuemart_product_id))
                {
                    unset($items[$key]);
                }
            }

        }

        return $items;
	}

	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);

		$query1=$db->getQuery(true);
		$query1->select('GROUP_CONCAT(products_en_gb.virtuemart_product_id)')
			->from('#__virtuemart_products_en_gb AS products_en_gb')
			->leftJoin('#__virtuemart_products AS products  USING(virtuemart_product_id)')
			->leftJoin('#__virtuemart_tour_id_hotel_addon_id AS tour_id_hotel_addon_id1 USING(virtuemart_product_id)')
			->where('tour_id_hotel_addon_id1.virtuemart_hotel_addon_id=hotel_addon.virtuemart_hotel_addon_id')
		;

		$query->select('hotel_addon.*,hotel.hotel_name,cityarea.city_area_name,cityarea.virtuemart_cityarea_id')
			->from('#__virtuemart_hotel_addon AS hotel_addon')
			->leftJoin('#__virtuemart_hotel AS hotel USING(virtuemart_hotel_id)')
			->leftJoin('#__virtuemart_cityarea AS cityarea ON cityarea.virtuemart_cityarea_id=hotel.virtuemart_cityarea_id')
			->select('('.$query1.') AS list_virtuemart_product_id')
		;
		$user = JFactory::getUser();
		$shared = '';
        if ($search = $this->getState('filter.search'))
        {
            $search = '"%' . $db->escape($search, true) . '%"';
            $query->where('(hotel_addon.virtuemart_hotel_addon_id LIKE '.$search.' OR  hotel.hotel_name LIKE '.$search.')');
        }
        if ($location_city = $this->getState('filter.location_city'))
        {
            $query->where('cityarea.virtuemart_cityarea_id='.(int)$location_city);
        }
        if ($vail_from = $this->getState('filter.vail_from'))
        {
            $vail_from=JFactory::getDate($vail_from);
            $query->where('hotel_addon.vail_from >='.$query->q($vail_from->toSql()));
        }
        if ($vail_to = $this->getState('filter.vail_to'))
        {
            $vail_to=JFactory::getDate($vail_to);
            $query->where('hotel_addon.vail_to<='.$query->q($vail_to->toSql()));
        }
        // Filter by published state
        $state = $this->getState('filter.state');

        if (is_numeric($state))
        {
            $query->where('hotel_addon.published = ' . (int) $state);
        }
        elseif ($state === '')
        {
            $query->where('(hotel_addon.published IN (0, 1))');
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

        $location_city = $this->getUserStateFromRequest($this->context . '.filter.location_city', 'filter_location_city', '', 'int');
        $this->setState('filter.location_city', $location_city);


        $virtuemart_product_id = $this->getUserStateFromRequest($this->context . '.filter.virtuemart_product_id', 'filter_virtuemart_product_id', '', 'int');
        $this->setState('filter.virtuemart_product_id', $virtuemart_product_id);

        $vail_from = $this->getUserStateFromRequest($this->context . '.filter.vail_from', 'filter_vail_from', '', 'String');
        $this->setState('filter.vail_from', $vail_from);

        $vail_to = $this->getUserStateFromRequest($this->context . '.filter.vail_to', 'filter_vail_to', '', 'String');
        $this->setState('filter.vail_to', $vail_to);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
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
			$data_price=$data['data_price'];
			$data_price=base64_decode($data_price);
			require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
			$data_price = up_json_decode($data_price, false, 512, JSON_PARSE_JAVASCRIPT);
			$item_mark_up_type=$data_price->item_mark_up_type;
			foreach($list_tour_id as $tour_id)
			{
                $vail_from=JFactory::getDate($data['vail_from']);
                $vail_to=JFactory::getDate($data['vail_to']);
				$single_room=$data_price->items->single_room;
				$double_twin_room=$data_price->items->double_twin_room;
				$triple_room=$data_price->items->triple_room;

				while ($vail_from->getTimestamp() <= $vail_to->getTimestamp()) {

					$date=$vail_from->format('Y-m-d');
					$hotel_addon_date_price_table->id=0;
					$hotel_addon_date_price_table->jload(array('date'=>$date,'virtuemart_product_id'=>$tour_id,'hotel_addon_type'=>$hotel_addon_type));
					$hotel_addon_date_price_table->date=$date;
					$hotel_addon_date_price_table->virtuemart_hotel_addon_id=$virtuemart_hotel_addon_id;
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