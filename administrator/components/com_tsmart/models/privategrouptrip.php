<?php
/**
 *
 * Data module for shop product
 *
 * @package    tsmart
 * @subpackage product
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 8970 2015-09-06 23:19:17Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if (!class_exists('tmsModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');

/**
 * Model class for shop product
 *
 * @package    tsmart
 * @subpackage product
 */
class tsmartModelPrivategrouptrip extends tmsModel
{

    protected $context = 'privategrouptrip';
    const PRODUCT_TYPE = 'privategrouptrip';

    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('price');
    }

    public function getContext()
    {
        return $this->context;
    }

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
    function getItem($id = 0,$booking_date='',$price_type="flat_price")
    {   $debug=TSMUtility::get_debug();
        $app = JFactory::getApplication();
        $input = $app->input;
        $price_helper=tsmHelper::getHepler('price');
        //$product=$price_helper->get_product_by_tour_price_id($id);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('tour_price.tsmart_price_id,service_class.service_class_name,tour_price.tsmart_product_id,tour_price.full_charge_children1,tour_price.full_charge_children2')
            ->from('#__tsmart_tour_price AS tour_price ')
            ->innerJoin('#__tsmart_service_class AS service_class ON service_class.tsmart_service_class_id=tour_price.tsmart_service_class_id');
        if($price_type=='multi_price'){
            $query ->where('tour_price.tsmart_price_id=' . (int)$id);
        }
        else{
            $query->leftJoin('#__tsmart_departure AS departure On departure.tsmart_departure_id='.(int)$id);
        }
        $query->leftJoin('#__tsmart_itinerary AS itinerary ON itinerary.tsmart_product_id=tour_price.tsmart_product_id')
            ->select('count(distinct itinerary.tsmart_itinerary_id) AS total_day ')
            ->leftJoin('#__tsmart_cityarea AS cityarea ON cityarea.tsmart_cityarea_id=itinerary.tsmart_cityarea_id')
            ->leftJoin('#__tsmart_states AS states ON states.tsmart_state_id=cityarea.tsmart_state_id')
            ->leftJoin('#__tsmart_countries AS countries ON countries.tsmart_country_id=states.tsmart_country_id')
            ->select('GROUP_CONCAT(
                    CONCAT(states.state_name,",",countries.country_name) SEPARATOR ";"
            ) AS list_destination')
            ->select('tour_price.tax')
            ->leftJoin('#__tsmart_products AS products ON products.tsmart_product_id=tour_price.tsmart_product_id')
            ->select('products.published AS tour_state,products.tour_length AS tour_length,products.max_person AS max_person')
            ->group('service_class.tsmart_service_class_id')
            ->order('service_class.ordering')//->where('')
        ;
        $start_date = $this->getState('filter.start_date');

        $start_date = JFactory::getDate($start_date);
        $query->leftJoin('#__tsmart_date_availability AS date_availability ON date_availability.tsmart_service_class_id=tour_price.tsmart_service_class_id AND date_availability.tsmart_product_id=tour_price.tsmart_product_id');
        $query->select(' IF(date_availability.date =' . $query->quote($start_date->toSql()) . ' OR CURDATE()=' . $query->quote($start_date->toSql()) . ', 0, 1) as tour_state');

        $total_passenger_from_12_years_old = $this->getState('filter.total_passenger_from_12_years_old');
        $query->innerJoin('#__tsmart_group_size_id_tour_price_id AS group_size_id_tour_price_id ON group_size_id_tour_price_id.tsmart_price_id=tour_price.tsmart_price_id');
        $query->select('group_size_id_tour_price_id.id AS group_size_id_tour_price_id');
        $query->select('group_size.tsmart_group_size_id AS tsmart_group_size_id');
        $query->select('group_size_id_tour_price_id.tsmart_group_size_id AS tsmart_group_size_id');
        $query->select('group_size_id_tour_price_id.price_senior AS price_senior');
        $query->select('group_size_id_tour_price_id.price_adult AS price_adult');
        $query->select('group_size_id_tour_price_id.price_teen AS  price_teen');
        $query->select('group_size_id_tour_price_id.price_infant AS price_infant');
        $query->select('group_size_id_tour_price_id.price_children1 AS price_children1');
        $query->select('group_size_id_tour_price_id.price_children2 AS price_children2');
        $query->select('group_size_id_tour_price_id.price_private_room AS price_private_room');
        $query->select('group_size_id_tour_price_id.price_extra_bed AS price_extra_bed');
        $query->leftJoin('#__tsmart_group_size AS group_size ON group_size.tsmart_group_size_id=group_size_id_tour_price_id.tsmart_group_size_id');
        if($price_type=='multi_price'){

            $query->where('group_size.from<=' . (int)$total_passenger_from_12_years_old . ' AND group_size.to>=' . (int)$total_passenger_from_12_years_old);
            $start_date = JFactory::getDate($start_date);
            $query->where('(tour_price.sale_period_from<=' . $query->quote($start_date->toSql()) . ' AND tour_price.sale_period_to>=' . $query->quote($start_date->toSql()) . ')');

        }
        else{
            $query->where('tour_price.sale_period_from<=departure.departure_date AND tour_price.sale_period_to>=departure.departure_date');
            $query->where('tour_price.tsmart_service_class_id=departure.tsmart_service_class_id AND tour_price.tsmart_product_id=departure.tsmart_product_id');

        }

        $query->innerJoin('#__tsmart_mark_up_tour_price_id AS mark_up_tour_price_id ON mark_up_tour_price_id.tsmart_price_id=tour_price.tsmart_price_id')
            ->select('
            mark_up_tour_price_id.id AS id_mark_up_tour_price_id,
            mark_up_tour_price_id.price_senior AS mark_up_price_senior,
            mark_up_tour_price_id.price_adult AS mark_up_price_adult,
            mark_up_tour_price_id.price_teen AS mark_up_price_teen,
            mark_up_tour_price_id.price_infant AS mark_up_price_infant,
            mark_up_tour_price_id.price_children1 AS mark_up_price_children1,
            mark_up_tour_price_id.price_children2 AS mark_up_price_children2,
            mark_up_tour_price_id.price_private_room AS mark_up_price_private_room,
            mark_up_tour_price_id.price_extra_bed AS mark_up_price_extra_bed,
            mark_up_tour_price_id.senior AS mark_up_senior,
            mark_up_tour_price_id.adult AS mark_up_adult,
            mark_up_tour_price_id.teen AS mark_up_teen,
            mark_up_tour_price_id.children1 AS mark_up_children1,
            mark_up_tour_price_id.children2 AS mark_up_children2,
            mark_up_tour_price_id.infant AS mark_up_infant,
            mark_up_tour_price_id.private_room AS mark_up_private_room,
            mark_up_tour_price_id.extra_bed AS mark_up_extra_bed,
            mark_up_tour_price_id.type AS mark_up_type
            ');
        $booking_date = JFactory::getDate($booking_date);
        $query3 = $db->getQuery(true);
        $query3->clear();
        $query3->select('discount2.tsmart_discount_id')
            ->from('#__tsmart_discounts AS discount2')
            ->where('discount2.tsmart_product_id=tour_price.tsmart_product_id AND discount2.published=1 AND discount2.discount_start_date<' . $query->quote($booking_date->toSql()) . ' AND discount2.discount_expiry_date>=' . $query->quote($booking_date->toSql()))
            ->leftJoin('#__tsmart_discount_id_service_class_id AS discount_id_service_class_id ON discount2.tsmart_discount_id=discount_id_service_class_id.tsmart_discount_id')
            ->where('discount_id_service_class_id.tsmart_service_class_id=service_class.tsmart_service_class_id');
        $query->leftJoin('#__tsmart_discounts AS discount2 ON discount2.tsmart_discount_id=(' . $query3 . ')');

        $query->select('discount2.tsmart_discount_id,discount2.discount_name AS discount_name,discount2.percent_or_total AS discount_percent_or_total,discount2.discount_value AS discount_value');
        require_once JPATH_ROOT . DS . 'administrator/components/com_tsmart/helpers/tsmorder.php';
        $query->leftJoin('#__tsmart_orders AS orders ON orders.product_type=' . $query->q(self::PRODUCT_TYPE) . ' AND orders.object_id=tour_price.tsmart_price_id AND orders.tsmart_orderstate_id IS NOT NULL AND orders.tsmart_orderstate_id IN("' . implode('","', tmsorder::get_list_order_confirm()) . '")')
            ->select('COUNT(orders.tsmart_order_id) AS total_order_confirm');
        if($debug)
        {
            echo $query->dump();
        }
        $item = $this->_db->setQuery($query)->loadObject();
        $type = $item->mark_up_type;
        $tax = $item->tax / 100;
        if ($type == 'amount') {
            $price_senior = $item->price_senior + $item->mark_up_price_senior;
            $item->sale_price_senior = round($price_senior + $price_senior * $tax);
            $price_adult = $item->price_adult + (float)$item->mark_up_price_adult;
            $item->sale_price_adult = round($price_adult + $price_adult * $tax);
            $price_teen = $item->price_teen + $item->mark_up_price_teen;
            $item->sale_price_teen = round($price_teen + $price_teen * $tax);
            $price_infant = $item->price_infant + $item->mark_up_price_infant;
            $item->sale_price_infant = round($price_infant + $price_infant * $tax);
            $price_children1 = $item->price_children1 + $item->mark_up_price_children1;
            $item->sale_price_children1 = round($price_children1 + $price_children1 * $tax);
            $price_children2 = $item->price_children2 + $item->mark_up_price_children2;
            $item->sale_price_children2 = round($price_children2 + $price_children2 * $tax);
            $price_private_room = $item->price_private_room + $item->mark_up_price_private_room;
            $item->sale_price_private_room = round($price_private_room + $price_private_room * $tax);
            $extra_bed = $item->price_extra_bed + $item->mark_up_extra_bed;
            $item->sale_price_extra_bed = round($extra_bed + $extra_bed * $tax);


        } else {
            $price_senior = $item->price_senior + $item->price_senior * $item->mark_up_senior / 100;
            $item->sale_price_senior = round($price_senior + $price_senior * $tax);
            $price_adult = $item->price_adult + $item->price_adult * ($item->mark_up_adult / 100);
            $item->sale_price_adult = round($price_adult + $price_adult * $tax);
            $price_teen = $item->price_teen + $item->price_teen * ($item->mark_up_teen / 100);
            $item->sale_price_teen = round($price_teen + $price_teen * $tax);
            $price_infant = $item->price_infant + $item->price_infant * ($item->mark_up_infant / 100);
            $item->sale_price_infant = round($price_infant + $price_infant * $tax);
            $price_children1 = $item->price_children1 + $item->price_children1 * ($item->mark_up_children1 / 100);
            $item->sale_price_children1 = round($price_children1 + $price_children1 * $tax);
            $price_children2 = $item->price_children2 + $item->price_children2 * ($item->mark_up_children2 / 100);
            $item->sale_price_children2 = round($price_children2 + $price_children2 * $tax);
            $price_private_room = $item->price_private_room + $item->price_private_room * ($item->mark_up_private_room / 100);
            $item->sale_price_private_room = round($price_private_room + $price_private_room * $tax);
            $extra_bed = $item->price_extra_bed + $item->price_extra_bed * $item->mark_up_extra_bed / 100;
            $item->sale_price_extra_bed = round($extra_bed + $extra_bed * $tax);

        }
        if ($item->tsmart_discount_id) {
            if ($item->discount_percent_or_total == 'percent') {
                $item->sale_discount_price_senior = $item->sale_price_senior - ($item->discount_value * $item->sale_price_senior) / 100;
                $item->sale_discount_price_adult = $item->sale_price_adult - ($item->discount_value * $item->sale_price_adult) / 100;
                $item->sale_discount_price_teen = $item->sale_price_teen - ($item->discount_value * $item->sale_price_teen) / 100;
                $item->sale_discount_price_children1 = $item->sale_price_children1 - ($item->discount_value * $item->sale_price_children1) / 100;
                $item->sale_discount_price_children2 = $item->sale_price_children2 - ($item->discount_value * $item->sale_price_children2) / 100;
                $item->sale_discount_price_infant = $item->sale_price_infant - ($item->discount_value * $item->sale_price_infant) / 100;
            } else {
                $item->sale_discount_price_senior = (int)$item->sale_price_senior - $item->discount_value;
                $item->sale_discount_price_adult = (int)$item->sale_price_adult - $item->discount_value;
                $item->sale_discount_price_teen = (int)$item->sale_price_teen - $item->discount_value;
                $item->sale_discount_price_children1 = (int)$item->sale_price_children1 - $item->discount_value;
                $item->sale_discount_price_children2 = (int)$item->sale_price_children2 - $item->discount_value;
                $item->sale_discount_price_infant = (int)$item->sale_price_infant - $item->discount_value;
            }
            $item->sale_discount_price_senior = round($item->sale_discount_price_senior > 0 ? $item->sale_discount_price_senior : 0);
            $item->sale_discount_price_adult = round($item->sale_discount_price_adult > 0 ? $item->sale_discount_price_adult : 0);
            $item->sale_discount_price_teen = round($item->sale_discount_price_teen > 0 ? $item->sale_discount_price_teen : 0);
            $item->sale_discount_price_children1 = round($item->sale_discount_price_children1 > 0 ? $item->sale_discount_price_children1 : 0);
            $item->sale_discount_price_children2 = round($item->sale_discount_price_children2 > 0 ? $item->sale_discount_price_children2 : 0);
            $item->sale_discount_price_infant = round($item->sale_discount_price_infant > 0 ? $item->sale_discount_price_infant : 0);
        }
        return $item;

    }

    public function getItems()
    {
        $debug=TSMUtility::get_debug();
        $input=JFactory::getApplication()->input;
        $items = parent::getItems(); // TODO: Change the autogenerated stub
        foreach ($items as $key => &$item) {
            $type = $item->mark_up_type;
            $tax = $item->tax / 100;
            if ($type == 'amount') {
                $price_senior = $item->price_senior + $item->mark_up_price_senior;
                $item->sale_price_senior = round($price_senior + $price_senior * $tax);
                $price_adult = $item->price_adult + (float)$item->mark_up_price_adult;
                $item->sale_price_adult = round($price_adult + $price_adult * $tax);
                $price_teen = $item->price_teen + $item->mark_up_price_teen;
                $item->sale_price_teen = round($price_teen + $price_teen * $tax);
                $price_infant = $item->price_infant + $item->mark_up_price_infant;
                $item->sale_price_infant = round($price_infant + $price_infant * $tax);
                $price_children1 = $item->price_children1 + $item->mark_up_price_children1;
                $item->sale_price_children1 = round($price_children1 + ($price_children1 * $item->tax) / 100);
                $price_children2 = $item->price_children2 + $item->mark_up_price_children2;
                $item->sale_price_children2 = round($price_children2 + $price_children2 * $tax);
                $price_private_room = $item->price_private_room + $item->mark_up_price_private_room;
                $item->sale_price_private_room = round($price_private_room + $price_private_room * $tax);
                $extra_bed = $item->extra_bed + $item->mark_up_extra_bed;
                $item->sale_price_extra_bed = round($extra_bed + $extra_bed * $tax);


            } else {
                $price_senior = $item->price_senior + $item->price_senior * $item->mark_up_senior / 100;
                $item->sale_price_senior = round($price_senior + $price_senior * $tax);
                $price_adult = $item->price_adult + $item->price_adult * ($item->mark_up_adult / 100);
                $item->sale_price_adult = round($price_adult + $price_adult * $tax);
                $price_teen = $item->price_teen + $item->price_teen * ($item->mark_up_teen / 100);
                $item->sale_price_teen = round($price_teen + $price_teen * $tax);
                $price_infant = $item->price_infant + $item->price_infant * ($item->mark_up_infant / 100);
                $item->sale_price_infant = round($price_infant + $price_infant * $tax);
                $price_children1 = $item->price_children1 + ($item->price_children1 * $item->mark_up_children1) / 100;
                $item->sale_price_children1 = $price_children1 + $price_children1 * $tax;
                $price_children2 = $item->price_children2 + $item->price_children2 * ($item->mark_up_children2 / 100);
                $item->sale_price_children2 = round($price_children2 + $price_children2 * $tax);
                $price_private_room = $item->price_private_room + $item->price_private_room * ($item->mark_up_private_room / 100);
                $item->sale_price_private_room = round($price_private_room + $price_private_room * $tax);
                $extra_bed = $item->extra_bed + $item->extra_bed * $item->mark_up_extra_bed / 100;
                $item->sale_price_extra_bed = round($extra_bed + $extra_bed * $tax);
            }
            $item->sale_min_price = round($item->sale_min_price + $item->sale_min_price * $tax);
            if ($item->tsmart_discount_id) {
                if ($item->discount_percent_or_total == 'percent') {
                    $item->sale_discount_price_senior = $item->sale_price_senior - ($item->discount_value * $item->sale_price_senior) / 100;
                    $item->sale_discount_price_adult = $item->sale_price_adult - ($item->discount_value * $item->sale_price_adult) / 100;
                    $item->sale_discount_price_teen = $item->sale_price_teen - ($item->discount_value * $item->sale_price_teen) / 100;
                    $item->sale_discount_price_children1 = $item->sale_price_children1 - ($item->discount_value * $item->sale_price_children1) / 100;
                    $item->sale_discount_price_children2 = $item->sale_price_children2 - ($item->discount_value * $item->sale_price_children2) / 100;
                    $item->sale_discount_price_infant = $item->sale_price_infant - ($item->discount_value * $item->sale_price_infant) / 100;
                    $item->sale_discount_min_price = $item->sale_min_price - ($item->discount_value * $item->sale_min_price) / 100;
                } else {
                    $item->sale_discount_price_senior = (int)$item->sale_price_senior - $item->discount_value;
                    $item->sale_discount_price_adult = (int)$item->sale_price_adult - $item->discount_value;
                    $item->sale_discount_price_teen = (int)$item->sale_price_teen - $item->discount_value;
                    $item->sale_discount_price_children1 = (int)$item->sale_price_children1 - $item->discount_value;
                    $item->sale_discount_price_children2 = (int)$item->sale_price_children2 - $item->discount_value;
                    $item->sale_discount_price_infant = (int)$item->sale_price_infant - $item->discount_value;
                    $item->sale_discount_min_price = (int)$item->sale_min_price - $item->discount_value;
                }
                $item->sale_discount_price_senior = $item->sale_discount_price_senior > 0 ? $item->sale_discount_price_senior : 0;
                $item->sale_discount_price_adult = $item->sale_discount_price_adult > 0 ? $item->sale_discount_price_adult : 0;
                $item->sale_discount_price_teen = $item->sale_discount_price_teen > 0 ? $item->sale_discount_price_teen : 0;
                $item->sale_discount_price_children1 = $item->sale_discount_price_children1 > 0 ? $item->sale_discount_price_children1 : 0;
                $item->sale_discount_price_children2 = $item->sale_discount_price_children2 > 0 ? $item->sale_discount_price_children2 : 0;
                $item->sale_discount_price_infant = $item->sale_discount_price_infant > 0 ? $item->sale_discount_price_infant : 0;
                $item->sale_discount_min_price = $item->sale_discount_min_price > 0 ? $item->sale_discount_min_price : 0;
            }
        }
        foreach ($items as $key => &$item) {
            $item->sale_min_price = min($item->sale_price_senior, $item->sale_price_adult, $item->sale_price_teen);

        }
        usort($items, function ($item1, $item2) {
            if ($item1->sale_min_price == $item2->sale_min_price) return 0;
            return $item1->sale_min_price < $item2->sale_min_price ? -1 : 1;
        });
        $tsmart_product_id = $input->getInt('tsmart_product_id', 0);

        $product_model = tmsModel::getModel('product');


        $product = $product_model->getItem($tsmart_product_id);
        if($product->price_type!="flat_price") {
            $list_service_class = array();
            foreach ($items as $key => &$item) {
                if (in_array($item->tsmart_service_class_id, $list_service_class)) {
                    unset($items[$key]);
                    continue;
                }
                array_push($list_service_class, $item->tsmart_service_class_id);
            }
        }
        $items = array_values($items);
        return $items;
    }

    public function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('site');
        // Load the filter state.
        $total_passenger_from_12_years_old = $this->getUserStateFromRequest($this->context . '.filter.total_passenger_from_12_years_old', 'filter_total_passenger_from_12_years_old', 0, 'int');
        $this->setState('filter.total_passenger_from_12_years_old', $total_passenger_from_12_years_old);
        $total_passenger_under_12_years_old = $this->getUserStateFromRequest($this->context . '.filter.total_passenger_under_12_years_old', 'filter_total_passenger_under_12_years_old', 0, 'int');
        $this->setState('filter.total_passenger_under_12_years_old', $total_passenger_under_12_years_old);
        $start_date = $this->getUserStateFromRequest($this->context . '.filter.start_date', 'filter_start_date', '', 'string');
        if (!$start_date) {
            $start_date = $this->getUserStateFromRequest($this->context . '.filter.start_date', 'modal_filter_start_date', '', 'string');
        }
        $this->setState('filter.start_date', $start_date);
        $month = $this->getUserStateFromRequest($this->context . '.filter.month', 'filter_month', '', 'string');
        $this->setState('filter.month', $month);

    }

    function getListQuery()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $tsmart_product_id = $input->getInt('tsmart_product_id', 0);
        $debug=TSMUtility::get_debug();
        $product_model = tmsModel::getModel('product');


        $product = $product_model->getItem($tsmart_product_id);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('tour_price.tsmart_price_id,service_class.tsmart_service_class_id,service_class.service_class_name,tour_price.tsmart_product_id')
            ->from('#__tsmart_service_class AS service_class')
            ->innerJoin('#__tsmart_tour_price AS tour_price ON tour_price.tsmart_service_class_id=service_class.tsmart_service_class_id')
            ->where('tour_price.tsmart_product_id=' . (int)$tsmart_product_id)
            ->leftJoin('#__tsmart_itinerary AS itinerary ON itinerary.tsmart_product_id=' . (int)$tsmart_product_id)
            ->select('count(distinct itinerary.tsmart_itinerary_id) AS total_day ')
            ->leftJoin('#__tsmart_cityarea AS cityarea ON cityarea.tsmart_cityarea_id=itinerary.tsmart_cityarea_id')
            ->leftJoin('#__tsmart_states AS states ON states.tsmart_state_id=cityarea.tsmart_state_id')
            ->leftJoin('#__tsmart_countries AS countries ON countries.tsmart_country_id=states.tsmart_country_id')
            ->select('GROUP_CONCAT(
                    CONCAT(states.state_name,",",countries.country_name) SEPARATOR ";"
            ) AS list_destination')
            ->select('
                group_size_id_tour_price_id.price_senior AS price_senior,
                group_size_id_tour_price_id.price_adult AS price_adult,
                group_size_id_tour_price_id.price_teen AS price_teen,
                group_size_id_tour_price_id.price_infant AS price_infant,
                group_size_id_tour_price_id.price_children1 AS price_children1,
                group_size_id_tour_price_id.price_children2 AS price_children2,
                group_size_id_tour_price_id.price_private_room AS price_private_room,
                group_size_id_tour_price_id.price_extra_bed AS price_extra_bed

            ')
            ->select('(@current_tax:=tour_price.tax/100) AS current_tax')
            ->select('(@profit_senior_price_percent:=(group_size_id_tour_price_id.price_senior+ group_size_id_tour_price_id.price_senior*(mark_up_tour_price_id.price_senior/100)  )) AS profit_senior_price_percent
            ,
             (@sale_senior_percent:=(@profit_senior_price_percent +@profit_senior_price_percent * @current_tax)) AS sale_senior_percent
            ')
            ->select('(@profit_senior_price_amount:=(group_size_id_tour_price_id.price_senior+ mark_up_tour_price_id.price_senior  )) AS profit_senior_price_amount
            ,
             (@sale_senior_amount:=(@profit_senior_price_amount +@profit_senior_price_amount * @current_tax)) AS sale_senior_amount
            ')
            ->select('(@profit_adult_price_percent:=(group_size_id_tour_price_id.price_adult+ group_size_id_tour_price_id.price_adult*(mark_up_tour_price_id.price_adult/100)  )) AS profit_adult_price_percent
            ,
             (@sale_adult_percent:=(@profit_adult_price_percent +@profit_adult_price_percent * @current_tax)) AS sale_adult_percent
            ')
            ->select('(@profit_adult_price_amount:=(group_size_id_tour_price_id.price_adult+ mark_up_tour_price_id.price_adult  )) AS profit_adult_price_amount
            ,
             (@sale_adult_amount:=(@profit_adult_price_amount +@profit_adult_price_amount * @current_tax)) AS sale_adult_amount
            ')
            ->select('(@profit_teen_price_percent:=(group_size_id_tour_price_id.price_teen+ group_size_id_tour_price_id.price_teen*(mark_up_tour_price_id.price_teen/100)  )) AS profit_teen_price_percent
            ,
             (@sale_teen_percent:=(@profit_teen_price_percent +@profit_teen_price_percent * @current_tax)) AS sale_teen_percent
            ')
            ->select('(@profit_teen_price_amount:=(group_size_id_tour_price_id.price_teen+ mark_up_tour_price_id.price_teen  )) AS profit_teen_price_amount
            ,
             (@sale_teen_amount:=(@profit_teen_price_amount +@profit_teen_price_amount * @current_tax)) AS sale_teen_amount
            ')
            ->select('@sale_senior1:=(IF(mark_up_tour_price_id.type = "percent", @sale_senior_percent,@sale_senior_amount  )) AS sale_senior1')
            ->select('@sale_adult1:=(IF(mark_up_tour_price_id.type = "percent", @sale_adult_percent,@sale_adult_amount  )) AS sale_adult1')
            ->select('@sale_teen1:=(IF(mark_up_tour_price_id.type = "percent", @sale_teen_percent,@sale_teen_amount  )) AS sale_teen1')
            ->select('@sale_min_price1:=LEAST(@sale_senior1,@sale_adult1,@sale_teen1) AS sale_min_price')
            ->where('group_size_id_tour_price_id.price_senior!=0 AND group_size_id_tour_price_id.price_adult!=0 AND group_size_id_tour_price_id.price_teen!=0');
        $now = JFactory::getDate();
        $query
            //->where('tour_price.sale_period_from<=' . $query->q($now->toSql()) . ' AND ' . $query->q($now->toSql()) . ' <=tour_price.sale_period_to')
            ->select('tour_price.tax')
            ->leftJoin('#__tsmart_products AS products ON products.tsmart_product_id=' . (int)$tsmart_product_id)
            ->select('products.published AS tour_state,products.tour_length AS tour_length')


        ;
        if($product->price_type=='flat_price') {
            $query->group('departure.tsmart_departure_id')
            ->order('departure.departure_date');
        }else{
            $query->group('group_size_id_tour_price_id.id')
            ->order('sale_min_price');
        }
        $start_date = $this->getState('filter.start_date');
        if($product->price_type=='flat_price') {
            $start_date=$this->getState('filter.month');
            if($start_date) {
                $start_date = explode('/', $start_date);
                $start_date = count($start_date)==2 ? "$start_date[0]/01/$start_date[1]" : '';
            }
        }
        $start_date = JFactory::getDate($start_date);
        if ($start_date) {
            $query->leftJoin('#__tsmart_date_availability AS date_availability ON date_availability.tsmart_service_class_id=tour_price.tsmart_service_class_id AND date_availability.tsmart_product_id=tour_price.tsmart_product_id');
            $query->select(' IF(date_availability.date =' . $query->quote($start_date->toSql()) . ' OR CURDATE()=' . $query->quote($start_date->toSql()) . ', 0, 1) as tour_state');
        }
        $query->innerJoin('#__tsmart_group_size_id_tour_price_id AS group_size_id_tour_price_id ON group_size_id_tour_price_id.tsmart_price_id=tour_price.tsmart_price_id');
        $query->leftJoin('#__tsmart_group_size AS group_size ON group_size.tsmart_group_size_id=group_size_id_tour_price_id.tsmart_group_size_id');
        $query->select('group_size.from AS group_from,group_size.to AS group_to');

        if($product->price_type=='flat_price') {
            if ($start_date) {
                $query->where('tour_price.sale_period_from>=' . $query->quote($start_date->toSql()) );
            }
        }
        else{
            if ($total_passenger_from_12_years_old = $this->getState('filter.total_passenger_from_12_years_old', 1)) {
                $query->where('(group_size.from<=' . (int)$total_passenger_from_12_years_old . ' AND group_size.to>=' . (int)$total_passenger_from_12_years_old . ')');
            }
            if ($start_date) {
                $query->where('(tour_price.sale_period_from<=' . $query->quote($start_date->toSql()) . ' AND tour_price.sale_period_to>=' . $query->quote($start_date->toSql()) . ')');
            }
        }
        $query->innerJoin('#__tsmart_mark_up_tour_price_id AS mark_up_tour_price_id ON mark_up_tour_price_id.tsmart_price_id=tour_price.tsmart_price_id')
            ->select('
            mark_up_tour_price_id.id AS id_mark_up_tour_price_id,
            mark_up_tour_price_id.price_senior AS mark_up_price_senior,
            mark_up_tour_price_id.price_adult AS mark_up_price_adult,
            mark_up_tour_price_id.price_teen AS mark_up_price_teen,
            mark_up_tour_price_id.price_infant AS mark_up_price_infant,
            mark_up_tour_price_id.price_children1 AS mark_up_price_children1,
            mark_up_tour_price_id.price_children2 AS mark_up_price_children2,
            mark_up_tour_price_id.price_private_room AS mark_up_price_private_room,
            mark_up_tour_price_id.price_extra_bed AS mark_up_price_extra_bed,
            mark_up_tour_price_id.senior AS mark_up_senior,
            mark_up_tour_price_id.adult AS mark_up_adult,
            mark_up_tour_price_id.teen AS mark_up_teen,
            mark_up_tour_price_id.children1 AS mark_up_children1,
            mark_up_tour_price_id.children2 AS mark_up_children2,
            mark_up_tour_price_id.infant AS mark_up_infant,
            mark_up_tour_price_id.private_room AS mark_up_private_room,
            mark_up_tour_price_id.extra_bed AS mark_up_extra_bed,
            mark_up_tour_price_id.type AS mark_up_type
            ');
        if ($start_date) {
            $now = JFactory::getDate($start_date);
        } else {
            $now = JFactory::getDate();
        }
        if($product->price_type=='flat_price') {
            $query->innerJoin('#__tsmart_departure AS departure ON departure.tsmart_product_id=tour_price.tsmart_product_id AND tour_price.tsmart_service_class_id=departure.tsmart_service_class_id AND departure.tsmart_departure_parent_id IS NOT NULL')
                ->where('tour_price.sale_period_from<=departure.departure_date AND tour_price.sale_period_to>=departure.departure_date')
                ->select('departure.tsmart_departure_id,departure.departure_date,departure.departure_code')
            ;
        }
        $query3 = $db->getQuery(true);
        $query3->clear();
        $query3->select('discount2.tsmart_discount_id')
            ->from('#__tsmart_discounts AS discount2')
            ->where('discount2.tsmart_product_id=' . (int)$tsmart_product_id . ' AND discount2.published=1 AND discount2.discount_start_date<' . $query->quote($now->toSql()) . ' AND discount2.discount_expiry_date>=' . $query->quote($now->toSql()))
            ->leftJoin('#__tsmart_discount_id_service_class_id AS discount_id_service_class_id ON discount2.tsmart_discount_id=discount_id_service_class_id.tsmart_discount_id')
            ->where('discount_id_service_class_id.tsmart_service_class_id=service_class.tsmart_service_class_id');
        $query->leftJoin('#__tsmart_discounts AS discount2 ON discount2.tsmart_discount_id=(' . $query3 . ')');
        $query->select('discount2.tsmart_discount_id,discount2.discount_name AS discount_name,discount2.percent_or_total AS discount_percent_or_total,discount2.discount_value AS discount_value')
        ;
        if($debug)
        {
            echo $query->dump();
        }
        return $query;
    }


}
// pure php no closing tag
