<?php
/**
*
* Currency controller
*
* @package	tsmart
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

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Currency Controller
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class TsmartControllerorders extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct();


	}

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$data = vRequest::getRequest();
		parent::save($data);
	}
	function ajax_save_order_assign_user()
	{
		$app=JFactory::getApplication();
		$order_table=tsmTable::getInstance('orders','Table');
		$data=$app->input->getArray();
		$order_table->load($data['tsmart_order_id']);
		$order_table->assign_user_id=$data['assign_user_id'];
		$response=new stdClass();
		$response->e=0;
		if(!$order_table->store())
		{
			$response->e=1;
			$response->m=$order_table->getError();
		}
		echo json_encode($response);
		die;
	}
	function ajax_get_order_detail_by_order_id()
	{
		$app=JFactory::getApplication();
		$order_table=tsmTable::getInstance('orders','Table');
		$data=$app->input->getArray();

		$tsmart_order_id=$data['tsmart_order_id'];
		$response=new stdClass();
		$response->e=0;
		$passenger_helper=TSMHelper::getHepler('passenger');

		$list_row=$passenger_helper->get_list_passenger_in_room_by_order_id($tsmart_order_id);
		foreach($list_row as &$passenger_in_room){
			$passenger_in_room->single_room_fee=$passenger_in_room->room_fee;
			$passenger_in_room->tour_fee=$passenger_in_room->tour_cost;
			$passenger_in_room->discount_fee=$passenger_in_room->discount;
			$passenger_in_room->total_cost = $passenger_in_room->tour_cost+$passenger_in_room->room_fee+$passenger_in_room->extra_fee-$passenger_in_room->discount;

			$passenger_in_room->balance = $passenger_in_room->total_cost-$passenger_in_room->payment;
			$passenger_in_room->refund = $passenger_in_room->payment-$passenger_in_room->cancel_fee;
			$passenger_in_room->passenger_status =$passenger_in_room->tour_tsmart_passenger_state_id;
		}
		$order_table=tsmTable::getInstance('orders','Table');

		$order_table->load($tsmart_order_id);
		$response=new stdClass();
		$response->e=0;
		$order_object=(object)$order_table->getproperties();
		$response->r=$order_object;
		$response->list_row=$list_row;
		echo json_encode($response);
		die;
	}
	function ajax_get_passenger_detail_by_passenger_id()
	{
		$app=JFactory::getApplication();
		$passenger_table=tsmTable::getInstance('passenger','Table');
		$data=$app->input->getArray();
		$passenger_table->load($data['tsmart_passenger_id']);
		$response=new stdClass();
		$response->e=0;
		$response->passenger_data=(object)$passenger_table->getProperties();
		echo json_encode($response);
		die;
	}
	function ajax_save_passenger_cost()
	{
		$app=JFactory::getApplication();
		$passenger_table=tsmTable::getInstance('passenger','Table');
		$data=$app->input->getArray();
		$list_row=$data['list_row'];
		$response=new stdClass();
		$response->e=0;
		foreach($list_row as $row){
			$passenger_table->load($row['tsmart_passenger_id']);
			$passenger_table->bind($row);
			if(!$passenger_table->store()){
				throw new Exception('save error: ' . $passenger_table->getError());
			}
		}
		$order_table=tsmTable::getInstance('orders','Table');
		$order_table->load($data['tsmart_order_id']);
		$order_object=(object)$order_table->getproperties();
		$response->r=$order_object;
		echo json_encode($response);
		die;
	}

	function ajax_save_passenger_detail_by_passenger_id(){
		$app=JFactory::getApplication();
		$data=(object)$app->input->getArray();
		$json_post=$data->json_post;
		$passenger_table=tsmTable::getInstance('passenger','Table');
		$passenger_table->bindChecknStore($json_post);
		$response=new stdClass();
		$response->error=0;
		$response->passenger_data=$passenger_table->getProperties();
		echo json_encode($response);
		die;
	}
	function ajax_save_order_info()
	{
		$app=JFactory::getApplication();
		$order_table=tsmTable::getInstance('orders','Table');
		$data=$app->input->getArray();
		$list_row=$data['list_row'];
		$order_table->load($data['tsmart_order_id']);
		$order_table->assign_user_id=$data['assign_user_id'];
		$order_table->terms_condition=$data['terms_condition'];
		$order_table->reservation_notes=$data['reservation_notes'];
		$order_table->itinerary=$data['itinerary'];
		$tsmart_orderstate_id=$data['tsmart_orderstate_id'];
		$order_table->tsmart_orderstate_id=$tsmart_orderstate_id>0?$tsmart_orderstate_id:null;
		$response=new stdClass();
		$response->e=0;
		if(!$order_table->store())
		{
			$response->e=1;
			$response->m=$order_table->getError();
		}
		$order_object=(object)$order_table->getproperties();
		$response->r=$order_object;

		$passenger_table=tsmTable::getInstance('passenger','Table');
		$tsmart_order_id=$order_object->tsmart_order_id;
		foreach($list_row as $row){
			$tsmart_passenger_id=$row['tsmart_passenger_id'];
			$passenger_table->load($tsmart_passenger_id);
			$passenger_table->tour_tsmart_passenger_state_id=$row['passenger_status'];
			if(!$passenger_table->store()){
				throw new Exception('save error: ' . $passenger_table->getError());
			}
		}



		$order_data=json_decode($order_object->order_data);
		$list_passenger=$order_data->list_passenger;
		$user=JFactory::getUser();
		$departure_date=JFactory::getDate($data['departure_date']);
		$departure_date_end=JFactory::getDate($data['departure_date_end']);
		$order_data->departure->departure_date=$departure_date->toSql();
		$order_data->departure->departure_date_end=$departure_date_end->toSql();
		$order_data=json_encode($order_data);
		$order_table->order_data=$order_data;
		if(!$order_table->store())
		{
			$response->e=1;
			$response->m=$order_table->getError();
		}
		$response->total_passenger=50;
		$departure=$order_data->departure;
		$tour=$order_data->tour;
		$response->service_date=JHtml::_('date', $departure_date, tsmConfig::$date_format)."<br/>".JHtml::_('date', $departure_date_end, tsmConfig::$date_format);
		$response->departure_date=JHtml::_('date', $departure_date, tsmConfig::$date_format);
		$response->assign_name=JFactory::getUser($order_table->assign_user_id)->name;
		echo json_encode($response);
		die;
	}

}
// pure php no closing tag
