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

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Currency Controller
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class TsmartControllerdeparture extends TsmController {

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
	function ajax_get_departure_item()
	{
		$app=JFactory::getApplication();
		$model=$this->getModel('departure');
		$input=$app->input;
/*		echo "<pre>";
		print_r($input->getArray());
		echo "</pre>";*/
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
		$input=$app->input;
/*		echo "<pre>";
		print_r($input->getArray());
		echo "</pre>";*/
		$list_departure_available2=$model->save_departure_item();
		echo json_encode($list_departure_available2);
		die;
	}
}
// pure php no closing tag
