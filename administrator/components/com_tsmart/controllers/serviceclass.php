<?php
/**
*
* Currency controller
*
* @package	VirtueMart
* @subpackage Currency
* @author RickG
* @link http://www.tsmart.net
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

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Currency Controller
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG, Max Milbers, Patrick Kohl
 */
class TsmartControllerServiceclass extends TsmController {

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
