<?php 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
/**
*
* Base controller
*
* @package	tsmart
* @subpackage Core
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2011 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id$
*/

if (!class_exists( 'TsmController' )) require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');

/**
 * tsmart default administrator controller
 *
 * @package		tsmart
 */

class TsmartControllerTsmart extends TsmController {


	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 * Task for disabling dangerous database tools, used after install
	 * @author Max Milbers
	 */
	public function disableDangerousTools(){

		$data = vRequest::getRequest();
		$config = tmsModel::getInstance('config', 'tsmartModel');
		$config->setDangerousToolsOff();
		$this->display();
	}


	public function keepalive(){
		//echo 'alive';
		jExit();
	}

}
