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
class TsmartControllerDeparture extends VmController {

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
        $virtuemart_product_id=$input->get('virtuemart_product_id',0,'int');
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmserviceclass.php';
		$list_service_class=vmServiceclass::get_list_service_class_by_tour_id($virtuemart_product_id);
		echo json_encode($list_service_class);
		die;

	}


	function ajax_get_departure_item()
	{
		$app=JFactory::getApplication();
		$departure_model=$this->getModel('departure');
		$input=$app->input;
        $virtuemart_departure_id=$input->getInt('virtuemart_departure_id',0);
        $item=$departure_model->getdeparture($virtuemart_departure_id);
        echo json_encode($item);
		die;
	}
	function ajax_save_departure_item()
	{
		$app=JFactory::getApplication();
		$departure_model=$this->getModel('departure');
		$data=$app->input->getArray();
		$min_max_space=$data['min_max_space'];
		$min_max_space=explode(';',$min_max_space);
		$data['min_space']=$min_max_space[0];
		$data['max_space']=$min_max_space[1];
        $data['allow_passenger']=implode(',',$data['allow_passenger']);
        $data['weekly']=implode(',',$data['weekly']);
        $days_seleted=$data['days_seleted'];
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $days_seleted = up_json_decode($days_seleted, false, 512, JSON_PARSE_JAVASCRIPT);
        $data['days_seleted']=implode(',',$days_seleted);
        if(!$data['virtuemart_departure_id'])
        {
            $data['departure_code']=JUserHelper::genRandomPassword();
        }
        $virtuemart_departure_id= $departure_model->store($data);
        if(!$virtuemart_departure_id)
        {
            echo $departure_model->getError();
            die;
        }

        $item=$departure_model->getdeparture($virtuemart_departure_id);
        if(!$item->virtuemart_departure_parent_id) {
            $departure_model->create_children_departure($virtuemart_departure_id);
        }
        echo json_encode($item);
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
