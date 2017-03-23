<?php
/**
*
* Currency View
*
* @package	tsmart
* @subpackage Currency
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 8724 2015-02-18 14:03:29Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for maintaining the list of currencies
 *
 * @package	tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers
 */
class TsmartViewpayment extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();


		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {
			$cid	= vRequest::getInt( 'cid' );

			$task = vRequest::getCmd('task', 'add');

			if($task!='add' && !empty($cid) && !empty($cid[0])){
				$cid = (int)$cid[0];
			} else {
				$cid = 0;
			}
			$this->view_height=1000;
			$model->setId($cid);
			$this->item = $model->getItem();
			//get list tour
			require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmpayment.php';
			$this->item->list_tour_id = vmPayment::get_list_tour_id_by_payment_id($this->item->tsmart_payment_id);
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmproduct.php';
			$this->list_tour = vmproduct::get_list_product();
			//end get list tour
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmcurrency.php';
			$list_currency = tsmcurrency::get_list_currency();
			$this->assignRef('list_currency', $list_currency);
			//get list payment method
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmpaymentmethod.php';
			$list_payment_method = tsmpaymentmethod::get_list_payment_method();
			$this->assignRef('list_payment_method', $list_payment_method);

			$this->item->list_payment_method_id = tsmpaymentmethod::get_list_payment_method_id_by_payment_id($this->item->tsmart_payment_id);

			//end get list payment method

			//get list mode payment
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmpaymentmethod.php';
			$list_mode_payment= tsmpaymentmethod::get_list_mode_payment();
			$this->assignRef('list_mode_payment', $list_mode_payment);

			//end get list mode payment
			$this->SetViewTitle('',$this->item->title);
			$this->addStandardEditViewCommandsPopup();

		} else {

			$this->SetViewTitle();
			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model,0,'ASC');
			$this->items = $model->getItemList(vRequest::getCmd('search', false));
			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
