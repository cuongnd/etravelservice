<?php
/**
*
* Controller for the front end Manufacturerviews
*
* @package	tsmart
* @subpackage User
* @author Oscar van Eijk
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2014 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: manufacturer.php 2420 2010-06-01 21:12:57Z oscar $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * tsmart Component Controller
 *
 * @package		tsmart
 */
class TsmartControllerVendor extends JControllerLegacy
{

	/**
	* Send the ask question email.
	* @author Kohl Patrick, Christopher Roussel
	*/
	public function mailAskquestion () {

		vRequest::vmCheckToken();

		if(!class_exists('shopFunctionsF')) require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');

		$model = tmsModel::getModel('vendor');
		$mainframe = JFactory::getApplication();
		$vars = array();
		$min = tsmConfig::get('asks_minimum_comment_length', 50)+1;
		$max = tsmConfig::get('asks_maximum_comment_length', 2000)-1 ;
		$commentSize = vRequest::getString ('comment');
		if (function_exists('mb_strlen')) {
			$commentSize =  mb_strlen($commentSize);
		} else {
			$commentSize =  strlen($commentSize);
		}

		$validMail = filter_var(vRequest::getVar('email'), FILTER_VALIDATE_EMAIL);

		$tsmart_vendor_id = vRequest::getInt('tsmart_vendor_id',1);

		if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
		$userId = tsmartModelVendor::getUserIdByVendorId($tsmart_vendor_id);

		//$vendorUser = JFactory::getUser($userId);

		if ( $commentSize<$min || $commentSize>$max || !$validMail ) {
			$this->setRedirect(JRoute::_ ( 'index.php?option=com_tsmart&view=vendor&task=contact&tsmart_vendor_id=' . $tsmart_vendor_id , FALSE),tsmText::_('com_tsmart_COMMENT_NOT_VALID_JS'));
			return ;
		}

		$user = JFactory::getUser();

		$fromMail = vRequest::getVar('email');	//is sanitized then
		$fromName = vRequest::getVar('name','');//is sanitized then
		$fromMail = str_replace(array('\'','"',',','%','*','/','\\','?','^','`','{','}','|','~'),array(''),$fromMail);
		$fromName = str_replace(array('\'','"',',','%','*','/','\\','?','^','`','{','}','|','~'),array(''),$fromName);
		if (!empty($user->id)) {
			if(empty($fromMail)){
				$fromMail = $user->email;
			}
			if(empty($fromName)){
				$fromName = $user->name;
			}
		}

		$vars['user'] = array('name' => $fromName, 'email' => $fromMail);

		$VendorEmail = $model->getVendorEmail($tsmart_vendor_id);
		$vars['vendor'] = array('vendor_store_name' => $fromName );

		if (shopFunctionsF::renderMail('vendor', $VendorEmail, $vars,'vendor')) {
			$string = 'com_tsmart_MAIL_SEND_SUCCESSFULLY';
		}
		else {
			$string = 'com_tsmart_MAIL_NOT_SEND_SUCCESSFULLY';
		}
		$mainframe->enqueueMessage(tsmText::_($string));

		// Display it all
		$view = $this->getView('vendor', 'html');

		$view->setLayout('mail_confirmed');
		$view->display();
	}

}

// No closing tag
