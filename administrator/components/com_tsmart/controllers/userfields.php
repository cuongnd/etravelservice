<?php
/**
*
* Userfields controller
*
* @package	tsmart
* @subpackage Userfields
* @author Oscar van Eijk
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: userfields.php 8953 2015-08-19 10:30:52Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');


/**
 * Controller class for the Order status
 *
 * @package    tsmart
 * @subpackage Userfields
 * @author     Oscar van Eijk
 */
class TsmartControllerUserfields extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access public
	 * @author
	 */
	function __construct(){
		parent::__construct('tsmart_userfield_id');

	}

	function Userfields(){

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView('userfields', $viewType);

		parent::display();
	}
	function viewJson() {

		// Create the view object.
		$view = $this->getView('userfields', 'json');

		// Now display the view.
		$view->display(null);
	}

	function save($data = 0) {

		if($data===0) $data = vRequest::getPost();

		if(vmAccess::manager('raw')){
			$data['description'] = vRequest::get('description','');
			if(isset($data['params'])){
				$data['params'] = vRequest::get('params','');
			}
		} else {
			$data['description'] = vRequest::getHtml('description','');
			if(isset($data['params'])){
				$data['params'] = vRequest::getHtml('params','');
			}
		}
		$data['name'] = vRequest::getCmd('name');
		// onSaveCustom plugin;
		parent::save($data);
	}



}

//No Closing tag
