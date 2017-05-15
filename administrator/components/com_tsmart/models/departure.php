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
if (!class_exists('tmsModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');
/**
 * Model class for shop Currencies
 *
 * @package    tsmart
 * @subpackage Currency
 */
class tsmartModelDeparture extends tmsModel
{
    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('departure');
    }
    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
    function getdeparture($departure_id = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('departure.*')
            ->from('#__tsmart_departure AS departure')
            ->leftJoin('#__tsmart_products AS products ON products.tsmart_product_id=departure.tsmart_product_id')
            ->leftJoin('#__tsmart_products_en_gb AS products_en_gb ON products_en_gb.tsmart_product_id=departure.tsmart_product_id')
            ->select('products_en_gb.product_name')
            ->select('products.product_code')
            ->select('products.tour_operator')
            ->leftJoin('#__tsmart_tour_style AS tour_style ON tour_style.tsmart_tour_style_id=products.tsmart_tour_style_id')
            ->select('tour_style.tour_style_name')
            ->leftJoin('#__tsmart_cityarea AS start_cityarea ON start_cityarea.tsmart_cityarea_id=products.start_city')
            ->select('start_cityarea.city_area_name AS start_city_name')
            ->leftJoin('#__tsmart_cityarea AS end_cityarea ON end_cityarea.tsmart_cityarea_id=products.start_city')
            ->select('end_cityarea.city_area_name AS end_city_name')
            ->leftJoin('#__tsmart_physicalgrade AS physicalgrade ON physicalgrade.tsmart_physicalgrade_id=products.tsmart_physicalgrade_id')
            ->select('physicalgrade.physicalgrade_name')
            ->leftJoin('#__tsmart_service_class AS service_class ON service_class.tsmart_service_class_id=departure.tsmart_service_class_id')
            ->select('service_class.service_class_name')
            ->leftJoin('#__tsmart_tour_id_payment_id AS tour_id_payment_id ON tour_id_payment_id.tsmart_product_id=departure.tsmart_product_id')
            ->leftJoin('#__tsmart_payment AS payment ON payment.tsmart_payment_id=tour_id_payment_id.tsmart_payment_id')
            ->select('payment.*')
            ->leftJoin('#__tsmart_tour_price AS tour_price ON departure.departure_date>= tour_price.sale_period_from AND departure.departure_date<=tour_price.sale_period_to AND tour_price.tsmart_product_id=departure.tsmart_product_id AND tour_price.tsmart_service_class_id=departure.tsmart_service_class_id')
            ->select('tour_price.tax')
            ->leftJoin('#__tsmart_mark_up_tour_price_id AS mark_up_tour_price_id ON  mark_up_tour_price_id.tsmart_price_id=tour_price.tsmart_price_id')
            ->select('
                    mark_up_tour_price_id.price_senior AS price_senior,
                    mark_up_tour_price_id.senior AS mark_up_senior,


                    mark_up_tour_price_id.price_adult AS price_adult,
                    mark_up_tour_price_id.adult AS mark_up_adult,


                    mark_up_tour_price_id.price_teen AS price_teen,
                    mark_up_tour_price_id.teen AS mark_up_teen,


                    mark_up_tour_price_id.price_children1 AS price_children1,
                    mark_up_tour_price_id.children1 AS mark_up_children1,


                    mark_up_tour_price_id.price_children2 AS price_children2,
                    mark_up_tour_price_id.children2 AS mark_up_children2,


                    mark_up_tour_price_id.price_infant AS price_infant,
                    mark_up_tour_price_id.infant AS mark_up_infant,



                    mark_up_tour_price_id.price_private_room AS price_private_room,
                    mark_up_tour_price_id.private_room AS mark_up_private_room,



                    mark_up_tour_price_id.price_extra_bed AS price_extra_bed,
                    mark_up_tour_price_id.extra_bed AS mark_up_extra_bed,




                    mark_up_tour_price_id.type AS mark_up_type
            ')
            ->where('departure.tsmart_departure_id=' . (int)$departure_id);
        $db->setQuery($query);
        return $db->loadObject();
    }
    /**
     * Retireve a list of currencies from the database.
     * This function is used in the backend for the currency listing, therefore no asking if enabled or not
     * @author Max Milbers
     * @return object List of currency objects
     */
    public function populateState($ordering = null, $direction = null)
    {
        $filter_product_id = $this->getUserStateFromRequest($this->context . '.filter.filter_product_id', 'filter_product_id', 0, 'int');
        $this->setState('filter.product_id', $filter_product_id);
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $departure_code = $this->getUserStateFromRequest($this->context . '.filter.departure_code', 'filter_departure_code', '', 'string');
        $this->setState('filter.departure_code', $departure_code);


        $trip_style_id = $this->getUserStateFromRequest($this->context . '.filter.trip_style_id', 'filter_trip_style_id', '', 'int');
        $this->setState('filter.trip_style_id', $trip_style_id);

        parent::populateState($ordering, $direction); // TODO: Change the autogenerated stub
    }
    function getItemList($search = '')
    {
        //echo $this->getListQuery()->dump();
        $items = parent::getItems();
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmprice.php';
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmpromotion.php';
        foreach ($items as &$item) {
            $item->sale_price_adult = tsmprice::get_sale_price_by_mark_up_and_tax($item->price_adult, $item->mark_up_adult, $item->mark_up_price_adult, $item->tax, $item->mark_up_type);
            $item->sale_promotion_price_adult = vmpromotion::get_sale_promotion_price_by_mark_up_and_tax(
                $item->promotion_price_adult,
                $item->mark_up_promotion_adult, $item->mark_up_promotion_price_adult, $item->mark_up_promotion_type,
                $item->mark_up_promotion_net_price_adult, $item->mark_up_promotion_net_adult, $item->mark_up_promotion_net_type,
                $item->promotion_tax);
        }
        return $items;
    }
    function getListQuery()
    {
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
        $app = JFactory::getApplication();
        $input = $app->input;
        $tsmart_product_id = $this->getState('filter.product_id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $tsmart_departure_id = $this->getState('filter.tsmart_departure_id');
        $query->select('departure.tsmart_departure_id,departure.departure_name,departure.departure_code,departure.assign_user_id,departure.tsmart_product_id,departure.departure_date,departure.sale_period_from,departure.sale_period_to,departure.published,products_en_gb.product_name,service_class.service_class_name')
            ->select('departure.min_space,departure.max_space')
            ->from('#__tsmart_departure AS departure')
            ->leftJoin('#__tsmart_products AS products USING(tsmart_product_id)')
            ->innerJoin('#__tsmart_products_en_gb AS products_en_gb ON products_en_gb.tsmart_product_id=products.tsmart_product_id')
            ->leftJoin('#__tsmart_service_class AS service_class USING(tsmart_service_class_id)')
            ->where('departure.tsmart_departure_parent_id IS NOT NULL')
            ->leftJoin('#__tsmart_tour_price AS tour_price ON departure.departure_date>= tour_price.sale_period_from AND departure.departure_date<=tour_price.sale_period_to AND tour_price.tsmart_product_id=departure.tsmart_product_id AND tour_price.tsmart_service_class_id=departure.tsmart_service_class_id')
            ->leftJoin('#__tsmart_group_size_id_tour_price_id AS group_size_id_tour_price_id ON group_size_id_tour_price_id.tsmart_price_id=tour_price.tsmart_price_id')
            ->select('tour_price.tax')
            ->leftJoin('#__tsmart_group_size AS group_size ON group_size.tsmart_group_size_id=group_size_id_tour_price_id.tsmart_group_size_id')
            //->where('group_size.type='.$query->q(tsmGroupSize::FLAT_PRICE))
            ->select('group_size_id_tour_price_id.price_adult')
            ->leftJoin('#__tsmart_mark_up_tour_price_id AS mark_up_tour_price_id ON  mark_up_tour_price_id.tsmart_price_id=tour_price.tsmart_price_id')
            ->select('
                    mark_up_tour_price_id.price_adult AS mark_up_price_adult,
                    mark_up_tour_price_id.adult AS mark_up_adult,
                    mark_up_tour_price_id.type AS mark_up_type
            ')
            ->leftJoin('#__tsmart_tour_promotion_price AS tour_promotion_price ON departure.departure_date>= tour_promotion_price.sale_period_from AND departure.departure_date<=tour_promotion_price.sale_period_to AND tour_promotion_price.tsmart_product_id=departure.tsmart_product_id AND tour_promotion_price.tsmart_service_class_id=departure.tsmart_service_class_id')
            ->select('tour_promotion_price.tax AS promotion_tax')
            ->leftJoin('#__tsmart_group_size_id_tour_promotion_price_id AS group_size_id_tour_promotion_price_id ON group_size_id_tour_promotion_price_id.tsmart_promotion_price_id=tour_promotion_price.tsmart_promotion_price_id')
            ->select('group_size_id_tour_promotion_price_id.price_adult AS promotion_price_adult')
            ->leftJoin('#__tsmart_mark_up_tour_promotion_net_price_id AS mark_up_tour_promotion_net_price_id ON mark_up_tour_promotion_net_price_id.tsmart_promotion_price_id=tour_promotion_price.tsmart_promotion_price_id')
            ->select('
                    mark_up_tour_promotion_net_price_id.price_adult AS mark_up_promotion_net_price_adult,
                    mark_up_tour_promotion_net_price_id.adult AS mark_up_promotion_net_adult,
                    mark_up_tour_promotion_net_price_id.type AS mark_up_promotion_net_type
            ')
            ->leftJoin('#__tsmart_mark_up_tour_promotion_price_id AS mark_up_tour_promotion_price_id ON mark_up_tour_promotion_price_id.tsmart_promotion_price_id=tour_promotion_price.tsmart_promotion_price_id')
            ->select('
                    mark_up_tour_promotion_price_id.price_adult AS mark_up_promotion_price_adult,
                    mark_up_tour_promotion_price_id.adult AS mark_up_promotion_adult,
                    mark_up_tour_promotion_price_id.type AS mark_up_promotion_type
            ')
            ->group('departure.tsmart_departure_id');
        if ($tsmart_departure_id) {
            $query->where('departure.tsmart_departure_id=' . (int)$tsmart_departure_id);
        }
        if ($departure_code = $this->getState('filter.departure_code'))
        {
            $departure_code = '"%' . $db->escape($departure_code, true) . '%"';
            $query->where('departure.departure_code LIKE '.$departure_code);
        }
        if ($tsmart_product_id) {
            $query->where('departure.tsmart_product_id=' . (int)$tsmart_product_id);
        }
        $user = JFactory::getUser();
        $shared = '';
        if (vmAccess::manager()) {
            //$query->where('transferaddon.shared=1','OR');
        }
        $search = vRequest::getCmd('search', false);
        if (empty($search)) {
            $search = vRequest::getString('search', false);
        }
        // add filters
        if ($search) {
            $db = JFactory::getDBO();
            $search = '"%' . $db->escape($search, true) . '%"';
            $query->where('departure.departure_name LIKE ' . $search);
        }
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'departure.tsmart_departure_id');
        $orderDirn = $this->state->get('list.direction', 'asc');
        if ($orderCol == 'airport.ordering') {
            $orderCol = $db->quoteName('departure.tsmart_departure_id') . ' ' . $orderDirn . ', ' . $db->quoteName('itinerary.ordering');
        }
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        $query->group('departure.tsmart_departure_id');
        echo $query->dump();
        return $query;
    }
    public function save_departure_item($data)
    {
        $tsmart_product_id = $data['tour_id'];
        $departure_name = $data['departure_name'];
        $tour_service_class_id = $data['tour_service_class_id'];
        if ($departure_name == '') {
            vmError('please set departure name');
            return false;
        }
        $list_basic_available2 = $this->get_departure_item($data);
        if (count($list_basic_available2) == 0) {
            vmError('there are no departure available');
            return false;
        }
        $list_date_available = array();
        if (count($list_basic_available2)) {
            foreach ($list_basic_available2 as $item) {
                $list_date_available[] = $item->date_select;
            }
        }
        //lấy ra group_size_id từ bản ghi đầu tiên
        $tsmart_group_size_id = reset($list_basic_available2)->tsmart_group_size_id;
        $list_date_available = '"' . implode('","', $list_date_available) . '"';
        //check exists data
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('tsmart_departure_id')
            ->from('#__tsmart_departure')
            ->where('departure_date IN(' . $list_date_available . ')')
            ->where('tsmart_product_id=' . (int)$tsmart_product_id)
            ->where('tsmart_service_class_id=' . (int)$tour_service_class_id)
            ->where('tsmart_group_size_id=' . (int)$tsmart_group_size_id);
        $list_tsmart_departure_id = $db->setQuery($query)->loadObjectList();
        if (count($list_tsmart_departure_id) > 1) {
            vmError('there are some departure exists');
            return false;
        }
        $tsmart_departure_id = parent::store($data);
        $db = JFactory::getDbo();
        if (!$tsmart_departure_id) {
            vmError('can not save departure ' . $db->getErrorMsg());
            return false;
        }
        $query = $db->getQuery(true);
        $table_departure = tsmTable::getInstance('Departure', 'Table');
        $table_departure->bind($data);
        $table_departure->store();
        $tsmart_departure_id = $table_departure->tsmart_departure_id;
        //inser to group size
        foreach ($list_basic_available2 as $item) {
            $table_departure->tsmart_departure_id = 0;
            $table_departure->published = 1;
            $table_departure->tsmart_departure_id = $tsmart_departure_id;
            $table_departure->tsmart_product_id = $tsmart_product_id;
            $table_departure->departure_date = JFactory::getDate($item->date_select)->toSql();
            $table_departure->tsmart_service_class_id = $item->service_class_id;
            $table_departure->tsmart_group_size_id = $item->tsmart_group_size_id;
            $table_departure->senior_price = $item->senior_price;
            $table_departure->adult_price = $item->price_adult;
            $table_departure->teen_price = $item->price_teen;
            $table_departure->infant_price = $item->price_infant;
            $table_departure->children1_price = $item->price_children1;
            $table_departure->children2_price = $item->price_children2;
            $table_departure->private_room_price = $item->price_private_room;
            $table_departure->senior_departure_price = $item->departure_price->price_senior;
            $table_departure->adult_departure_price = $item->departure_price->price_adult;
            $table_departure->teen_departure_price = $item->departure_price->price_teen;
            $table_departure->infant_departure_price = $item->departure_price->price_infant;
            $table_departure->children1_departure_price = $item->departure_price->price_children1;
            $table_departure->children2_departure_price = $item->departure_price->price_children2;
            $table_departure->private_room_departure_price = $item->departure_price->price_private_room;
            $table_departure->store();
            $err = $db->getErrorMsg();
            if (!empty($err)) {
                vmError('can not insert group size in this tour', $err);
            }
        }
        $table_departure->delete($tsmart_departure_id);
        $err = $db->getErrorMsg();
        if (!empty($err)) {
            vmError('can not insert group size in this tour', $err);
        }
        return $list_basic_available2;
    }
    public function get_departure_item($data = array())
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $weeklies = $data['weekly'];
        $a_list_date = $data['list_date'];
        $min_space = $data['min_space'];
        if ($min_space == 0) {
            vmError('please set min space ');
            return false;
        }
        $tour_id = $data['tour_id'];
        if ($tour_id == 0) {
            vmError('please set tour ');
            return false;
        }
        $tsmart_service_class_id = $data['tour_service_class_id'];
        if ($tsmart_service_class_id == 0) {
            vmError('please set tour class ');
            return false;
        }
        $vail_period_from = JFactory::getDate($data['vail_period_from']);
        $vail_period_to = JFactory::getDate($data['vail_period_to']);
        $date_type = $data['date_type'];
        if ($date_type == 'weekly' && count($weeklies) == 0) {
            vmError('please select dates ');
            return false;
        }
        if ($date_type == 'day_select' && count($a_list_date) == 0) {
            vmError('please select dates ');
            return false;
        }
        // Start date
        $date = JFactory::getDate($vail_period_from);
        // End date
        $end_date = JFactory::getDate($vail_period_to);
        $list_date = array();
        while ($date->getTimestamp() <= $end_date->getTimestamp()) {
            $day_of_week = date("w", $date->getTimestamp());
            if ($date_type == 'weekly' && count($weeklies)) {
                if (in_array($day_of_week, $weeklies)) {
                    $list_date[] = $date->format('Y-m-d');
                }
                $date->modify('+1 day');
                continue;
            }
            if ($date_type == 'day_select' && count($a_list_date)) {
                if (in_array($date->format('Y-m-d'), $a_list_date)) {
                    $list_date[] = $date->format('Y-m-d');
                }
                $date->modify('+1 day');
                continue;
            }
            if (!count($weeklies) && !count($a_list_date)) {
                $list_date[] = $date->format('Y-m-d');
                $date->modify('+1 day');
                continue;
            }
        }
        //lấy ra các departure theo điều kiện tour_id, tour class và group size
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        //get list group size by tour id
        $query->select('group_size.*')
            ->from('#__tsmart_tour_id_group_size_id AS tour_id_group_size_id')
            ->where('tour_id_group_size_id.tsmart_product_id=' . (int)$tour_id)
            ->leftJoin('#__tsmart_group_size AS group_size ON group_size.tsmart_group_size_id=tour_id_group_size_id.tsmart_group_size_id')
            ->order('group_size.from')
            ->where('group_size.from>=' . (int)$min_space . ' OR (group_size.from<=' . (int)$min_space . ' AND group_size.to>=' . (int)$min_space . ')');
        $group_size = $db->setQuery($query)->loadObject();
        if (!$group_size) {
            vmError('there no group size from ' . (int)$min_space);
            return false;
        }
        $query->clear()
            ->select('group_size_id_tour_departure_price_id.*')
            ->from('#__tsmart_group_size_id_tour_departure_price_id AS group_size_id_tour_departure_price_id')
            ->leftJoin('#__tsmart_tour_departure_price AS departure_price
			ON departure_price.tsmart_departure_price_id=group_size_id_tour_departure_price_id.tsmart_tour_departure_price_id')
            ->select('departure_price.*')
            ->where('departure_price.tsmart_product_id=' . (int)$tour_id)
            ->where('departure_price.tsmart_service_class_id=' . (int)$tsmart_service_class_id)
            ->where('group_size_id_tour_departure_price_id.tsmart_group_size_id=' . (int)$group_size->tsmart_group_size_id);
        $list_departure_price = $db->setQuery($query)->loadObjectList();
        if (count($list_departure_price) == 0) {
            vmWarn('there are no departure price ');
        }
        //lấy giá có departure lọc theo điều kiện thỏa mãn danh sách này
        $list_departure_available = array();
        foreach ($list_date as $item_date) {
            $time_stamp_item_date = JFactory::getDate($item_date)->getTimestamp();
            foreach ($list_departure_price as $item_departure_price_date) {
                $start_date = $item_departure_price_date->sale_period_from;
                $end_date = $item_departure_price_date->sale_period_to;
                $time_stamp_start_date = JFactory::getDate($start_date)->getTimestamp();
                $time_stamp_end_date = JFactory::getDate($end_date)->getTimestamp();
                if ($time_stamp_item_date >= $time_stamp_start_date && $time_stamp_item_date <= $time_stamp_end_date) {
                    $item_departure_price_date1 = clone $item_departure_price_date;
                    $item_departure_price_date1->date_select = $item_date;
                    $list_departure_available[] = $item_departure_price_date1;
                }
            }
        }
        //lấy ra giá có markup (giá trị departure) theo tour có net departure price
        $list_mark_up_tour_departure_net_price = array();
        $list_tsmart_departure_price_id = array();
        foreach ($list_departure_price as $item_departure_price) {
            if (!in_array($item_departure_price->tsmart_departure_price_id, $list_tsmart_departure_price_id)) {
                $list_tsmart_departure_price_id[] = $item_departure_price->tsmart_departure_price_id;
            }
        }
        if (count($list_tsmart_departure_price_id) >= 1) {
            $query->clear()
                ->from('#__tsmart_mark_up_tour_departure_net_price_id AS mark_up_tour_departure_net_price_id')
                ->select('mark_up_tour_departure_net_price_id.*')
                ->where('mark_up_tour_departure_net_price_id.tsmart_tour_departure_price_id IN (' . implode(',', $list_tsmart_departure_price_id) . ')');
            $list_mark_up_tour_departure_net_price = $db->setQuery($query)->loadObjectList();
        }
        $list_mark_up_tour_departure_net_price2 = array();
        foreach ($list_mark_up_tour_departure_net_price as $mark_up_tour_departure_net_price) {
            $tsmart_tour_departure_price_id = $mark_up_tour_departure_net_price->tsmart_tour_departure_price_id;
            $list_mark_up_tour_departure_net_price2[$tsmart_tour_departure_price_id][$mark_up_tour_departure_net_price->type] = $mark_up_tour_departure_net_price;
        }
        if (count($list_departure_available) == 0) {
            vmWarn('there are no departure price ');
        }
        //tính giá lãi có departure
        //1.tính giá thực sau khi departure
        foreach ($list_departure_available as $key => $departure_available) {
            $tsmart_departure_price_id = $departure_available->tsmart_departure_price_id;
            $percent_price = $list_mark_up_tour_departure_net_price2[$tsmart_departure_price_id]['percent'];
            if (
                $percent_price->senior != 0
                || $percent_price->adult != 0
                || $percent_price->teen != 0
                || $percent_price->infant != 0
                || $percent_price->children1 != 0
                || $percent_price->children2 != 0
                || $percent_price->private_room != 0
            ) {
                //giá ???c tính theo percent thì không tính theo amount n?a
                $price_senior = $list_departure_available[$key]->price_senior;
                $price_senior = $price_senior - ($price_senior * $percent_price->senior) / 100;
                $list_departure_available[$key]->price_senior = $price_senior;
                $price_adult = $list_departure_available[$key]->price_adult;
                $price_adult = $price_adult - ($price_adult * $percent_price->adult) / 100;
                $list_departure_available[$key]->price_adult = $price_adult;
                $price_teen = $list_departure_available[$key]->price_teen;
                $price_teen = $price_teen - ($price_teen * $percent_price->teen) / 100;
                $list_departure_available[$key]->price_teen = $price_teen;
                $price_infant = $list_departure_available[$key]->price_infant;
                $price_infant = $price_infant - ($price_infant * $percent_price->infant) / 100;
                $list_departure_available[$key]->price_infant = $price_infant;
                $price_children1 = $list_departure_available[$key]->price_children1;
                $price_children1 = $price_children1 - ($price_children1 * $percent_price->children1) / 100;
                $list_departure_available[$key]->price_children1 = $price_children1;
                $price_children2 = $list_departure_available[$key]->price_children2;
                $price_children2 = $price_children2 - ($price_children2 * $percent_price->children2) / 100;
                $list_departure_available[$key]->price_children2 = $price_children2;
                $price_private_room = $list_departure_available[$key]->price_private_room;
                $price_private_room = $price_private_room - ($price_private_room * $percent_price->private_room) / 100;
                $list_departure_available[$key]->price_private_room = $price_private_room;
            } else {
                $amount_price = $list_mark_up_tour_departure_net_price2[$tsmart_departure_price_id]['amount'];
                $price_senior = $list_departure_available[$key]->price_senior;
                $price_senior = $price_senior - $amount_price->senior;
                $list_departure_available[$key]->price_senior = $price_senior;
                $price_adult = $list_departure_available[$key]->price_adult;
                $price_adult = $price_adult - $amount_price->adult;
                $list_departure_available[$key]->price_adult = $price_adult;
                $price_teen = $list_departure_available[$key]->price_teen;
                $price_teen = $price_teen - $amount_price->teen;
                $list_departure_available[$key]->price_teen = $price_teen;
                $price_infant = $list_departure_available[$key]->price_infant;
                $price_infant = $price_infant - $amount_price->infant;
                $list_departure_available[$key]->price_infant = $price_infant;
                $price_children1 = $list_departure_available[$key]->price_children1;
                $price_children1 = $price_children1 - $amount_price->children1;
                $list_departure_available[$key]->price_children1 = $price_children1;
                $price_children2 = $list_departure_available[$key]->price_children2;
                $price_children2 = $price_children2 - $amount_price->children2;
                $list_departure_available[$key]->price_children2 = $price_children2;
                $price_private_room = $list_departure_available[$key]->price_private_room;
                $price_private_room = $price_private_room - $amount_price->private_room;
                $list_departure_available[$key]->price_private_room = $price_private_room;
            }
        }
        if (count($list_tsmart_departure_price_id) == 0) {
            vmWarn('there are no departure price ');
        }
        //lấy ra các giá markup (giá trị có lãi) theo tour có net departure price
        $list_mark_up_tour_departure_price = array();
        if (count($list_tsmart_departure_price_id) >= 1) {
            $query->clear()
                ->from('#__tsmart_mark_up_tour_departure_price_id AS mark_up_tour_departure_price_id')
                ->select('mark_up_tour_departure_price_id.*')
                ->where('mark_up_tour_departure_price_id.tsmart_tour_departure_price_id IN (' . implode(',', $list_tsmart_departure_price_id) . ')');
            $list_mark_up_tour_departure_price = $db->setQuery($query)->loadObjectList();
        }
        $list_mark_up_tour_departure_price2 = array();
        foreach ($list_mark_up_tour_departure_price as $mark_up_tour_departure_price) {
            $tsmart_tour_departure_price_id = $mark_up_tour_departure_price->tsmart_tour_departure_price_id;
            $list_mark_up_tour_departure_price2[$tsmart_tour_departure_price_id][$mark_up_tour_departure_price->type] = $mark_up_tour_departure_price;
        }
        //2.tính giá thực sau khi khi có markup (có phần lãi)
        foreach ($list_departure_available as $key => $departure_available) {
            $tsmart_departure_price_id = $departure_available->tsmart_departure_price_id;
            $percent_price = $list_mark_up_tour_departure_price2[$tsmart_departure_price_id]['percent'];
            if (
                $percent_price->senior != 0
                || $percent_price->adult != 0
                || $percent_price->teen != 0
                || $percent_price->infant != 0
                || $percent_price->children1 != 0
                || $percent_price->children2 != 0
                || $percent_price->private_room != 0
            ) {
                //giá  tính theo percent thì không tính theo amount n?a
                $price_senior = $list_departure_available[$key]->price_senior;
                $price_senior = $price_senior + ($price_senior * $percent_price->senior) / 100;
                $list_departure_available[$key]->price_senior = $price_senior;
                $price_adult = $list_departure_available[$key]->price_adult;
                $price_adult = $price_adult + ($price_adult * $percent_price->adult) / 100;
                $list_departure_available[$key]->price_adult = $price_adult;
                $price_teen = $list_departure_available[$key]->price_teen;
                $price_teen = $price_teen + ($price_teen * $percent_price->teen) / 100;
                $list_departure_available[$key]->price_teen = $price_teen;
                $price_infant = $list_departure_available[$key]->price_infant;
                $price_infant = $price_infant + ($price_infant * $percent_price->infant) / 100;
                $list_departure_available[$key]->price_infant = $price_infant;
                $price_children1 = $list_departure_available[$key]->price_children1;
                $price_children1 = $price_children1 + ($price_children1 * $percent_price->children1) / 100;
                $list_departure_available[$key]->price_children1 = $price_children1;
                $price_children2 = $list_departure_available[$key]->price_children2;
                $price_children2 = $price_children2 + ($price_children2 * $percent_price->children2) / 100;
                $list_departure_available[$key]->price_children2 = $price_children2;
                $price_private_room = $list_departure_available[$key]->price_private_room;
                $price_private_room = $price_private_room + ($price_private_room * $percent_price->private_room) / 100;
                $list_departure_available[$key]->price_private_room = $price_private_room;
            } else {
                $amount_price = $list_mark_up_tour_departure_price2[$tsmart_departure_price_id]['amount'];
                $price_senior = $list_departure_available[$key]->price_senior;
                $price_senior = $price_senior + $amount_price->senior;
                $list_departure_available[$key]->price_senior = $price_senior;
                $price_adult = $list_departure_available[$key]->price_adult;
                $price_adult = $price_adult + $amount_price->adult;
                $list_departure_available[$key]->price_adult = $price_adult;
                $price_teen = $list_departure_available[$key]->price_teen;
                $price_teen = $price_teen + $amount_price->teen;
                $list_departure_available[$key]->price_teen = $price_teen;
                $price_infant = $list_departure_available[$key]->price_infant;
                $price_infant = $price_infant + $amount_price->infant;
                $list_departure_available[$key]->price_infant = $price_infant;
                $price_children1 = $list_departure_available[$key]->price_children1;
                $price_children1 = $price_children1 + $amount_price->children1;
                $list_departure_available[$key]->price_children1 = $price_children1;
                $price_children2 = $list_departure_available[$key]->price_children2;
                $price_children2 = $price_children2 + $amount_price->children2;
                $list_departure_available[$key]->price_children2 = $price_children2;
                $price_private_room = $list_departure_available[$key]->price_private_room;
                $price_private_room = $price_private_room + $amount_price->private_room;
                $list_departure_available[$key]->price_private_room = $price_private_room;
            }
        }
        $list_departure_available2 = array();
        foreach ($list_departure_available as $departure_available) {
            $list_departure_available2[$departure_available->tsmart_product_id . '-' . $departure_available->service_class_id . '-' . $departure_available->tsmart_group_size_id . '-' . $departure_available->date_select] = $departure_available;
        }
        //tính giá bán sau thuế
        foreach ($list_departure_available as $key => $departure_available) {
            $price_senior = $list_departure_available[$key]->price_senior;
            $price_senior = $price_senior + ($price_senior * $departure_available->tax) / 100;
            $list_departure_available[$key]->price_senior = $price_senior;
            $price_adult = $list_departure_available[$key]->price_adult;
            $price_adult = $price_adult + ($price_adult * $departure_available->tax) / 100;
            $list_departure_available[$key]->price_adult = $price_adult;
            $price_teen = $list_departure_available[$key]->price_teen;
            $price_teen = $price_teen + ($price_teen * $departure_available->tax) / 100;
            $list_departure_available[$key]->price_teen = $price_teen;
            $price_infant = $list_departure_available[$key]->price_infant;
            $price_infant = $price_infant + ($price_infant * $departure_available->tax) / 100;
            $list_departure_available[$key]->price_infant = $price_infant;
            $price_children1 = $list_departure_available[$key]->price_children1;
            $price_children1 = $price_children1 + ($price_children1 * $departure_available->tax) / 100;
            $list_departure_available[$key]->price_children1 = $price_children1;
            $price_children2 = $list_departure_available[$key]->price_children2;
            $price_children2 = $price_children2 + ($price_children2 * $departure_available->tax) / 100;
            $list_departure_available[$key]->price_children2 = $price_children2;
            $price_private_room = $list_departure_available[$key]->price_private_room;
            $price_private_room = $price_private_room + ($price_private_room * $departure_available->tax) / 100;
            $list_departure_available[$key]->price_private_room = $price_private_room;
        }
        //sử lý với giá basic
        $query->clear()
            ->select('group_size_id_tour_price_id.*')
            ->from('#__tsmart_group_size_id_tour_price_id AS group_size_id_tour_price_id')
            ->leftJoin('#__tsmart_tour_price AS tour_price
			ON tour_price.tsmart_price_id=group_size_id_tour_price_id.tsmart_price_id')
            ->select('tour_price.*')
            ->where('tour_price.tsmart_product_id=' . (int)$tour_id)
            ->where('tour_price.tsmart_service_class_id=' . (int)$tsmart_service_class_id)
            ->where('group_size_id_tour_price_id.tsmart_group_size_id=' . (int)$group_size->tsmart_group_size_id);
        $list_price = $db->setQuery($query)->loadObjectList();
        if (count($list_departure_available2) == 0 && count($list_price) == 0) {
            vmError('there are no base price  and departure price');
            return false;
        }
        // lấy giá có departure lọc theo điều kiên thỏa mãn danh sách ngày
        $list_price_available = array();
        foreach ($list_date as $item_date) {
            $time_stamp_item_date = JFactory::getDate($item_date)->getTimestamp();
            foreach ($list_price as $item_price_date) {
                $start_date = $item_price_date->sale_period_from;
                $end_date = $item_price_date->sale_period_to;
                $time_stamp_start_date = JFactory::getDate($start_date)->getTimestamp();
                $time_stamp_end_date = JFactory::getDate($end_date)->getTimestamp();
                if ($time_stamp_item_date >= $time_stamp_start_date && $time_stamp_item_date <= $time_stamp_end_date) {
                    $item_price_date1 = clone $item_price_date;
                    $item_price_date1->date_select = $item_date;
                    $list_price_available[] = $item_price_date1;
                }
            }
        }
        //lấy ra các giá markup (giá trị có lãi) theo tour có net  price
        $list_tsmart_price_id = array();
        foreach ($list_price as $item_price) {
            if (!in_array($item_price->tsmart_price_id, $list_tsmart_price_id)) {
                $list_tsmart_price_id[] = $item_price->tsmart_price_id;
            }
        }
        $list_mark_up_tour_price = array();
        if (count($list_tsmart_price_id) >= 1) {
            $query->clear()
                ->from('#__tsmart_mark_up_tour_price_id AS mark_up_tour_price_id')
                ->select('mark_up_tour_price_id.*')
                ->where('mark_up_tour_price_id.tsmart_price_id IN (' . implode(',', $list_tsmart_price_id) . ')');
            $list_mark_up_tour_price = $db->setQuery($query)->loadObjectList();
        }
        $list_mark_up_tour_price2 = array();
        foreach ($list_mark_up_tour_price as $mark_up_tour_price) {
            $tsmart_price_id = $mark_up_tour_price->tsmart_price_id;
            $list_mark_up_tour_price2[$tsmart_price_id][$mark_up_tour_price->type] = $mark_up_tour_price;
        }
        //2.tính giá thực sau khi khi có markup basic (có phần lãi)
        foreach ($list_price_available as $key => $price_available) {
            $tsmart_price_id = $price_available->tsmart_price_id;
            $percent_price = $list_mark_up_tour_price2[$tsmart_price_id]['percent'];
            if (
                $percent_price->senior != 0
                || $percent_price->adult != 0
                || $percent_price->teen != 0
                || $percent_price->infant != 0
                || $percent_price->children1 != 0
                || $percent_price->children2 != 0
                || $percent_price->private_room != 0
            ) {
                //giá ???c tính theo percent thì không tính theo amount n?a
                $price_senior = $list_price_available[$key]->price_senior;
                $price_senior = $price_senior + ($price_senior * $percent_price->senior) / 100;
                $list_price_available[$key]->price_senior = $price_senior;
                $price_adult = $list_price_available[$key]->price_adult;
                $price_adult = $price_adult + ($price_adult * $percent_price->adult) / 100;
                $list_price_available[$key]->price_adult = $price_adult;
                $price_teen = $list_price_available[$key]->price_teen;
                $price_teen = $price_teen + ($price_teen * $percent_price->teen) / 100;
                $list_price_available[$key]->price_teen = $price_teen;
                $price_infant = $list_price_available[$key]->price_infant;
                $price_infant = $price_infant + ($price_infant * $percent_price->infant) / 100;
                $list_price_available[$key]->price_infant = $price_infant;
                $price_children1 = $list_price_available[$key]->price_children1;
                $price_children1 = $price_children1 + ($price_children1 * $percent_price->children1) / 100;
                $list_price_available[$key]->price_children1 = $price_children1;
                $price_children2 = $list_price_available[$key]->price_children2;
                $price_children2 = $price_children2 + ($price_children2 * $percent_price->children2) / 100;
                $list_price_available[$key]->price_children2 = $price_children2;
                $price_private_room = $list_price_available[$key]->price_private_room;
                $price_private_room = $price_private_room + ($price_private_room * $percent_price->private_room) / 100;
                $list_price_available[$key]->price_private_room = $price_private_room;
            } else {
                $amount_price = $list_mark_up_tour_price2[$tsmart_price_id]['amount'];
                $price_senior = $list_price_available[$key]->price_senior;
                $price_senior = $price_senior + $amount_price->senior;
                $list_price_available[$key]->price_senior = $price_senior;
                $price_adult = $list_price_available[$key]->price_adult;
                $price_adult = $price_adult + $amount_price->adult;
                $list_price_available[$key]->price_adult = $price_adult;
                $price_teen = $list_price_available[$key]->price_teen;
                $price_teen = $price_teen + $amount_price->teen;
                $list_price_available[$key]->price_teen = $price_teen;
                $price_infant = $list_price_available[$key]->price_infant;
                $price_infant = $price_infant + $amount_price->infant;
                $list_price_available[$key]->price_infant = $price_infant;
                $price_children1 = $list_price_available[$key]->price_children1;
                $price_children1 = $price_children1 + $amount_price->children1;
                $list_price_available[$key]->price_children1 = $price_children1;
                $price_children2 = $list_price_available[$key]->price_children2;
                $price_children2 = $price_children2 + $amount_price->children2;
                $list_price_available[$key]->price_children2 = $price_children2;
                $price_private_room = $list_price_available[$key]->price_private_room;
                $price_private_room = $price_private_room + $amount_price->private_room;
                $list_price_available[$key]->price_private_room = $price_private_room;
            }
        }
        $list_price_available2 = array();
        foreach ($list_price_available as $price_available) {
            $list_price_available2[$price_available->tsmart_product_id . '-' . $price_available->service_class_id . '-' . $price_available->tsmart_group_size_id . '-' . $price_available->date_select] = $price_available;
        }
        //tính giá bán sau thuế
        foreach ($list_price_available as $key => $price_available) {
            $price_senior = $list_price_available[$key]->price_senior;
            $price_senior = $price_senior + ($price_senior * $price_available->tax) / 100;
            $list_price_available[$key]->price_senior = $price_senior;
            $price_adult = $list_price_available[$key]->price_adult;
            $price_adult = $price_adult + ($price_adult * $price_available->tax) / 100;
            $list_price_available[$key]->price_adult = $price_adult;
            $price_teen = $list_price_available[$key]->price_teen;
            $price_teen = $price_teen + ($price_teen * $price_available->tax) / 100;
            $list_price_available[$key]->price_teen = $price_teen;
            $price_infant = $list_price_available[$key]->price_infant;
            $price_infant = $price_infant + ($price_infant * $price_available->tax) / 100;
            $list_price_available[$key]->price_infant = $price_infant;
            $price_children1 = $list_price_available[$key]->price_children1;
            $price_children1 = $price_children1 + ($price_children1 * $price_available->tax) / 100;
            $list_price_available[$key]->price_children1 = $price_children1;
            $price_children2 = $list_price_available[$key]->price_children2;
            $price_children2 = $price_children2 + ($price_children2 * $price_available->tax) / 100;
            $list_price_available[$key]->price_children2 = $price_children2;
            $price_private_room = $list_price_available[$key]->price_private_room;
            $price_private_room = $price_private_room + ($price_private_room * $price_available->tax) / 100;
            $list_price_available[$key]->price_private_room = $price_private_room;
        }
        if (count($list_price_available2) == 0) {
            vmWarn('there are no base price');
        }
        foreach ($list_departure_available2 as $key => $value) {
            if (!is_object($list_price_available2[$key])) {
                $list_price_available2[$key] = new stdClass();
                $list_price_available2[$key]->departure_price = $value;
                $list_price_available2[$key]->date_select = $value->date_select;
            } else {
                $list_price_available2[$key]->departure_price = $value;
            }
        }
        if (count($list_price_available2) == 0) {
            vmWarn('there are no departure availble');
            return false;
        }
        return $list_price_available2;
    }
    public function create_children_departure($tsmart_departure_id)
    {
        $query = $this->_db->getQuery(true);
        $query->delete('#__tsmart_departure')
            ->where('tsmart_departure_parent_id=' . (int)$tsmart_departure_id);
        $this->_db->setQuery($query);
        $ok = $this->_db->execute();
        if (!$ok) {


            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $table_departure = $this->getTable();
        $table_departure->load($tsmart_departure_id);
        $date_type = $table_departure->date_type;
        if ($date_type == 'day_select') {
            $days_seleted = explode(',', $table_departure->days_seleted);
        } else {
            $sale_period_from = $table_departure->sale_period_from;
            $sale_period_to = $table_departure->sale_period_to;
            require_once JPATH_ROOT . DS . 'administrator/components/com_tsmart/helpers/tsmutility.php';
            $days_seleted = TSMUtility::dateRange($sale_period_from, $sale_period_to);
            $weekly = $table_departure->weekly;
            $weekly = explode(',', $weekly);
            foreach ($days_seleted as $key => $day) {
                $day_of_week = strtolower(date('D', strtotime($day)));
                if (!in_array($day_of_week, $weekly)) {
                    unset($days_seleted[$key]);
                }
            }

        }
        require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmdeparture.php';
        foreach ($days_seleted as $day) {
            $table_departure->departure_date = $day;
            $day = JFactory::getDate($day);
            $table_departure->tsmart_departure_id = 0;
            $table_departure->departure_code = tsmDeparture::get_format_departure_code($tsmart_departure_id, $day);
            $table_departure->tsmart_departure_parent_id = $tsmart_departure_id;
            $ok = $table_departure->store();
            if (!$ok) {

                $this->setError($table_departure->getErrors());
                return false;
            }
        }
        return true;
    }
    function store(&$data)
    {
        if (!vmAccess::manager('currency')) {
            vmWarn('Insufficient permissions to store currency');
            return false;
        }
        return parent::store($data);
    }
    function remove($ids)
    {
        if (!vmAccess::manager('currency')) {
            vmWarn('Insufficient permissions to remove currency');
            return false;
        }
        return parent::remove($ids);
    }
}
// pure php no closing tag