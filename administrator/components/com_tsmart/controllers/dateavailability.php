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
class TsmartControllerDateAvailability extends TsmController {

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
        $tsmart_product_id=$input->get('tsmart_product_id',0,'int');
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
		$list_service_class=vmServiceclass::get_list_service_class_by_tour_id($tsmart_product_id);
		echo json_encode($list_service_class);
		die;

	}


	function ajax_get_dateavailability_item()
	{
		$app=JFactory::getApplication();
		$dateavailability_model=$this->getModel('dateavailability');
		$input=$app->input;
		$tsmart_service_class_id=$input->getInt('tsmart_service_class_id',0);
		$tsmart_product_id=$input->getInt('tsmart_product_id',0);
        $item=$dateavailability_model->getdateavailability($tsmart_service_class_id,$tsmart_product_id);
        echo json_encode($item);
		die;
	}
	function ajax_save_dateavailability_item()
	{
		$app=JFactory::getApplication();
		$dateavailability_model=$this->getModel('dateavailability');
		$data=$app->input->getArray();
        $days_seleted=$data['days_seleted'];

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $days_seleted = up_json_decode($days_seleted, false, 512, JSON_PARSE_JAVASCRIPT);
		$tsmart_service_class_id=$data['tsmart_service_class_id'];
		$tsmart_product_id=$data['tsmart_product_id'];
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->delete('#__tsmart_date_availability')
			->where('tsmart_service_class_id='.(int)$tsmart_service_class_id.' AND tsmart_product_id='.(int)$tsmart_product_id);
		$db->setQuery($query);
		$ok=$db->execute();
		$response=new stdClass();
		$response->e=0;
		if(!$ok)
		{
			$response->e=1;
			throw new  Exception($db->getErrorMsg());
		}
		foreach($days_seleted AS $date)
		{
			$data1=$data;
			$data1['date']=$date;
			$tsmart_dateavailability_id= $dateavailability_model->store($data1);
			if(!$tsmart_dateavailability_id)
			{
				echo $dateavailability_model->getErrors();
			}
		}
		echo json_encode($response);
        die;
	}
	public function ajax_remove_item()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$tsmart_dateavailability_id=$input->get('tsmart_dateavailability_id',0,'int');
		$tour_id=$input->get('tour_id',0,'int');
		$model_dateavailability = VmModel::getModel('dateavailability');
		if(!$model_dateavailability->remove(array($tsmart_dateavailability_id)))
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
		$model_dateavailability = VmModel::getModel('dateavailability');
		if(!$model_dateavailability->toggle('published',null,'tsmart_dateavailability_id','dateavailability'))
		{
			echo 'cannot published item';
			die;
		}
		echo 1;
		die;

	}


}
// pure php no closing tag
