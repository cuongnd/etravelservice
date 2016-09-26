<?php
/**
*
* User controller
*
* @package	tsmart
* @subpackage User
* @author Oscar van Eijk
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: user.php 9021 2015-10-20 23:54:07Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Controller class for the user
 *
 * @package    	tsmart
 * @subpackage 	User
 * @author     	Oscar van Eijk
 * @author 		Max Milbers
 */
class TsmartControllerUser extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access public
	 * @author
	 */
	function __construct(){

		parent::__construct('tsmart_user_id');
	}

	/**
	 * Handle the edit task
	 */
	function edit($view=0){

		//We set here the tsmart_user_id, when no tsmart_user_id is set to 0, for adding a new user
		//In every other case the tsmart_user_id is sent.
		$cid = vRequest::getVar('tsmart_user_id');
		if(!isset($cid)) vRequest::setVar('tsmart_user_id', (int)0);

		parent::edit('edit');
	}

	function addST(){

		$this->edit();
	}

	function removeAddressST(){

		$tsmart_userinfo_id = vRequest::getInt('tsmart_userinfo_id');
		$tsmart_user_id = vRequest::getInt('tsmart_user_id');

		//Lets do it dirty for now
		$userModel = VmModel::getModel('user');
		vmdebug('removeAddressST',$tsmart_user_id,$tsmart_userinfo_id);
		$userModel->setId($tsmart_user_id[0]);
		$userModel->removeAddress($tsmart_userinfo_id);

		$layout = vRequest::getCmd('layout','edit');
		$this->setRedirect( 'index.php?option=com_tsmart&view=user&task=edit&tsmart_user_id[]='.$tsmart_user_id[0] );
	}

	function editshop(){

		$user = JFactory::getUser();
		//the tsmart_user_id var gets overriden in the edit function, when not set. So we must set it here
		vRequest::setVar('tsmart_user_id', (int)$user->id);
		$this->edit();

	}
	function cancel(){

		$lastTask = vRequest::getCmd('last_task');
		if ($lastTask == 'edit_shop') $this->setRedirect('index.php?option=com_tsmart');
		else $this->setRedirect('index.php?option=com_tsmart&view=user');
	}

	/**
	 * Handle the save task
	 * Checks already in the controller the rights todo so and sets the data by filtering the post
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView('user', $viewType);

		if (!vmAccess::manager('user.edit')) {
			$msg = tsmText::_('_NOT_AUTH');
		} else {
			$model = VmModel::getModel('user');

			if($data===0) $data = vRequest::getRequest();

			// Store multiple selectlist entries as a ; separated string
			if (array_key_exists('vendor_accepted_currencies', $data) && is_array($data['vendor_accepted_currencies'])) {
			    $data['vendor_accepted_currencies'] = implode(',', $data['vendor_accepted_currencies']);
			}
			// TODO disallow vendor_store_name as HTML ?
			$data['vendor_store_name'] = vRequest::getHtml('vendor_store_name');
			$data['vendor_store_desc'] = vRequest::getHtml('vendor_store_desc');
			$data['vendor_terms_of_service'] = vRequest::getHtml('vendor_terms_of_service');
			$data['vendor_legal_info'] = vRequest::getHtml('vendor_legal_info');
			$data['vendor_letter_css'] = vRequest::getHtml('vendor_letter_css');
			$data['vendor_letter_header_html'] = vRequest::getHtml('vendor_letter_header_html');
			$data['vendor_letter_footer_html'] = vRequest::getHtml('vendor_letter_footer_html');

			$ids = vRequest::getInt('tsmart_user_id');

			if($ids){
				if(is_array($ids) and isset($ids[0])){
					$model->setId((int)$ids[0]);
					vmdebug('my user controller set '.(int)$ids[0],$ids);
				} else{
					$model->setId((int)$ids);
					vmdebug('my user controller set '.(int)$ids,$ids);
				}
			}
			$ret=$model->store($data);
			if(!$ret){
				$msg = '';
			} else {
				$msg = $ret['message'];
			}

		}
		$cmd = vRequest::getCmd('task');
		$lastTask = vRequest::getCmd('last_task');
		if($cmd == 'apply'){
			if ($lastTask == 'editshop') $redirection = 'index.php?option=com_tsmart&view=user&task=editshop';
			else $redirection = 'index.php?option=com_tsmart&view=user&task=edit&tsmart_user_id[]='.$ret['newId'];
		} else {
			if ($lastTask == 'editshop') $redirection = 'index.php?option=com_tsmart';
			else $redirection = 'index.php?option=com_tsmart&view=user';
		}
// 		$this->setRedirect($redirection, $ret['message']);
		$this->setRedirect($redirection);
	}


}

//No Closing tag
