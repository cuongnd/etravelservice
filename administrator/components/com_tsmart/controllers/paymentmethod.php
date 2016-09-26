<?php
/**
*
* Calc controller
*
* @package	tsmart
* @subpackage Calc
* @author Max Milbers, jseros
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: paymentmethod.php 8953 2015-08-19 10:30:52Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmController.php');


/**
 * Calculator Controller
 *
 * @package    tsmart
 * @subpackage Calculation tool
 * @author Max Milbers
 */
class TsmartControllerPaymentmethod extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	public function __construct() {
		tsmConfig::loadJLang('com_tsmart_orders',TRUE);
		parent::__construct();

	}


	function save($data = 0){
		$data = vRequest::getPost();
		if(vmAccess::manager('raw')){
			$data['payment_name'] = vRequest::get('payment_name','');
			$data['payment_desc'] = vRequest::get('payment_desc','');
			if(isset($data['params'])){
				$data['params'] = vRequest::get('params','');
			}
		} else {
			$data['payment_name'] = vRequest::getHtml('payment_name','');
			$data['payment_desc'] = vRequest::getHtml('payment_desc','');
			if(isset($data['params'])){
				$data['params'] = vRequest::getHtml('params','');
			}
		}

		parent::save($data);
	}

	/**
	 * Clone a payment
	 *
	 * @author Valérie Isaksen
	 */
	public function ClonePayment() {
		$mainframe = Jfactory::getApplication();

		/* Load the view object */
		$view = $this->getView('paymentmethod', 'html');

		$model = tmsModel::getModel('paymentmethod');
		$msgtype = '';

		$cids = vRequest::getInt($this->_cidName, vRequest::getInt('tsmart_payment_id'));
		if(!is_array($cids)) $cids = array($cids);

		foreach($cids as $cid){
			if ($model->createClone($cid)) $msg = tsmText::_('com_tsmart_PAYMENT_CLONED_SUCCESSFULLY');
			else {
				$msg = tsmText::_('com_tsmart_PAYMENT_NOT_CLONED_SUCCESSFULLY');
				$msgtype = 'error';
			}
		}

		$mainframe->redirect('index.php?option=com_tsmart&view=paymentmethod', $msg, $msgtype);
	}

}
// pure php no closing tag
