<?php
/**
*
* Currency View
*
* @package	VirtueMart
* @subpackage Currency
* @author RickG
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 8724 2015-02-18 14:03:29Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmviewadmin.php');

/**
 * HTML View class for maintaining the list of currencies
 *
 * @package	VirtueMart
 * @subpackage Currency
 * @author RickG, Max Milbers
 */
class TsmartViewExcursionaddon extends VmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = VmModel::getModel();

		$input = JFactory::getApplication()->input;
		$task = $input->get('task');
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

			$model->setId($cid);
			$this->item = $model->getItem();
			//get list tour
			require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/vmexcursionaddon.php';
			$this->item->list_tour_id = vmexcursionaddon::get_list_tour_id_by_excursion_addon_id($this->item->virtuemart_excursion_addon_id);
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmproduct.php';
			$list_tour = vmproduct::get_list_product();
			$this->assignRef('list_tour', $list_tour);
			//end get list tour


			$this->SetViewTitle('',$this->item->title);
			$this->addStandardEditViewCommandsPopup();

		} else {
			$this->SetViewTitle();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::editList();
			JToolBarHelper::addNew('add_new_item');
			JToolBarHelper::deleteList();

			$this->addStandardDefaultViewLists($model,0,'ASC');
			$this->items = $model->getItemList();
			$this->pagination = $model->getPagination();

			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmcities.php';
			$this->list_cityarea=vmcities::get_city_state_country();
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmexcursionaddon.php';
			$this->list_excursion_payment_type=vmexcursionaddon::get_list_excursion_payment_type();
			if($task=='edit_item')
			{
				$cid	= vRequest::getInt( 'cid' );

				$task = vRequest::getCmd('task', 'add');

				if($task!='add' && !empty($cid) && !empty($cid[0])){
					$cid = (int)$cid[0];
				} else {
					$cid = 0;
				}

				$model->setId($cid);
				$this->item = $model->getItem();

				//get list tour
				require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/vmexcursionaddon.php';
				$this->item->list_tour_id = vmexcursionaddon::get_list_tour_id_by_excursion_addon_id($this->item->virtuemart_excursion_addon_id);
				require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmproduct.php';
				$list_tour = vmproduct::get_list_product();
				$this->assignRef('list_tour', $list_tour);
				//end get list tour


			}


		}

		parent::display($tpl);
	}

}
// pure php no closing tag
