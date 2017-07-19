<?php
/**
 *
 * Currency controller
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8618 2014-12-10 22:45:48Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists('TsmController')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmcontroller.php');


/**
 * Currency Controller
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class TsmartControllerorders extends TsmController
{

    /**
     * Method to display the view
     *
     * @access    public
     * @author
     */
    function __construct()
    {
        parent::__construct();


    }

    /**
     * We want to allow html so we need to overwrite some request data
     *
     * @author Max Milbers
     */
    function save($data = 0)
    {

        $data = vRequest::getRequest();
        parent::save($data);
    }

    function ajax_save_order_assign_user()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = $app->input->getArray();
        $order_table->jload($data['tsmart_order_id']);
        $order_table->assign_user_id = $data['assign_user_id'];
        $response = new stdClass();
        $response->e = 0;
        if (!$order_table->store()) {
            $response->e = 1;
            $response->m = $order_table->getError();
        }
        echo json_encode($response);
        die;
    }

    function ajax_save_rooming()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $room_order_table = tsmTable::getInstance('room_order', 'Table');
        $data = (object)$app->input->getArray();
        $list_night_hotel = $data->list_night_hotel;
        $list_passenger = $data->list_passenger;
        $tsmart_order_id = $data->tsmart_order_id;
        $response = new stdClass();
        $list_night_hotel = JArrayHelper::toObject($list_night_hotel[0]);

        foreach ($list_night_hotel->list_room_type as $room_type => $list_room) {
            $room_order_table->tsmart_room_order_id = 0;
            $room_order_table->room_type = $room_type;
            $room_order_table->tsmart_order_id = $tsmart_order_id;
            if (!$room_order_table->store()) {
                throw new Exception('save error: ' . $room_order_table->getError());
            }
            $tsmart_room_order_id = $room_order_table->tsmart_room_order_id;
            $list_passenger_per_room = $list_room->list_passenger_per_room;
            foreach ($list_passenger_per_room as $passengers) {

                foreach ($passengers as $passenger_index) {
                    $passenger = $list_passenger[$passenger_index];
                    $tsmrt_passenger_id = $passenger['tsmart_passenger_id'];
                    $passenger_table->jload($tsmrt_passenger_id);
                    $passenger_table->tsmart_passenger_id = 0;
                    $passenger_table->created_on  =JFactory::getDate()->toSql();
                    $passenger_table->tsmart_parent_passenger_id = $tsmrt_passenger_id;
                    if (!$passenger_table->store()) {
                        throw new Exception('save error: ' . $passenger_table->getError());
                    }
                    $passenger_table->jload($tsmrt_passenger_id);
                    $passenger_table->tsmart_room_order_id = $tsmart_room_order_id;

                    if (!$passenger_table->store()) {
                        throw new Exception('save error: ' . $passenger_table->getError());
                    }


                }
            }
        }
        $passenger_helper = TSMHelper::getHepler('passenger');
        $tsmutility = TSMHelper::getHepler('utility');
        $list_passenger_not_in_temporary_and_not_in_room = $passenger_helper->get_list_passenger_not_in_temporary_and_not_in_room($tsmart_order_id);
        foreach ($list_passenger_not_in_temporary_and_not_in_room as &$passenger) {
            $passenger->year_old = $tsmutility->get_year_old_by_date($passenger->date_of_birth);
        }
        $response->e = 0;
        $response->list_passenger_not_in_temporary_and_not_in_room = $list_passenger_not_in_temporary_and_not_in_room;
        echo json_encode($response);
        die;
    }
    function ajax_save_passenger_cost_to_transfer()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $room_order_table = tsmTable::getInstance('room_order', 'Table');
        $data = (object)$app->input->getArray();
        $list_transfer_cost_per_passenger = $data->list_transfer_cost_per_passenger;
        $type = $data->type;
        $tsmart_order_transfer_addon_id = $data->tsmart_order_transfer_addon_id;
        $tsmart_order_id = $data->tsmart_order_id;
        $response = new stdClass();
        $list_transfer_cost_per_passenger = JArrayHelper::toObject($list_transfer_cost_per_passenger);
        foreach ($list_transfer_cost_per_passenger as $item) {

            $tsmart_passenger_id=$item->tsmart_passenger_id;
            $cost=$item->cost;
            $discount=$item->discount;
            $passenger_table->load($tsmart_passenger_id);
            $passenger_table->{$type.'_tsmart_order_transfer_addon_id'} = $tsmart_order_transfer_addon_id;
            $passenger_table->{$type.'_transfer_fee'} = $cost;
            $passenger_table->{$type.'_transfer_discount'} = $discount;
            if (!$passenger_table->store()) {
                throw new Exception('save error: ' . $room_order_table->getError());
            }
        }
        $response->e = 0;
        echo json_encode($response);
        die;
    }
    function ajax_save_passenger_cost_to_excursion()
    {
        $app = JFactory::getApplication();
        $excursion_addon_passenger_price_order_table = tsmTable::getInstance('excursion_addon_passenger_price_order', 'Table');
        $room_order_table = tsmTable::getInstance('room_order', 'Table');
        $data = (object)$app->input->getArray();
        $list_excursion_cost_per_passenger = $data->list_excursion_cost_per_passenger;
        $tsmart_order_excursion_addon_id = $data->tsmart_order_excursion_addon_id;
        $tsmart_order_id = $data->tsmart_order_id;
        $response = new stdClass();
        $list_excursion_cost_per_passenger = JArrayHelper::toObject($list_excursion_cost_per_passenger);
        foreach ($list_excursion_cost_per_passenger as $item) {

            $tsmart_passenger_id=$item->tsmart_passenger_id;
            $cost=$item->cost;
            $discount=$item->discount;
            $excursion_addon_passenger_price_order_table->jload(array(tsmart_order_excursion_addon_id=>$tsmart_order_excursion_addon_id,tsmart_passenger_id=>$tsmart_passenger_id));
            $excursion_addon_passenger_price_order_table->tsmart_order_excursion_addon_id = $tsmart_order_excursion_addon_id;
            $excursion_addon_passenger_price_order_table->tsmart_passenger_id = $tsmart_passenger_id;
            $excursion_addon_passenger_price_order_table->excursion_fee=$cost;
            $excursion_addon_passenger_price_order_table->excursion_discount=$discount;
            if (!$excursion_addon_passenger_price_order_table->store()) {
                throw new Exception('save error: ' . $room_order_table->getError());
            }
        }
        $response->e = 0;
        echo json_encode($response);
        die;
    }
    function change_status_passenger_in_hotel_add_on()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = (object)$app->input->getArray();
        $tsmart_passenger_id=$data->tsmart_passenger_id;
        $type=$data->type;
        $status=$data->status;
        $passenger_table->load($tsmart_passenger_id);
        $key=$type."_night_status";
        $response=new stdClass();
        $passenger_table->{$key}=(int)$status;
        if (!$passenger_table->store()) {
            throw new Exception('save error: ' . $passenger_table->getError());
        }
        $response->e = 0;
        echo json_encode($response);
        die;
    }
    function change_status_passenger_in_transfer_add_on()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = (object)$app->input->getArray();
        $tsmart_passenger_id=$data->tsmart_passenger_id;
        $type=$data->type;
        $status=$data->status;
        $passenger_table->load($tsmart_passenger_id);
        $key=$type."_transfer_status";
        $response=new stdClass();
        $passenger_table->{$key}=(int)$status;
        if (!$passenger_table->store()) {
            throw new Exception('save error: ' . $passenger_table->getError());
        }
        $response->e = 0;
        echo json_encode($response);
        die;
    }
    function change_status_group_hotel_add_on()
    {
        $app = JFactory::getApplication();
        $group_hotel_addon_order_table = tsmTable::getInstance('group_hotel_addon_order', 'Table');
        $data = (object)$app->input->getArray();
        $tsmart_group_hotel_addon_order_id=$data->tsmart_group_hotel_addon_order_id;
        $status=$data->status;
        $group_hotel_addon_order_table->load($tsmart_group_hotel_addon_order_id);
        $response=new stdClass();
        $group_hotel_addon_order_table->status=(int)$status;
        if (!$group_hotel_addon_order_table->store()) {
            throw new Exception('save error: ' . $group_hotel_addon_order_table->getError());
        }
        $response->e = 0;
        echo json_encode($response);
        die;
    }
    function ajax_save_group_hotel_add_on()
    {
        $app = JFactory::getApplication();
        $group_hotel_addon_order_table = tsmTable::getInstance('group_hotel_addon_order', 'Table');
        $data = (object)$app->input->getArray();

        $tsmart_group_hotel_addon_order_id=$data->tsmart_group_hotel_addon_order_id;
        $terms_condition=$data->terms_condition;
        $reservation_notes=$data->reservation_notes;
        $checkin_date=$data->checkin_date;
        $checkout_date=$data->checkout_date;
        $group_hotel_addon_order_table->load($tsmart_group_hotel_addon_order_id);
        $response=new stdClass();
        $group_hotel_addon_order_table->checkin_date=JFactory::getDate($checkin_date)->toSql();
        $group_hotel_addon_order_table->checkout_date=JFactory::getDate($checkout_date)->toSql();
        $group_hotel_addon_order_table->terms_condition=$terms_condition;
        $group_hotel_addon_order_table->reservation_notes=$reservation_notes;
        if (!$group_hotel_addon_order_table->store()) {
            throw new Exception('save error: ' . $group_hotel_addon_order_table->getError());
        }
        $list_assign_user_id_manager_hotel_add_on=$data->list_assign_user_id_manager_hotel_add_on;
        $db=JFactory::getDbo();
        $order_helper = TSMHelper::getHepler('orders');
        $query=$db->getQuery(true);
        $query->delete('#__tsmart_order_user_manager')
            ->where('object_id='.(int)$tsmart_group_hotel_addon_order_id)
            ->where('type='.$query->q($order_helper::TYPE_GROUP_HOTEL_ADD_ON_ORDER))
            ;
        $db->setQuery($query);
        if (!$db->execute()) {
            throw new Exception('save error: ' . $db->getError());
        }

        foreach($list_assign_user_id_manager_hotel_add_on as $user_id){
            $query->clear();
            $query->insert('#__tsmart_order_user_manager')
                ->set('type ='.$query->q($order_helper::TYPE_GROUP_HOTEL_ADD_ON_ORDER))
                ->set('user_id='.(int)$user_id)
                ->set('object_id='.(int)$tsmart_group_hotel_addon_order_id)
            ;
            $db->setQuery($query);
            if (!$db->execute()) {
                throw new Exception('save error: ' . $db->getError());
            }
        }
        $response->e = 0;
        echo json_encode($response);
        die;
    }
    function ajax_save_transfer_add_on()
    {
        $app = JFactory::getApplication();
        $transfer_addon_order_table = tsmTable::getInstance('transfer_addon_order', 'Table');
        $data = (object)$app->input->getArray();

        $tsmart_order_transfer_addon_id=$data->tsmart_order_transfer_addon_id;
        $terms_condition=$data->terms_condition;
        $reservation_notes=$data->reservation_notes;
        $checkin_date=$data->checkin_date;
        $transfer_addon_order_table->load($tsmart_order_transfer_addon_id);
        $response=new stdClass();
        $transfer_addon_order_table->checkin_date=JFactory::getDate($checkin_date)->toSql();
        $transfer_addon_order_table->terms_condition=$terms_condition;
        $transfer_addon_order_table->reservation_notes=$reservation_notes;
        if (!$transfer_addon_order_table->store()) {
            throw new Exception('save error: ' . $transfer_addon_order_table->getError());
        }
        $list_assign_user_id_manager_hotel_add_on=$data->list_assign_user_id_manager_hotel_add_on;
        $db=JFactory::getDbo();
        $order_helper = TSMHelper::getHepler('orders');
        $query=$db->getQuery(true);
        $query->delete('#__tsmart_order_user_manager')
            ->where('object_id='.(int)$tsmart_order_transfer_addon_id)
            ->where('type='.$query->q($order_helper::TYPE_GROUP_TRANSFER_ADD_ON_ORDER))
            ;
        $db->setQuery($query);
        if (!$db->execute()) {
            throw new Exception('save error: ' . $db->getError());
        }

        foreach($list_assign_user_id_manager_hotel_add_on as $user_id){
            $query->clear();
            $query->insert('#__tsmart_order_user_manager')
                ->set('type ='.$query->q($order_helper::TYPE_GROUP_TRANSFER_ADD_ON_ORDER))
                ->set('user_id='.(int)$user_id)
                ->set('object_id='.(int)$tsmart_order_transfer_addon_id)
            ;
            $db->setQuery($query);
            if (!$db->execute()) {
                throw new Exception('save error: ' . $db->getError());
            }
        }
        $response->e = 0;
        echo json_encode($response);
        die;
    }
    function ajax_save_rooming_hotel_add_on()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $hotel_addon_order_table = tsmTable::getInstance('hotel_addon_order', 'Table');
        $data = (object)$app->input->getArray();
        $list_night_hotel = $data->list_night_hotel;
        $tsmart_group_hotel_addon_order_id = $data->tsmart_group_hotel_addon_order_id;
        $list_passenger = $data->list_passenger;
        $tsmart_order_id = $data->tsmart_order_id;
        $tsmart_hotel_addon_id = $data->tsmart_hotel_addon_id;
        $type = $data->type;
        $response = new stdClass();
        $list_night_hotel = JArrayHelper::toObject($list_night_hotel[0]);
        foreach ($list_night_hotel->list_room_type as $room_type => $list_room) {
            $list_passenger_per_room = $list_room->list_passenger_per_room;
            foreach ($list_passenger_per_room as $passengers) {
                if(count($passengers)){
                    $hotel_addon_order_table->tsmart_order_hotel_addon_id=0;
                    $hotel_addon_order_table->tsmart_group_hotel_addon_order_id=$tsmart_group_hotel_addon_order_id;
                    $hotel_addon_order_table->tsmart_order_id=$tsmart_order_id;
                    $hotel_addon_order_table->type=$type;
                    $hotel_addon_order_table->room_type=$room_type;
                    $hotel_addon_order_table->tsmart_hotel_addon_id=$tsmart_hotel_addon_id;
                    if (!$hotel_addon_order_table->store()) {
                        throw new Exception('save error: ' . $hotel_addon_order_table->getError());
                    }
                    $tsmart_order_hotel_addon_id=$hotel_addon_order_table->tsmart_order_hotel_addon_id;
                }
                foreach ($passengers as $passenger_index) {
                    $passenger = (object)$list_passenger[$passenger_index];
                    unset($list_passenger[$passenger_index]);
                    $tsmrt_passenger_id = $passenger->tsmart_passenger_id;
                    $passenger_table->jload($tsmrt_passenger_id);
                    $key=$type."_tsmart_order_hotel_addon_id";
                    $passenger_table->{$key} = $tsmart_order_hotel_addon_id;
                    if (!$passenger_table->store()) {
                        throw new Exception('save error: ' . $passenger_table->getError());
                    }


                }
            }
        }
        foreach($list_passenger as $passenger){
            $passenger=(object)$passenger;
            if(isset($passenger->room_type)){
                $tsmrt_passenger_id = $passenger->tsmart_passenger_id;
                $passenger_table->jload($tsmrt_passenger_id);
                $key=$type."_tsmart_order_hotel_addon_id";
                $passenger_table->{$key} = null;
                if (!$passenger_table->store(true)) {
                    throw new Exception('save error: ' . $passenger_table->getError());
                }
            }
        }
        $response->e = 0;
        echo json_encode($response);
        die;
    }
    function save_passenger_room_in_hotel_add_on()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $hotel_addon_order_table = tsmTable::getInstance('hotel_addon_order', 'Table');
        $data = (object)$app->input->getArray();
        $type = $data->type;
        $tsmart_order_id = $data->tsmart_order_id;
        $tsmart_group_hotel_addon_order_id = $data->tsmart_group_hotel_addon_order_id;
        $tsmart_hotel_addon_id = $data->tsmart_hotel_addon_id;
        $group_passengers = $data->group_passengers;
        $response = new stdClass();

        foreach ($group_passengers as $group_passenger) {
            $group_passenger=(object)$group_passenger;
            $room_type=$group_passenger->room_type;
            $hotel_addon_order_table->tsmart_order_hotel_addon_id=0;
            $hotel_addon_order_table->tsmart_group_hotel_addon_order_id=$tsmart_group_hotel_addon_order_id;
            $hotel_addon_order_table->tsmart_order_id=$tsmart_order_id;
            $hotel_addon_order_table->tsmart_hotel_addon_id=$tsmart_hotel_addon_id;
            $hotel_addon_order_table->room_type=$room_type;
            if (!$hotel_addon_order_table->store()) {
                throw new Exception('save error: ' . $hotel_addon_order_table->getError());
            }
            $tsmart_order_hotel_addon_id=$hotel_addon_order_table->tsmart_order_hotel_addon_id;
            foreach($group_passenger->passengers as $passenger){
                $passenger=(object)$passenger;
                $tsmrt_passenger_id = $passenger->tsmart_passenger_id;
                $passenger_table->jload($tsmrt_passenger_id);
                $key=$type."_tsmart_order_hotel_addon_id";
                $key_price=$type."_night_hotel_fee";
                $passenger_table->{$key} = $tsmart_order_hotel_addon_id;
                $passenger_table->{$key_price} = $passenger->room_price;
                if (!$passenger_table->store()) {
                    throw new Exception('save error: ' . $passenger_table->getError());
                }
            }


        }

        echo json_encode($response);
        die;
    }

    function ajax_get_order_detail_by_order_id()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = $app->input->getArray();

        $tsmart_order_id = $data['tsmart_order_id'];
        $response = new stdClass();
        $response->e = 0;
        $passenger_helper = TSMHelper::getHepler('passenger');

        $list_row = $passenger_helper->get_list_passenger_not_in_temporary_by_order_id($tsmart_order_id);
        foreach ($list_row as &$passenger_in_room) {
            $passenger_in_room->single_room_fee = $passenger_in_room->room_fee;
            $passenger_in_room->tour_fee = $passenger_in_room->tour_cost;
            $passenger_in_room->discount_fee = $passenger_in_room->discount;
            $passenger_in_room->total_cost = $passenger_in_room->tour_cost + $passenger_in_room->room_fee + $passenger_in_room->extra_fee - $passenger_in_room->discount;

            $passenger_in_room->balance = $passenger_in_room->total_cost - $passenger_in_room->payment;
            $passenger_in_room->refund = $passenger_in_room->payment - $passenger_in_room->cancel_fee;
            $passenger_in_room->passenger_status = $passenger_in_room->tour_tsmart_passenger_state_id;
        }
        $order_table = tsmTable::getInstance('orders', 'Table');

        $order_table->jload($tsmart_order_id);
        $response = new stdClass();
        $response->e = 0;
        $order_object = (object)$order_table->getproperties();
        $response->r = $order_object;
        $response->list_row = $list_row;
        echo json_encode($response);
        die;
    }
    function ajax_remove_passenger_from_rooming_list(){
        $app = JFactory::getApplication();
        $hotel_addon_order_table = tsmTable::getInstance('hotel_addon_order', 'Table');
        $data = (object)$app->input->getArray();
        $tsmart_order_hotel_addon_id=$data->tsmart_order_hotel_addon_id;
        $tsmart_order_id=$data->tsmart_order_id;
        $type=$data->type;
        if (!$hotel_addon_order_table->delete($tsmart_order_hotel_addon_id)) {
            throw new Exception('save error: ' . $hotel_addon_order_table->getError());
        }
        $utility_helper = TSMHelper::getHepler('utility');
        $passenger_helper = TSMHelper::getHepler('passenger');
        $list_passenger_not_in_room = $passenger_helper->get_list_passenger_not_in_temporary_and_not_joint_hotel_addon_by_hotel_addon_id($type,$tsmart_order_id);
        foreach ($list_passenger_not_in_room as &$passenger_in_room) {
            $passenger_in_room->year_old=$utility_helper->get_year_old_by_date($passenger_in_room->date_of_birth);
        }
        $response = new stdClass();
        $response->list_passenger_not_in_room = $list_passenger_not_in_room;
        echo json_encode($response);
        die;
    }
    function ajax_get_order_detail_and_night_hotel_detail_by_hotel_addon_id()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = (object)$app->input->getArray();

        $tsmart_group_hotel_addon_order_id = $data->tsmart_group_hotel_addon_order_id;
        $tsmart_order_id = $data->tsmart_order_id;
        $type = $data->type;
        $response = new stdClass();
        $response->e = 0;
        $passenger_helper = TSMHelper::getHepler('passenger');
        $utility_helper = TSMHelper::getHepler('utility');
        $orders_helper = TSMHelper::getHepler('orders');
        $cities_helper = TSMHelper::getHepler('cities');
        $response->list_assign_user_id_manager_hotel_add_on=$orders_helper->get_list_assign_user_id_manager_hotel_add_on($tsmart_group_hotel_addon_order_id);
        $list_passenger = $passenger_helper->get_list_passenger_not_in_temporary_and_joint_hotel_addon_by_hotel_addon_id($tsmart_group_hotel_addon_order_id,$type);
        $key=$type."_night_hotel_fee";
        $list_rooming_single=array();
        $list_rooming_double=array();
        $list_rooming_twin=array();
        $list_rooming_triple=array();
        foreach ($list_passenger as &$passenger_in_room) {
            if($passenger_in_room->room_type=='single')
            {
                $list_rooming_single[$passenger_in_room->tsmart_order_hotel_addon_id][]=$passenger_in_room;
            }elseif($passenger_in_room->room_type=='double'){
                $list_rooming_double[$passenger_in_room->tsmart_order_hotel_addon_id][]=$passenger_in_room;
            }elseif($passenger_in_room->room_type=='twin'){
                $list_rooming_twin[$passenger_in_room->tsmart_order_hotel_addon_id][]=$passenger_in_room;
            }elseif($passenger_in_room->room_type=='triple'){
                $list_rooming_triple[$passenger_in_room->tsmart_order_hotel_addon_id][]=$passenger_in_room;
            }

        }
        $list_rooming=array(
            'single'=>$list_rooming_single,
            'double'=>$list_rooming_double,
            'twin'=>$list_rooming_twin,
            'triple'=>$list_rooming_triple,
        );
        $list_passenger_not_in_room = $passenger_helper->get_list_passenger_not_in_temporary_and_not_joint_hotel_addon_by_hotel_addon_id($type,$tsmart_order_id);
        foreach ($list_passenger_not_in_room as &$passenger_in_room) {
            $passenger_in_room->year_old=$utility_helper->get_year_old_by_date($passenger_in_room->date_of_birth);
        }

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select(
            'hotel.hotel_name,
            hotel.tsmart_cityarea_id,
            group_hotel_addon_order.tsmart_hotel_addon_id,
            group_hotel_addon_order.status AS group_status,
            hotel_addon_order.tsmart_order_hotel_addon_id,hotel_addon_order.terms_condition
            ,group_hotel_addon_order.tsmart_order_id
            ,group_hotel_addon_order.tsmart_group_hotel_addon_order_id
            ,group_hotel_addon_order.reservation_notes
            ,group_hotel_addon_order.terms_condition
            ,checkin_date
            ,checkout_date
        ')
            ->from('#__tsmart_hotel_addon AS hotel_addon')
            ->leftJoin('#__tsmart_group_hotel_addon_order AS group_hotel_addon_order ON group_hotel_addon_order.tsmart_hotel_addon_id=hotel_addon.tsmart_hotel_addon_id')
            ->where('group_hotel_addon_order.tsmart_group_hotel_addon_order_id='.(int)$tsmart_group_hotel_addon_order_id)
            ->leftJoin('#__tsmart_hotel_addon_order AS hotel_addon_order ON hotel_addon_order.tsmart_group_hotel_addon_order_id=group_hotel_addon_order.tsmart_group_hotel_addon_order_id')
            ->leftJoin('#__tsmart_hotel AS hotel ON hotel.tsmart_hotel_id=hotel_addon.tsmart_hotel_id')
            ->where('hotel_addon_order.tsmart_group_hotel_addon_order_id=group_hotel_addon_order.tsmart_group_hotel_addon_order_id');
        $hotel_addon = $db->setQuery($query)->loadObject();


        $date1=date_create($hotel_addon->checkout_date);
        $date2=date_create($hotel_addon->checkin_date);
        $total_night=date_diff($date1,$date2);
        $total_night=$total_night->days;
        $hotel_addon->total_night=$total_night;
        $hotel_addon->hotel_location=$cities_helper->get_path_city_state_country_by_city_id($hotel_addon->tsmart_cityarea_id);
        $hotel_addon->show_checkin_date=JHtml::_('date', $hotel_addon->checkin_date, tsmConfig::$date_format);
        $hotel_addon->show_checkout_date=JHtml::_('date', $hotel_addon->checkout_date, tsmConfig::$date_format);
        $hotel_addon->checkin_date=JFactory::getDate($hotel_addon->checkin_date)->format('d/M/y');
        $hotel_addon->checkout_date=JFactory::getDate($hotel_addon->checkout_date)->format('d/M/y');
        $holtel_add_on_helper=TSMhelper::getHepler('hoteladdon');
        $data_price=$holtel_add_on_helper->get_data_price_by_hotel_add_on_id($hotel_addon->tsmart_hotel_addon_id);
        $pre_total_cost=0;
        $pre_total_balance=0;
        $pre_total_payment=0;
        $pre_total_refund=0;
        $pre_total_cancel=0;

        $post_total_cost=0;
        $post_total_balance=0;
        $post_total_payment=0;
        $post_total_refund=0;
        $post_total_cancel=0;
        foreach ($list_passenger as &$passenger_in_room) {

            $passenger_in_room->unit_price_per_night=$passenger_helper->get_unit_price_per_night($passenger_in_room->tsmart_passenger_id,$data_price,$type);
            if($type=='pre') {
                $passenger_in_room->pre_night_total_cost = $passenger_in_room->unit_price_per_night * $total_night - $passenger_in_room->pre_night_discount;
                $passenger_in_room->pre_night_balance = $passenger_in_room->pre_night_total_cost - $passenger_in_room->pre_night_payment;
                $passenger_in_room->pre_night_refund = $passenger_in_room->pre_night_payment - $passenger_in_room->pre_night_cancel_fee;

                $pre_total_balance += $passenger_in_room->pre_night_balance;
                $pre_total_payment += $passenger_in_room->pre_night_payment;
                $pre_total_refund += $passenger_in_room->pre_night_refund;
                $pre_total_cancel += $passenger_in_room->pre_night_cancel_fee;
                $pre_total_cost += $passenger_in_room->pre_night_total_cost;
            }else {
                $passenger_in_room->post_night_total_cost = $passenger_in_room->unit_price_per_night * $total_night - $passenger_in_room->post_night_discount;
                $passenger_in_room->post_night_balance = $passenger_in_room->post_night_total_cost - $passenger_in_room->post_night_payment;
                $passenger_in_room->post_night_refund = $passenger_in_room->post_night_payment - $passenger_in_room->post_night_cancel_fee;

                $post_total_balance += $passenger_in_room->post_night_balance;
                $post_total_payment += $passenger_in_room->post_night_payment;
                $post_total_refund += $passenger_in_room->post_night_refund;
                $post_total_cancel += $passenger_in_room->post_night_cancel_fee;
                $post_total_cost += $passenger_in_room->post_night_total_cost;
            }


        }
        if($type=='pre') {
            $hotel_addon->pre_total_cost = $pre_total_cost;
            $hotel_addon->pre_total_balance = $pre_total_balance;
            $hotel_addon->pre_total_payment = $pre_total_payment;
            $hotel_addon->pre_total_refund = $pre_total_refund;
            $hotel_addon->pre_total_cancel = $pre_total_cancel;
        }else {
            $hotel_addon->post_total_cost = $post_total_cost;
            $hotel_addon->post_total_balance = $post_total_balance;
            $hotel_addon->post_total_payment = $post_total_payment;
            $hotel_addon->post_total_refund = $post_total_refund;
            $hotel_addon->post_total_cancel = $post_total_cancel;
        }

        $hotel_addon->data_price = $data_price;
        $order_table = tsmTable::getInstance('orders', 'Table');
        $order_table->jload($hotel_addon->tsmart_order_id);
        $response->e = 0;
        $order_object = (object)$order_table->getproperties();
        $response->order_detail = $order_object;
        $response->list_passenger_not_in_room = $list_passenger_not_in_room;
        $response->list_rooming = $list_rooming;
        $response->hotel_addon_detail = $hotel_addon;
        $response->list_passenger = $list_passenger;
        echo json_encode($response);
        die;
    }
    function ajax_get_order_detail_and_transfer_add_on_detail_by_tsmart_order_transfer_addon_id()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = (object)$app->input->getArray();

        $tsmart_order_transfer_addon_id = $data->tsmart_order_transfer_addon_id;
        $tsmart_order_id = $data->tsmart_order_id;
        $type = $data->type;
        $response = new stdClass();
        $response->e = 0;
        $passenger_helper = TSMHelper::getHepler('passenger');
        $utility_helper = TSMHelper::getHepler('utility');
        $orders_helper = TSMHelper::getHepler('orders');
        $cities_helper = TSMHelper::getHepler('cities');
        $response->list_assign_user_id_manager_transfer_add_on=$orders_helper->get_list_assign_user_id_manager_transfer_add_on_id($tsmart_order_transfer_addon_id);
        $list_passenger = $passenger_helper->get_list_passenger_not_in_temporary_and_joint_transfer_add_on_by_tsmart_order_transfer_addon_id($tsmart_order_transfer_addon_id,$type);

        $list_passenger_not_in_transfer = $passenger_helper->get_list_passenger_not_in_temporary_and_not_joint_transfer_by_tsmart_order_id($tsmart_order_id,$type);
        foreach ($list_passenger_not_in_transfer as &$passenger_in_transfer) {
            $passenger_in_transfer->year_old=$utility_helper->get_year_old_by_date($passenger_in_transfer->date_of_birth);
        }

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select(
            'transfer_addon.transfer_addon_name,
            transfer_addon_order.status AS group_status,
            transfer_addon_order.tsmart_order_id,
            transfer_addon.tsmart_transfer_addon_id,
            transfer_addon_order.tsmart_order_transfer_addon_id
            ,transfer_addon_order.reservation_notes
            ,transfer_addon_order.terms_condition
            ,checkin_date
        ')
            ->from('#__tsmart_transfer_addon AS transfer_addon')
            ->leftJoin('#__tsmart_transfer_addon_order AS transfer_addon_order ON transfer_addon_order.tsmart_transfer_addon_id=transfer_addon.tsmart_transfer_addon_id')
            ->where('transfer_addon_order.tsmart_order_transfer_addon_id='.(int)$tsmart_order_transfer_addon_id);
        $transfer_addon = $db->setQuery($query)->loadObject();

        $transfer_addon->transfer_location=$cities_helper->get_path_city_state_country_by_city_id($transfer_addon->tsmart_cityarea_id);
        $transfer_addon->show_checkin_date=JHtml::_('date', $transfer_addon->checkin_date, tsmConfig::$date_format);
        $transfer_addon->show_checkout_date=JHtml::_('date', $transfer_addon->checkout_date, tsmConfig::$date_format);
        $transfer_addon->checkin_date=JFactory::getDate($transfer_addon->checkin_date)->format('d/M/y');
        $transfer_addon->checkout_date=JFactory::getDate($transfer_addon->checkout_date)->format('d/M/y');
        $transfer_add_on_helper=TSMhelper::getHepler('transferaddon');
        $data_price=$transfer_add_on_helper->get_data_price_by_tsmart_transfer_addon_id($transfer_addon->tsmart_transfer_addon_id);
        $pre_total_cost=0;
        $pre_total_balance=0;
        $pre_total_payment=0;
        $pre_total_refund=0;
        $pre_total_cancel=0;

        $post_total_cost=0;
        $post_total_balance=0;
        $post_total_payment=0;
        $post_total_refund=0;
        $post_total_cancel=0;
        foreach ($list_passenger as &$passenger_in_transfer) {

            if($type=='pre') {
                $passenger_in_transfer->pre_transfer_total_cost = $passenger_in_transfer->pre_transfer_fee - $passenger_in_transfer->pre_transfer_discount;
                $passenger_in_transfer->pre_transfer_balance = $passenger_in_transfer->pre_transfer_total_cost - $passenger_in_transfer->pre_transfer_payment;
                $passenger_in_transfer->pre_transfer_refund = $passenger_in_transfer->pre_transfer_payment - $passenger_in_transfer->pre_transfer_cancel_fee;

                $pre_total_balance += $passenger_in_transfer->pre_transfer_balance;
                $pre_total_payment += $passenger_in_transfer->pre_transfer_payment;
                $pre_total_refund += $passenger_in_transfer->pre_transfer_refund;
                $pre_total_cancel += $passenger_in_transfer->pre_transfer_cancel_fee;
                $pre_total_cost += $passenger_in_transfer->pre_transfer_total_cost;
            }else {
                $passenger_in_transfer->post_transfer_total_cost = $passenger_in_transfer->post_transfer_fee - $passenger_in_transfer->post_transfer_discount;
                $passenger_in_transfer->post_transfer_balance = $passenger_in_transfer->post_transfer_total_cost - $passenger_in_transfer->post_transfer_payment;
                $passenger_in_transfer->post_transfer_refund = $passenger_in_transfer->post_transfer_payment - $passenger_in_transfer->post_transfer_cancel_fee;

                $post_total_balance += $passenger_in_transfer->post_transfer_balance;
                $post_total_payment += $passenger_in_transfer->post_transfer_payment;
                $post_total_refund += $passenger_in_transfer->post_transfer_refund;
                $post_total_cancel += $passenger_in_transfer->post_transfer_cancel_fee;
                $post_total_cost += $passenger_in_transfer->post_transfer_total_cost;
            }


        }
        if($type=='pre') {
            $transfer_addon->pre_total_cost = $pre_total_cost;
            $transfer_addon->pre_total_balance = $pre_total_balance;
            $transfer_addon->pre_total_payment = $pre_total_payment;
            $transfer_addon->pre_total_refund = $pre_total_refund;
            $transfer_addon->pre_total_cancel = $pre_total_cancel;
        }else {
            $transfer_addon->post_total_cost = $post_total_cost;
            $transfer_addon->post_total_balance = $post_total_balance;
            $transfer_addon->post_total_payment = $post_total_payment;
            $transfer_addon->post_total_refund = $post_total_refund;
            $transfer_addon->post_total_cancel = $post_total_cancel;
        }

        $transfer_addon->data_price = $data_price;
        $order_table = tsmTable::getInstance('orders', 'Table');
        $order_table->jload($transfer_addon->tsmart_order_id);
        $response->e = 0;
        $order_object = (object)$order_table->getproperties();
        $response->order_detail = $order_object;
        $response->list_passenger_not_in_transfer = $list_passenger_not_in_transfer;
        $response->transfer_addon = $transfer_addon;
        $response->list_passenger = $list_passenger;
        echo json_encode($response);
        die;
    }

    function ajax_get_order_detail_and_excurstion_add_on_detail_by_tsmart_order_excurstion_addon_id()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = (object)$app->input->getArray();

        $tsmart_order_excursion_addon_id = $data->tsmart_order_excursion_addon_id;
        $tsmart_order_id = $data->tsmart_order_id;
        $response = new stdClass();
        $response->e = 0;
        $passenger_helper = TSMHelper::getHepler('passenger');
        $utility_helper = TSMHelper::getHepler('utility');
        $orders_helper = TSMHelper::getHepler('orders');
        $cities_helper = TSMHelper::getHepler('cities');
        $response->list_assign_user_id_manager_excusion_add_on=$orders_helper->get_list_assign_user_id_manager_excusion_add_on_id($tsmart_order_excursion_addon_id);
        $list_passenger = $passenger_helper->get_list_passenger_not_in_temporary_and_joint_excursion_add_on_by_tsmart_order_excursion_addon_id($tsmart_order_excursion_addon_id,$tsmart_order_id);

        $list_passenger_not_in_excursion = $passenger_helper->get_list_passenger_not_in_temporary_and_not_joint_excursion_by_tsmart_order_id($tsmart_order_excursion_addon_id,$tsmart_order_id);
        foreach ($list_passenger_not_in_excursion as &$passenger_in_excursion) {
            $passenger_in_excursion->year_old=$utility_helper->get_year_old_by_date($passenger_in_excursion->date_of_birth);
        }

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select(
            'excursion_addon.excursion_addon_name,
            excursion_addon_order.status AS group_status,
            excursion_addon_order.tsmart_order_id,
            excursion_addon.tsmart_excursion_addon_id,
            excursion_addon_order.tsmart_order_excursion_addon_id
            ,excursion_addon_order.reservation_notes
            ,excursion_addon_order.terms_condition
            ,checkin_date
            ,checkout_date
        ')
            ->from('#__tsmart_excursion_addon AS excursion_addon')
            ->leftJoin('#__tsmart_excursion_addon_order AS excursion_addon_order ON excursion_addon_order.tsmart_excursion_addon_id=excursion_addon.tsmart_excursion_addon_id')
            ->where('excursion_addon_order.tsmart_order_excursion_addon_id='.(int)$tsmart_order_excursion_addon_id);
        $excursion_addon = $db->setQuery($query)->loadObject();

        $excursion_addon->excursion_location=$cities_helper->get_path_city_state_country_by_city_id($excursion_addon->tsmart_cityarea_id);
        $excursion_addon->show_checkin_date=JHtml::_('date', $excursion_addon->checkin_date, tsmConfig::$date_format);
        $excursion_addon->show_checkout_date=JHtml::_('date', $excursion_addon->checkout_date, tsmConfig::$date_format);
        $excursion_addon->checkin_date=JFactory::getDate($excursion_addon->checkin_date)->format('d/M/y');
        $excursion_addon->checkout_date=JFactory::getDate($excursion_addon->checkout_date)->format('d/M/y');

        $excursion_add_on_helper=TSMhelper::getHepler('excursionaddon');
        $data_price=$excursion_add_on_helper->get_data_price_by_tsmart_excursion_addon_id($excursion_addon->tsmart_excursion_addon_id);
        $excursion_total_cost=0;
        $excursion_total_balance=0;
        $excursion_total_payment=0;
        $excursion_total_refund=0;
        $excursion_total_cancel=0;

        foreach ($list_passenger as &$passenger_in_excursion) {

            $passenger_in_excursion->excursion_total_cost = $passenger_in_excursion->excursion_fee - $passenger_in_excursion->excursion_discount;
            $passenger_in_excursion->excursion_balance = $passenger_in_excursion->excursion_total_cost - $passenger_in_excursion->excursion_payment;
            $passenger_in_excursion->excursion_refund = $passenger_in_excursion->excursion_payment - $passenger_in_excursion->excursion_cancel_fee;

            $excursion_total_balance += $passenger_in_excursion->excursion_balance;
            $excursion_total_payment += $passenger_in_excursion->excursion_payment;
            $excursion_total_refund += $passenger_in_excursion->excursion_refund;
            $excursion_total_cancel += $passenger_in_excursion->excursion_cancel_fee;
            $excursion_total_cost += $passenger_in_excursion->excursion_total_cost;


        }
        $excursion_addon->excursion_total_cost = $excursion_total_cost;
        $excursion_addon->excursion_total_balance = $excursion_total_balance;
        $excursion_addon->excursion_total_payment = $excursion_total_payment;
        $excursion_addon->excursion_total_refund = $excursion_total_refund;
        $excursion_addon->excursion_total_cancel = $excursion_total_cancel;

        $excursion_addon->data_price = $data_price;
        $order_table = tsmTable::getInstance('orders', 'Table');
        $order_table->jload($excursion_addon->tsmart_order_id);
        $response->e = 0;
        $order_object = (object)$order_table->getproperties();
        $response->order_detail = $order_object;
        $response->list_passenger_not_in_excursion = $list_passenger_not_in_excursion;
        $response->excursion_addon = $excursion_addon;
        $response->list_passenger = $list_passenger;
        echo json_encode($response);
        die;
    }
    function ajax_get_order_detail_and_night_hotel_detail_and_list_passenger_in_temporary_and_passenger_not_joint_hotel_addon_by_hotel_addon_id()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = (object)$app->input->getArray();

        $tsmart_group_hotel_addon_order_id = $data->tsmart_group_hotel_addon_order_id;
        $type = $data->type;
        $tsmart_order_id = $data->tsmart_order_id;
        $response = new stdClass();
        $response->e = 0;
        $passenger_helper = TSMHelper::getHepler('passenger');
        $utility_helper = TSMHelper::getHepler('utility');
        $list_passenger_not_in_room = $passenger_helper->get_list_passenger_not_in_temporary_and_not_joint_hotel_addon_by_hotel_addon_id($type,$tsmart_order_id);
        foreach ($list_passenger_not_in_room as &$passenger_in_room) {
            $passenger_in_room->year_old=$utility_helper->get_year_old_by_date($passenger_in_room->date_of_birth);
        }



        $list_rooming_single=array();
        $list_rooming_double=array();
        $list_rooming_twin=array();
        $list_rooming_triple=array();
        $list_passenger = $passenger_helper->get_list_passenger_not_in_temporary_and_joint_hotel_addon_by_hotel_addon_id($tsmart_group_hotel_addon_order_id,$type);
        foreach ($list_passenger as &$passenger_in_room) {
            $passenger_in_room->single_room_fee = $passenger_in_room->room_fee;
            $passenger_in_room->tour_fee = $passenger_in_room->tour_cost;
            $passenger_in_room->discount_fee = $passenger_in_room->discount;
            $passenger_in_room->total_cost = $passenger_in_room->tour_cost + $passenger_in_room->room_fee + $passenger_in_room->extra_fee - $passenger_in_room->discount;

            $passenger_in_room->balance = $passenger_in_room->total_cost - $passenger_in_room->payment;
            $passenger_in_room->refund = $passenger_in_room->payment - $passenger_in_room->cancel_fee;
            $passenger_in_room->passenger_status = $passenger_in_room->tour_tsmart_passenger_state_id;
            $passenger_in_room->year_old=$utility_helper->get_year_old_by_date($passenger_in_room->date_of_birth);
            if($passenger_in_room->room_type=='single')
            {
                $list_rooming_single[$passenger_in_room->tsmart_order_hotel_addon_id][]=$passenger_in_room;
            }elseif($passenger_in_room->room_type=='double'){
                $list_rooming_double[$passenger_in_room->tsmart_order_hotel_addon_id][]=$passenger_in_room;
            }elseif($passenger_in_room->room_type=='twin'){
                $list_rooming_twin[$passenger_in_room->tsmart_order_hotel_addon_id][]=$passenger_in_room;
            }elseif($passenger_in_room->room_type=='triple'){
                $list_rooming_triple[$passenger_in_room->tsmart_order_hotel_addon_id][]=$passenger_in_room;
            }        }


        $list_rooming=array(
            'single'=>$list_rooming_single,
            'double'=>$list_rooming_double,
            'twin'=>$list_rooming_twin,
            'triple'=>$list_rooming_triple,
        );

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select(
            'hotel.hotel_name,
            hotel_addon.tsmart_hotel_addon_id,
            hotel_addon_order.tsmart_order_hotel_addon_id,group_hotel_addon_order.terms_condition
            ,group_hotel_addon_order.tsmart_order_id
            ,group_hotel_addon_order.reservation_notes
            ,checkin_date
            ,checkout_date
        ')
            ->from('#__tsmart_hotel_addon AS hotel_addon')
            ->leftJoin('#__tsmart_hotel_addon_order AS hotel_addon_order ON hotel_addon_order.tsmart_hotel_addon_id=hotel_addon.tsmart_hotel_addon_id')
            ->leftJoin('#__tsmart_hotel AS hotel ON hotel.tsmart_hotel_id=hotel_addon.tsmart_hotel_id')
            ->leftJoin('#__tsmart_group_hotel_addon_order AS group_hotel_addon_order ON group_hotel_addon_order.tsmart_group_hotel_addon_order_id=hotel_addon_order.tsmart_group_hotel_addon_order_id')
            ->where('group_hotel_addon_order.tsmart_group_hotel_addon_order_id='.(int)$tsmart_group_hotel_addon_order_id);
        $hotel_addon = $db->setQuery($query)->loadObject();
        $date1=date_create($hotel_addon->checkout_date);
        $date2=date_create($hotel_addon->checkin_date);
        $hotel_addon->total_night=date_diff($date1,$date2);
        $holtel_add_on_helper=TSMhelper::getHepler('hoteladdon');
        $data_price=$holtel_add_on_helper->get_data_price_by_hotel_add_on_id($hotel_addon->tsmart_hotel_addon_id); base64_decode($hotel_addon->data_price);
        $hotel_addon->data_price = $data_price;
        $order_table = tsmTable::getInstance('orders', 'Table');
        $order_table->jload($hotel_addon->tsmart_order_id);
        $response = new stdClass();
        $response->e = 0;
        $order_object = (object)$order_table->getproperties();
        $response->order_detail = $order_object;
        $response->list_passenger_not_in_room = $list_passenger_not_in_room;
        $response->hotel_addon_detail = $hotel_addon;
        $response->list_passenger = $list_passenger;
        $response->list_rooming = $list_rooming;
        echo json_encode($response);
        die;
    }

    function ajax_get_passenger_detail_by_passenger_id()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = $app->input->getArray();
        $passenger_table->jload($data['tsmart_passenger_id']);
        $response = new stdClass();
        $response->e = 0;
        $response->passenger_data = (object)$passenger_table->getProperties();
        echo json_encode($response);
        die;
    }

    function get_last_history_rooming()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = $app->input->getArray();
        $tsmart_order_id = $data['tsmart_order_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('passenger.tsmart_passenger_id,passenger.tsmart_room_order_id,passenger.tsmart_parent_passenger_id')
            ->from('#__tsmart_passenger AS passenger')
            ->leftJoin('#__tsmart_passenger AS passenger2 ON passenger2.tsmart_passenger_id=passenger.tsmart_parent_passenger_id')
            ->select('passenger2.title,passenger2.first_name,passenger2.middle_name,passenger2.last_name,passenger2.date_of_birth')
            ->where('passenger.tsmart_order_id=' . (int)$tsmart_order_id)
            ->where('passenger.tsmart_parent_passenger_id is not null')
            ->leftJoin('#__tsmart_room_order AS room_order ON room_order.tsmart_room_order_id=passenger.tsmart_room_order_id')
            ->select('room_order.room_type,room_order.created_on')
            ->group('passenger.tsmart_parent_passenger_id')
            ->order('passenger.created_on ASC')
        ;
        $list_passenger = $db->setQuery($query)->loadObjectList();
        $list_passenger=JArrayHelper::pivot($list_passenger,'tsmart_room_order_id');
        $response = new stdClass();
        $response->e = 0;
        $response->list_passenger = $list_passenger;
        echo json_encode($response);
        die;
    }
    function get_near_last_history_rooming()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = $app->input->getArray();
        $tsmart_order_id = $data['tsmart_order_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('passenger.tsmart_passenger_id,passenger.tsmart_room_order_id,passenger.tsmart_parent_passenger_id')
            ->from('#__tsmart_passenger AS passenger')
            ->leftJoin('#__tsmart_passenger AS passenger2 ON passenger2.tsmart_passenger_id=passenger.tsmart_parent_passenger_id')
            ->select('passenger2.title,passenger2.first_name,passenger2.middle_name,passenger2.last_name,passenger2.date_of_birth')
            ->where('passenger.tsmart_order_id=' . (int)$tsmart_order_id)
            ->where('passenger.tsmart_parent_passenger_id is not null')
            ->leftJoin('#__tsmart_room_order AS room_order ON room_order.tsmart_room_order_id=passenger.tsmart_room_order_id')
            ->select('room_order.room_type,room_order.created_on')
            ->group('passenger.tsmart_parent_passenger_id')
            ->order('passenger.created_on')
        ;
        $list_passenger = $db->setQuery($query)->loadObjectList();
        $list_passenger=JArrayHelper::pivot($list_passenger,'tsmart_room_order_id');
        $response = new stdClass();
        $response->e = 0;
        $response->list_passenger = $list_passenger;
        echo json_encode($response);
        die;
    }
    function get_first_history_rooming()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = $app->input->getArray();
        $tsmart_order_id = $data['tsmart_order_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('passenger.tsmart_passenger_id,passenger.tsmart_room_order_id,passenger.tsmart_parent_passenger_id')
            ->from('#__tsmart_passenger AS passenger')
            ->leftJoin('#__tsmart_passenger AS passenger2 ON passenger2.tsmart_passenger_id=passenger.tsmart_parent_passenger_id')
            ->select('passenger2.title,passenger2.first_name,passenger2.middle_name,passenger2.last_name,passenger2.date_of_birth')
            ->where('passenger.tsmart_order_id=' . (int)$tsmart_order_id)
            ->where('passenger.tsmart_parent_passenger_id is not null')
            ->leftJoin('#__tsmart_room_order AS room_order ON room_order.tsmart_room_order_id=passenger.tsmart_room_order_id')
            ->select('room_order.room_type,room_order.created_on')
            ->group('passenger.tsmart_parent_passenger_id')
            ->order('passenger.created_on')
        ;
        $list_passenger = $db->setQuery($query)->loadObjectList();
        $list_passenger=JArrayHelper::pivot($list_passenger,'tsmart_room_order_id');
        $response = new stdClass();
        $response->e = 0;
        $response->list_passenger = $list_passenger;
        echo json_encode($response);
        die;
    }

    function ajax_save_passenger_cost()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = $app->input->getArray();
        $list_row = $data['list_row'];
        $response = new stdClass();
        $response->e = 0;
        foreach ($list_row as $row) {
            $passenger_table->jload($row['tsmart_passenger_id']);
            $passenger_table->bind($row);
            if (!$passenger_table->store()) {
                throw new Exception('save error: ' . $passenger_table->getError());
            }
        }
        $order_table = tsmTable::getInstance('orders', 'Table');
        $order_table->jload($data['tsmart_order_id']);
        $order_object = (object)$order_table->getproperties();
        $response->r = $order_object;
        echo json_encode($response);
        die;
    }
    function ajax_save_passenger_cost_hotel_add_on()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = (object)$app->input->getArray();
        $list_row = $data->list_row;
        $type = $data->type;
        $response = new stdClass();
        foreach ($list_row as $row) {
            $row=(object)$row;
            $passenger_table->jload($row->tsmart_passenger_id);
            $passenger_table->{$type."_night_discount"}=$row->discount;
            $passenger_table->{$type."_night_payment"}=$row->payment;
            $passenger_table->{$type."_night_cancel_fee"}=$row->cancel_fee;
            if (!$passenger_table->store()) {
                throw new Exception('save error: ' . $passenger_table->getError());
            }
        }
        $response->e =0 ;
        echo json_encode($response);
        die;
    }
    function ajax_save_passenger_cost_excursion_add_on()
    {
        $app = JFactory::getApplication();
        $excursion_addon_passenger_price_order_table = tsmTable::getInstance('excursion_addon_passenger_price_order', 'Table');
        $data = (object)$app->input->getArray();
        $tsmart_order_excursion_addon_id = $data->tsmart_order_excursion_addon_id;
        $list_row = $data->list_row;
        $type = $data->type;
        $response = new stdClass();
        foreach ($list_row as $row) {
            $row=(object)$row;
            $excursion_addon_passenger_price_order_table->jload(array(tsmart_order_excursion_addon_id=>$tsmart_order_excursion_addon_id,tsmart_passenger_id=>$row->tsmart_passenger_id) );
            $excursion_addon_passenger_price_order_table->tsmart_order_excursion_addon_id=$tsmart_order_excursion_addon_id;
            $excursion_addon_passenger_price_order_table->tsmart_passenger_id=$row->tsmart_passenger_id;
            $excursion_addon_passenger_price_order_table->excursion_fee=$row->excursion_fee;
            $excursion_addon_passenger_price_order_table->excursion_discount=$row->excursion_discount;
            $excursion_addon_passenger_price_order_table->excursion_payment=$row->excursion_payment;
            $excursion_addon_passenger_price_order_table->excursion_cancel_fee=$row->excursion_cancel_fee;
            if (!$excursion_addon_passenger_price_order_table->store()) {
                throw new Exception('save error: ' . $excursion_addon_passenger_price_order_table->getError());
            }
        }
        $response->e =0 ;
        echo json_encode($response);
        die;
    }
    function ajax_save_passenger_cost_transfer_add_on()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = (object)$app->input->getArray();
        $list_row = $data->list_row;
        $type = $data->type;
        $response = new stdClass();
        foreach ($list_row as $row) {
            $row=(object)$row;
            $passenger_table->jload($row->tsmart_passenger_id);
            $passenger_table->{$type."_transfer_discount"}=$row->discount;
            $passenger_table->{$type."_transfer_payment"}=$row->payment;
            $passenger_table->{$type."_transfer_cancel_fee"}=$row->cancel_fee;
            if (!$passenger_table->store()) {
                throw new Exception('save error: ' . $passenger_table->getError());
            }
        }
        $response->e =0 ;
        echo json_encode($response);
        die;
    }

    function ajax_add_passenger_to_room()
    {
        $app = JFactory::getApplication();
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $data = $app->input->getArray();
        $list_row = $data['list_row'];
        $response = new stdClass();
        $response->e = 0;
        foreach ($list_row as $row) {
            $passenger_table->jload($row['tsmart_passenger_id']);
            $passenger_table->bind($row);
            $passenger_table->discount = $row['passenger_discount'];
            $passenger_table->extra_fee = $row['passenger_extra_fee'];
            $passenger_table->is_temporary = 0;
            if (!$passenger_table->store()) {
                throw new Exception('save error: ' . $passenger_table->getError());
            }
        }
        $order_table = tsmTable::getInstance('orders', 'Table');
        $order_table->jload($data['tsmart_order_id']);
        $order_object = (object)$order_table->getproperties();
        $response->r = $order_object;
        echo json_encode($response);
        die;
    }

    function ajax_save_passenger_detail_by_passenger_id()
    {
        $app = JFactory::getApplication();
        $data = (object)$app->input->getArray();
        $json_post = $data->json_post;
        if (!$json_post['tsmart_passenger_id']) {
            $json_post['is_temporary'] = 1;
        }
        $date_of_birth = $json_post['date_of_birth'];
        $date_of_birth = JFactory::getDate($date_of_birth)->toSql();
        $json_post['date_of_birth'] = $date_of_birth;

        $issue_date = $json_post['issue_date'];
        $issue_date = JFactory::getDate($issue_date)->toSql();
        $json_post['issue_date'] = $issue_date;

        $expiry_date = $json_post['expiry_date'];
        $expiry_date = JFactory::getDate($expiry_date)->toSql();
        $json_post['expiry_date'] = $expiry_date;

        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $passenger_table->bindChecknStore($json_post);
        $response = new stdClass();
        $response->error = 0;
        $response->passenger_data = $passenger_table->getProperties();
        echo json_encode($response);
        die;
    }

    function ajax_delete_passenger_by_passenger_id()
    {
        $app = JFactory::getApplication();
        $data = (object)$app->input->getArray();
        $tsmart_passenger_id = $data->tsmart_passenger_id;
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $passenger_table->delete($tsmart_passenger_id);
        $response = new stdClass();
        $response->error = 0;
        echo json_encode($response);
        die;
    }

    function ajax_delete_rooming()
    {
        $app = JFactory::getApplication();
        $data = (object)$app->input->getArray();
        $tsmart_room_order_id = $data->tsmart_room_order_id;
        $tsmart_order_id = $data->tsmart_order_id;
        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $passenger_helper = TSMHelper::getHepler('passenger');
        $passengers = $passenger_helper->get_list_passenger_by_room_oder_id($tsmart_room_order_id);
        foreach ($passengers as $passenger) {
            $tsmrt_passenger_id = $passenger->tsmart_passenger_id;
            $passenger_table->jload($tsmrt_passenger_id);
            $passenger_table->tsmart_passenger_id = 0;
            $passenger_table->created_on  =JFactory::getDate()->toSql();
            $passenger_table->tsmart_parent_passenger_id = $tsmrt_passenger_id;
            if (!$passenger_table->store()) {
                throw new Exception('save error: ' . $passenger_table->getError());
            }
            $passenger_table->jload($tsmrt_passenger_id);
            $passenger_table->tsmart_room_order_id = null;

            if (!$passenger_table->store(true)) {
                throw new Exception('save error: ' . $passenger_table->getError());
            }
        }
        $response = new stdClass();
        $response->error = 0;

        $tsmutility = TSMHelper::getHepler('utility');
        $list_passenger_not_in_temporary_and_not_in_room = $passenger_helper->get_list_passenger_not_in_temporary_and_not_in_room($tsmart_order_id);
        foreach ($list_passenger_not_in_temporary_and_not_in_room as &$passenger) {
            $passenger->year_old = $tsmutility->get_year_old_by_date($passenger->date_of_birth);
        }
        $list_passenger_not_in_temporary_and_not_in_room = JArrayHelper::pivot($list_passenger_not_in_temporary_and_not_in_room, 'tsmart_passenger_id');
        $response->list_passenger_not_in_temporary_and_not_in_room = $list_passenger_not_in_temporary_and_not_in_room;
        echo json_encode($response);
        die;
    }

    function ajax_save_order_info()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = $app->input->getArray();
        $list_row = $data['list_row'];
        $order_table->jload($data['tsmart_order_id']);
        $order_table->assign_user_id = $data['assign_user_id'];
        $order_table->terms_condition = $data['terms_condition'];
        $order_table->reservation_notes = $data['reservation_notes'];
        $order_table->itinerary = $data['itinerary'];
        $tsmart_orderstate_id = $data['tsmart_orderstate_id'];
        $order_table->tsmart_orderstate_id = $tsmart_orderstate_id > 0 ? $tsmart_orderstate_id : null;
        $response = new stdClass();
        $response->e = 0;
        if (!$order_table->store()) {
            $response->e = 1;
            $response->m = $order_table->getError();
        }
        $order_object = (object)$order_table->getproperties();
        $response->r = $order_object;

        $passenger_table = tsmTable::getInstance('passenger', 'Table');
        $tsmart_order_id = $order_object->tsmart_order_id;
        foreach ($list_row as $row) {
            $tsmart_passenger_id = $row['tsmart_passenger_id'];
            $passenger_table->jload($tsmart_passenger_id);
            $passenger_table->tour_tsmart_passenger_state_id = $row['passenger_status'];
            if (!$passenger_table->store()) {
                throw new Exception('save error: ' . $passenger_table->getError());
            }
        }


        $order_data = json_decode($order_object->order_data);
        $list_passenger = $order_data->list_passenger;
        $user = JFactory::getUser();
        $departure_date = JFactory::getDate($data['departure_date']);
        $departure_date_end = JFactory::getDate($data['departure_date_end']);
        $order_data->departure->departure_date = $departure_date->toSql();
        $order_data->departure->departure_date_end = $departure_date_end->toSql();
        $order_data = json_encode($order_data);
        $order_table->order_data = $order_data;
        if (!$order_table->store()) {
            $response->e = 1;
            $response->m = $order_table->getError();
        }
        $response->total_passenger = 50;
        $departure = $order_data->departure;
        $tour = $order_data->tour;
        $response->service_date = JHtml::_('date', $departure_date, tsmConfig::$date_format) . "<br/>" . JHtml::_('date', $departure_date_end, tsmConfig::$date_format);
        $response->departure_date = JHtml::_('date', $departure_date, tsmConfig::$date_format);
        $response->assign_name = JFactory::getUser($order_table->assign_user_id)->name;
        echo json_encode($response);
        die;
    }

}
// pure php no closing tag
