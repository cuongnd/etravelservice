<?php
/**
*
* State controller
*
* @package	tsmart
* @subpackage State
* @author RickG, Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: state.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Product Controller
 *
 * @package    tsmart
 * @subpackage State
 * @author RickG, Max Milbers
 */
class TsmartControllerState extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author RickG, Max Milbers
	 */
	function __construct() {
		parent::__construct('tsmart_state_id');

		$country = vRequest::getInt('tsmart_country_id', 0);
		$this->redirectPath .= ($country > 0) ? '&tsmart_country_id=' . $country : '';
	}


	/**
	 * Retrieve full statelist
	 */
	function getList() {
		$view = $this->getView('state', 'json');
		$view->display(null);
	}
	function ajax_get_list_state_by_country_id()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$tsmart_country_id=$input->get('tsmart_country_id',0,'int');
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmstates.php';
		$list_state=vmstates::get_list_state_by_country_id($tsmart_country_id);
		echo json_encode($list_state);
		jexit();
	}
}

