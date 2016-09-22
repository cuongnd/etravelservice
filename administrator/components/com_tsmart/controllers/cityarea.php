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
class TsmartControllercityarea extends VmController {

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
	function ajax_get_list_city_by_state_id()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$virtuemart_state_id=$input->get('virtuemart_state_id',0,'int');
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmcities.php';
		$list_state=vmcities::get_list_city_by_state_id($virtuemart_state_id);
		echo json_encode($list_state);
		jexit();
	}

}
// pure php no closing tag
