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
		$order_table->load($data['tsmart_order_id']);
		$response=new stdClass();
		$response->e=0;
		if(!$order_table->store())
		{
			$response->e=1;
			$response->m=$order_table->getError();
		}
		$order_object=(object)$order_table->getproperties();
		$response->r=$order_object;
		$order_data=json_decode($order_object->order_data);
		$list_passenger=$order_data->list_passenger;
		$build_room=(array)$order_data->build_room;
		$list_passenger=array_merge($list_passenger->senior_adult_teen,$list_passenger->children_infant);
		$list_row=array();
		if($order_data->modified_by){
			for ($i = 0, $n = count($list_passenger); $i < $n; $i++) {
				$row = $list_passenger[$i];
				$passenger_index = $row->passenger_index;
				$row->tour_fee = $row->tour_cost;
				$row->extra_fee =$row->extra_fee;

				$single_room_fee = $row->single_room_fee;
				$single_room_fee = $single_room_fee != "" && is_numeric($single_room_fee) && $single_room_fee > 0 ? $single_room_fee : 0;
				$row->single_room_fee = $single_room_fee;

				$row->total_cost = $row->tour_cost + $row->extra_fee + $row->single_room_fee;

				$discount_fee = $row->discount_fee;
				$discount_fee = $discount_fee != "" && is_numeric($discount_fee) && $discount_fee > 0 ? $discount_fee : 0;
				$row->discount_fee = $discount_fee;

				$payment = $row->payment;
				$payment = $payment != "" && is_numeric($payment) && $payment > 0 ? $payment : 0;
				$row->payment = $payment;

				$cancel_fee = $row->cancel_fee;
				$cancel_fee = $cancel_fee != "" && is_numeric($cancel_fee) && $cancel_fee > 0 ? $cancel_fee : 0;
				$row->cancel_fee = $cancel_fee;
				$list_row[]=$row;
			}

		}else{
			$departure=$order_data->departure;
			$single_room_fee=array();
			foreach($build_room as $item){
				$tour_cost_and_room_price=$item->tour_cost_and_room_price;
				foreach($tour_cost_and_room_price as $item_passenger){
					$total_cost=0;
					$passenger_index=$item_passenger->passenger_index;
					$total_cost+=$item_passenger->room_price;
					$single_room_fee[$passenger_index]=$total_cost;
				}

			}
			$list_extra_fee=array();
			foreach($build_room as $item){
				$tour_cost_and_room_price=$item->tour_cost_and_room_price;
				foreach($tour_cost_and_room_price as $item_passenger){
					$total_cost=0;
					$passenger_index=$item_passenger->passenger_index;
					$total_cost+=$item_passenger->extra_bed_price;
					$list_extra_fee[$passenger_index]=$total_cost;
				}

			}


			for ($i = 0, $n = count($list_passenger); $i < $n; $i++) {
				$row = $list_passenger[$i];
				$passenger_index=$row->passenger_index;
				$row->booking=$this->item->tsmart_order_id;
				$row->service_name=$this->tour->product_name;
				$tour_cost=$row->tour_cost;
				$passenger_per_extra_fee=(float)$list_extra_fee[$i];
				$tour_cost+=$passenger_per_extra_fee;

				$row->tour_fee=$row->tour_cost;
				$row->extra_fee=$passenger_per_extra_fee;


				$single_room_fee=$single_room_fee[$i];
				$single_room_fee=$single_room_fee!=""&&is_numeric($single_room_fee)&&$single_room_fee>0?$single_room_fee:0;
				$row->single_room_fee=$single_room_fee;

				$row->total_cost=$row->tour_cost+$row->extra_fee+$row->single_room_fee;

				$discount_fee=$row->discount_fee;
				$discount_fee=$discount_fee!=""&&is_numeric($discount_fee)&&$discount_fee>0?$discount_fee:0;
				$row->discount_fee=$discount_fee;

				$payment=$row->payment;
				$payment=$payment!=""&&is_numeric($payment)&&$payment>0?$payment:0;
				$row->payment=$payment;
				$row->balance=$row->total_cost-$row->payment;
				$cancel_fee=$row->cancel_fee;
				$cancel_fee=$cancel_fee!=""&&is_numeric($cancel_fee)&&$cancel_fee>0?$cancel_fee:0;
				$row->cancel_fee=$cancel_fee;
				$list_row[]=$row;
			}

		}
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
		$order_table=tsmTable::getInstance('orders','Table');
		$data=$app->input->getArray();
		$list_row=$data['list_row'];
		$order_table->load($data['tsmart_order_id']);
		$response=new stdClass();
		$response->e=0;
		if(!$order_table->store())
		{
			$response->e=1;
			$response->m=$order_table->getError();
		}
		$order_object=(object)$order_table->getproperties();
		$response->r=$order_object;
		$order_data=json_decode($order_object->order_data);
		$list_passenger=$order_data->list_passenger;
		$i=0;
		foreach($list_passenger->senior_adult_teen as &$item){
			$row=(object)$list_row[$i];
			$item->tour_cost=$row->tour_fee;
			$item->single_room_fee=$row->single_room_fee;
			$item->extra_fee=$row->extra_fee;
			$item->discount_fee=$row->discount_fee;
			$item->cancel_fee=$row->cancel_fee;
			$item->payment=$row->payment;
			$i++;
		}
		foreach($list_passenger->children_infant as &$item){
			$row=(object)$list_row[$i];
			$item->tour_cost=$row->tour_fee;
			$item->single_room_fee=$row->single_room_fee;
			$item->extra_fee=$row->extra_fee;
			$item->discount_fee=$row->discount_fee;
			$item->cancel_fee=$row->cancel_fee;
			$item->payment=$row->payment;
			$i++;
		}
		$user=JFactory::getUser();
		$order_data->list_passenger=$list_passenger;
		$order_data->modified_by=$user->id;
		$order_data=json_encode($order_data);
		$order_table->order_data=$order_data;
		if(!$order_table->store())
		{
			$response->e=1;
			$response->m=$order_table->getError();
		}
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
		$order_data=json_decode($order_object->order_data);
		$list_passenger=$order_data->list_passenger;
		$i=0;
		foreach($list_passenger->senior_adult_teen as &$item){
			$row=(object)$list_row[$i];
			$item->passenger_status=$row->passenger_status;
			$i++;
		}
		foreach($list_passenger->children_infant as &$item){
			$row=(object)$list_row[$i];
			$item->passenger_status=$row->passenger_status;
			$i++;
		}
		$user=JFactory::getUser();
		$departure_date=JFactory::getDate($data['departure_date']);
		$departure_date_end=JFactory::getDate($data['departure_date_end']);
		$order_data->list_passenger=$list_passenger;
		$order_data->departure->departure_date=$departure_date->toSql();
		$order_data->departure->departure_date_end=$departure_date_end->toSql();
		$order_data=json_encode($order_data);
		$order_table->order_data=$order_data;
		if(!$order_table->store())
		{
			$response->e=1;
			$response->m=$order_table->getError();
		}
		$total_passenger_confirm=0;
		$passenger_helper=TSMHelper::getHepler('passenger');
		foreach($list_passenger->senior_adult_teen as $passenger){
			if($passenger_helper->is_confirm($passenger)){
				$total_passenger_confirm++;
			}
		}
		foreach($list_passenger->children_infant as $passenger){
			if($passenger_helper->is_confirm($passenger)){
				$total_passenger_confirm++;
			}
		}
		$response->total_passenger=$total_passenger_confirm>1?JText::sprintf("%s pers",$total_passenger_confirm):JText::sprintf("%s per",$total_passenger_confirm);
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
