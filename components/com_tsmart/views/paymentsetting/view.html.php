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
class TsmartViewpaymentsetting extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();

		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {
			$cid	= vRequest::getInt( 'cid' );
			$this->view_height=1500;
			$task = vRequest::getCmd('task', 'add');

			if($task!='add' && !empty($cid) && !empty($cid[0])){
				$cid = (int)$cid[0];
			} else {
				$cid = 0;
			}

			$model->setId($cid);
			$this->item = $model->getItem();
			$this->SetViewTitle('',$this->item->service_class_name);
			$this->addStandardEditViewCommandsPopup();

		} else {


			$app=JFactory::getApplication();
			$input=$app->input;
			$this->SetViewTitle();
			JToolBarHelper::save('save','Save');
			$this->addStandardDefaultViewLists($model,0,'ASC');
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmpaymentsetting.php';
			$this->list_config_mode=vmpaymentsetting::get_config_mode();
			$this->hold_seat_type=vmpaymentsetting::get_hold_seat_type();
			$this->currencies=vmpaymentsetting::get_list_currency();
			//get list payment method
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmpaymentmethod.php';
			$list_payment_method = tsmpaymentmethod::get_list_payment_method();
			$this->assignRef('list_payment_method', $list_payment_method);


			//end get list payment method

			$tsmart_paymentsetting_id=$input->get('tsmart_paymentsetting_id',0,'int');
			if(!$tsmart_paymentsetting_id)
			{
				$db=JFactory::getDbo();
				$query=$db->getQuery(true);
				$query->select('tsmart_paymentsetting_id')
					->from('#__tsmart_paymentsetting')
					;
				$tsmart_paymentsetting_id=$db->setQuery($query)->loadResult();
			}
			$this->item = $model->getItem($tsmart_paymentsetting_id);
			//
			$list_payment_method = vmpaymentsetting::get_list_payment_method_by_paymentsetting_id($tsmart_paymentsetting_id);
			$this->assignRef('list_payment_method', $list_payment_method);

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
