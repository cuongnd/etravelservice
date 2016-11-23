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
class TsmartControllerroom extends TsmController {

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

		$input=JFactory::getApplication()->input;
		$data=$input->getArray();
		$model = tmsModel::getModel($this->_cname);

		$id = $model->store($data);
		$tsmart_hotel_id=$data['tsmart_hotel_id'];
		$msg = 'failed';
		if(!empty($id)) {
			$msg = tsmText::sprintf('com_tsmart_STRING_SAVED',$this->mainLangKey);
			$type = 'message';
		}
		else $type = 'error';

		$redir = 'index.php?option=com_tsmart&view=room&tsmart_hotel_id='.$tsmart_hotel_id.'&key[tsmart_hotel_id]='.$tsmart_hotel_id;
		$this->setRedirect($redir, $msg,$type);
	}
	function ajax_get_list_room_by_hotel_id()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$tsmart_hotel_id=$input->get('tsmart_hotel_id',0,'int');
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmroom.php';
		$list_room=tsmroom::get_list_room_by_hotel_id($tsmart_hotel_id);
		echo json_encode($list_room);
		jexit();
	}


	function cancel($data = 0){

		$input=JFactory::getApplication()->input;
		$data=$input->getArray();
		$tsmart_hotel_id=$data['tsmart_hotel_id'];
		$msg = 'cancel';
		if(!empty($id)) {
			$msg = tsmText::sprintf('com_tsmart_STRING_SAVED',$this->mainLangKey);
			$type = 'message';
		}
		else $type = 'info';

		$redir = 'index.php?option=com_tsmart&view=room&tsmart_hotel_id='.$tsmart_hotel_id.'&key[tsmart_hotel_id]='.$tsmart_hotel_id;
		$this->setRedirect($redir, $msg,$type);
	}
}
// pure php no closing tag
