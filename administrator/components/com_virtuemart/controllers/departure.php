<?php
/**
*
* Currency controller
*
* @package	VirtueMart
* @subpackage Currency
* @author RickG
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: currency.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Currency Controller
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class VirtuemartControllerDeparture extends VmController {

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

		$data['currency_positive_style'] = vRequest::getHtml('currency_positive_style','');
		$data['currency_negative_style'] = vRequest::getHtml('currency_negative_style','');

		parent::save($data);
	}
	function ajax_get_list_service_class_by_tour_id()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$tour_id=$input->get('tour_id',0,'int');
		require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmserviceclass.php';
		$virtuemart_service_class_ids=vmServiceclass::get_list_service_class_ids_by_tour_id($tour_id);
		echo json_encode($virtuemart_service_class_ids);
		die;

	}


	function ajax_get_departure_item()
	{
		$app=JFactory::getApplication();
		$model=$this->getModel('departure');
		$input=$app->input;
		$list_departure_available2=$model->get_departure_item();
		$object_price_by_first_date=reset($list_departure_available2);
		$time_stemp_time_object_price_first_date=JFactory::getDate($object_price_by_first_date->date_select)->getTimestamp();
		foreach($list_departure_available2 as $key=>$value)
		{
			$time_stamp_date_select_by_value=$value->date_select;
			if($time_stamp_date_select_by_value<$time_stemp_time_object_price_first_date)
			{
				$object_price_by_first_date=$value;
				$time_stemp_time_object_price_first_date=$time_stamp_date_select_by_value;
			}
		}
		echo json_encode($object_price_by_first_date);
		die;
	}
	function ajax_save_departure_item()
	{
		$app=JFactory::getApplication();
		$model=$this->getModel('departure');
		$data=$app->input->getArray();
		$min_max_space=$data['min_max_space'];
		$min_max_space=explode(';',$min_max_space);
		$data['min_space']=$min_max_space[0];
		$data['max_space']=$min_max_space[1];
		$daterange_vail_period_from_to=$data['daterange_vail_period_from_to'];
		$daterange_vail_period_from_to=explode('-',$daterange_vail_period_from_to);

		$data['vail_period_from']=$daterange_vail_period_from_to[0];
		$data['vail_period_to']=$daterange_vail_period_from_to[1];


		$list_departure_available2=$model->save_departure_item($data);
		$response=new stdClass();
		$response->e=0;
		if(!$list_departure_available2)
		{
			$app = JFactory::getApplication();
			$response->e=1;
			$response->m=$app->getMessageQueue();
			echo json_encode($response);
			die;
		}
		echo json_encode($list_departure_available2);
		die;
	}
	public function ajax_remove_item()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$virtuemart_departure_id=$input->get('virtuemart_departure_id',0,'int');
		$tour_id=$input->get('tour_id',0,'int');
		$model_departure = VmModel::getModel('departure');
		if(!$model_departure->remove(array($virtuemart_departure_id)))
		{
			echo 'cannot delete item';
			die;
		}
		echo 1;
		die;

	}
	public function ajax_publish_item()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$model_departure = VmModel::getModel('departure');
		if(!$model_departure->toggle('published',null,'virtuemart_departure_id','departure'))
		{
			echo 'cannot published item';
			die;
		}
		echo 1;
		die;

	}


}
// pure php no closing tag
