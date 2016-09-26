<?php
/**
*
* Config controller
*
* @package	tsmart
* @subpackage Config
* @auhtor Max Milbers
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2014 tsmart Team and authors. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: config.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Configuration Controller
 *
 * @package    tsmart
 * @subpackage Config
 */
class TsmartControllerConfig extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		VmConfig::loadJLang('com_tsmart_config');
		parent::__construct();

	}


	/**
	 * Handle the save task
	 */
	function save($data = 0){

		vRequest::vmCheckToken();
		$model = VmModel::getModel('config');

		$data = vRequest::getPost();

		if(strpos($data['offline_message'],'|')!==false){
			$data['offline_message'] = str_replace('|','',$data['offline_message']);
		}

		$msg = '';
		if ($model->store($data)) {
			$msg = tsmText::_('com_tsmart_CONFIG_SAVED');
			// Load the newly saved values into the session.
			VmConfig::loadConfig();
		}

		$redir = 'index.php?option=com_tsmart';
		if(vRequest::getCmd('task') == 'apply'){
			$redir = $this->redirectPath;
		}

		$this->setRedirect($redir, $msg);


	}


	/**
	 * Overwrite the remove task
	 * Removing config is forbidden.
	 * @author Max Milbers
	 */
	function remove(){

		$msg = tsmText::_('com_tsmart_ERROR_CONFIGS_COULD_NOT_BE_DELETED');

		$this->setRedirect( $this->redirectPath , $msg);
	}
}

//pure php no tag
