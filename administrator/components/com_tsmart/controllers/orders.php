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
    function ajax_get_order_detail_and_night_hotel_detail_by_hotel_addon_id()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = $app->input->getArray();

        $tsmart_order_hotel_addon_id = $data['tsmart_order_hotel_addon_id'];
        $type = $data['type'];
        $response = new stdClass();
        $response->e = 0;
        $passenger_helper = TSMHelper::getHepler('passenger');

        $list_passenger = $passenger_helper->get_list_passenger_not_in_temporary_and_joint_hotel_addon_by_hotel_addon_id($tsmart_order_hotel_addon_id);
        foreach ($list_passenger as &$passenger_in_room) {
            $passenger_in_room->single_room_fee = $passenger_in_room->room_fee;
            $passenger_in_room->tour_fee = $passenger_in_room->tour_cost;
            $passenger_in_room->discount_fee = $passenger_in_room->discount;
            $passenger_in_room->total_cost = $passenger_in_room->tour_cost + $passenger_in_room->room_fee + $passenger_in_room->extra_fee - $passenger_in_room->discount;

            $passenger_in_room->balance = $passenger_in_room->total_cost - $passenger_in_room->payment;
            $passenger_in_room->refund = $passenger_in_room->payment - $passenger_in_room->cancel_fee;
            $passenger_in_room->passenger_status = $passenger_in_room->tour_tsmart_passenger_state_id;
        }
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select(
            'hotel.hotel_name,
            hotel_addon.tsmart_hotel_addon_id,
            hotel_addon_order.tsmart_order_hotel_addon_id,hotel_addon_order.terms_condition
            ,tsmart_order_id
            ,reservation_notes
            ,checkin_date
            ,checkout_date
        ')
            ->from('#__tsmart_hotel_addon AS hotel_addon')
            ->leftJoin('#__tsmart_hotel_addon_order AS hotel_addon_order ON hotel_addon_order.tsmart_hotel_addon_id=hotel_addon.tsmart_hotel_addon_id')
            ->leftJoin('#__tsmart_hotel AS hotel ON hotel.tsmart_hotel_id=hotel_addon.tsmart_hotel_id')
            ->where('hotel_addon_order.tsmart_order_hotel_addon_id='.(int)$tsmart_order_hotel_addon_id);
        $hotel_addon = $db->setQuery($query)->loadObject();
        $holtel_add_on_helper=TSMhelper::getHepler('hoteladdon');
        $data_price=$holtel_add_on_helper->get_data_price_by_hotel_add_on_id($hotel_addon->tsmart_hotel_addon_id); base64_decode($hotel_addon->data_price);
        $hotel_addon->data_price = $data_price;
        $order_table = tsmTable::getInstance('orders', 'Table');
        $order_table->jload($hotel_addon->tsmart_order_id);
        $response = new stdClass();
        $response->e = 0;
        $order_object = (object)$order_table->getproperties();
        $response->order_detail = $order_object;
        $response->hotel_addon_detail = $hotel_addon;
        $response->list_passenger = $list_passenger;
        echo json_encode($response);
        die;
    }
    function ajax_get_order_detail_and_night_hotel_detail_and_list_passenger_in_temporary_and_passenger_not_joint_hotel_addon_by_hotel_addon_id()
    {
        $app = JFactory::getApplication();
        $order_table = tsmTable::getInstance('orders', 'Table');
        $data = $app->input->getArray();

        $tsmart_order_hotel_addon_id = $data['tsmart_order_hotel_addon_id'];
        $type = $data['type'];
        $response = new stdClass();
        $response->e = 0;
        $passenger_helper = TSMHelper::getHepler('passenger');

        $list_passenger = $passenger_helper->get_list_passenger_in_temporary_and_passenger_not_joint_hotel_addon_by_hotel_addon_id($type);
        foreach ($list_passenger as &$passenger_in_room) {
            $passenger_in_room->single_room_fee = $passenger_in_room->room_fee;
            $passenger_in_room->tour_fee = $passenger_in_room->tour_cost;
            $passenger_in_room->discount_fee = $passenger_in_room->discount;
            $passenger_in_room->total_cost = $passenger_in_room->tour_cost + $passenger_in_room->room_fee + $passenger_in_room->extra_fee - $passenger_in_room->discount;

            $passenger_in_room->balance = $passenger_in_room->total_cost - $passenger_in_room->payment;
            $passenger_in_room->refund = $passenger_in_room->payment - $passenger_in_room->cancel_fee;
            $passenger_in_room->passenger_status = $passenger_in_room->tour_tsmart_passenger_state_id;
        }
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select(
            'hotel.hotel_name,
            hotel_addon.tsmart_hotel_addon_id,
            hotel_addon_order.tsmart_order_hotel_addon_id,hotel_addon_order.terms_condition
            ,tsmart_order_id
            ,reservation_notes
            ,checkin_date
            ,checkout_date
        ')
            ->from('#__tsmart_hotel_addon AS hotel_addon')
            ->leftJoin('#__tsmart_hotel_addon_order AS hotel_addon_order ON hotel_addon_order.tsmart_hotel_addon_id=hotel_addon.tsmart_hotel_addon_id')
            ->leftJoin('#__tsmart_hotel AS hotel ON hotel.tsmart_hotel_id=hotel_addon.tsmart_hotel_id')
            ->where('hotel_addon_order.tsmart_order_hotel_addon_id='.(int)$tsmart_order_hotel_addon_id);
        $hotel_addon = $db->setQuery($query)->loadObject();
        $holtel_add_on_helper=TSMhelper::getHepler('hoteladdon');
        $data_price=$holtel_add_on_helper->get_data_price_by_hotel_add_on_id($hotel_addon->tsmart_hotel_addon_id); base64_decode($hotel_addon->data_price);
        $hotel_addon->data_price = $data_price;
        $order_table = tsmTable::getInstance('orders', 'Table');
        $order_table->jload($hotel_addon->tsmart_order_id);
        $response = new stdClass();
        $response->e = 0;
        $order_object = (object)$order_table->getproperties();
        $response->order_detail = $order_object;
        $response->hotel_addon_detail = $hotel_addon;
        $response->list_passenger = $list_passenger;
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
