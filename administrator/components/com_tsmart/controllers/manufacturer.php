<?php
/**
*
* Manufacturer controller
*
* @package	tsmart
* @subpackage Manufacturer
* @author Patrick Kohl
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: manufacturer.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Manufacturer Controller
 *
 * @package    tsmart
 * @subpackage Manufacturer
 * @author
 *
 */
class TsmartControllerManufacturer extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct('tsmart_manufacturer_id');

	}

	/**
	 * Handle the save task
	 * Checks already in the controller the rights and sets the data by filtering the post
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		/* Load the data */
		$data = vRequest::getRequest();
		/* add the mf desc as html code */
		$data['mf_desc'] = vRequest::getHtml('mf_desc', '' );

		parent::save($data);
	}
}
// pure php no closing tag
