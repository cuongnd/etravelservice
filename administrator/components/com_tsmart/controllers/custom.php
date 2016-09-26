<?php
/**
*
* custom controller
*
* @package	tsmart
* @subpackage
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: custom.php 3039 2011-04-14 22:37:04Z Electrocity $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Product Controller
 *
 * @package    tsmart
 * @author Max Milbers
 */
class TsmartControllerCustom extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct('tsmart_custom_id');

	}


	function viewJson() {

		// Create the view object.
		$view = $this->getView('custom', 'json');

		// Now display the view.
		$view->display(null);
	}

	function save($data = 0) {

		if($data===0)$data = vRequest::getPost();
		$data['custom_desc'] = vRequest::getHtml('custom_desc');
		$data['custom_value'] = vRequest::getHtml('custom_value');
		$data['layout_pos'] = vRequest::getCmd('layout_pos');
		if(isset($data['params'])){
			$data['params'] = vRequest::getHtml('params','');
		}
		// onSaveCustom plugin;
		parent::save($data);
	}

	/**
	* Clone a product
	*
	* @author Max Milbers
	*/
	public function createClone() {
		$mainframe = Jfactory::getApplication();

		/* Load the view object */
		$view = $this->getView('custom', 'html');

		$model = tmsModel::getModel('custom');
		$msgtype = '';
		$cids = vRequest::getInt($this->_cidName, vRequest::getInt('tsmart_custom_id'));

		foreach ($cids as $custom_id) {
			if ($model->createClone($custom_id)) $msg = tsmText::_('com_tsmart_CUSTOM_CLONED_SUCCESSFULLY');
			else {
				$msg = tsmText::_('com_tsmart_CUSTOM_NOT_CLONED_SUCCESSFULLY').' : '.$custom_id;
				$msgtype = 'error';
			}
		}
		$mainframe->redirect('index.php?option=com_tsmart&view=custom', $msg, $msgtype);
	}
}
// pure php no closing tag
