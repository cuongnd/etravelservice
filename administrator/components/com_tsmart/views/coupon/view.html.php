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
class TsmartViewcoupon extends tsmViewAdmin {

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
			require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/vmcoupon.php';
			$this->item->list_tour_id = tsmcoupon::get_list_tour_id_by_hotel_addon_id($this->item->tsmart_hotel_addon_id);
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmproduct.php';
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
            $this->state=$model->getState();
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmcities.php';
			$this->list_cityarea=tsmcities::get_city_state_country();
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmcoupon.php';
			$this->list_hotel_addon_type=tsmcoupon::get_list_hotel_addon_type();
			$this->list_hotel_payment_type=tsmcoupon::get_list_hotel_payment_type();
			$this->list_hotel_addon_service_class=tsmcoupon::get_list_hotel_addon_service_class();
			require_once  JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmhotel.php';

            require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmproduct.php';
            $list_tour = vmproduct::get_list_product();
            $this->assignRef('list_tour', $list_tour);


			$this->list_hotel=tsmHotel::get_list_hotel();
			if($task=='edit_item'||$task=='add_new_item')
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
				require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/vmcoupon.php';
				$this->hotel=tsmcoupon::get_detail_hotel_by_hotel_id($this->item->tsmart_hotel_id);
				$this->tour_id_seletecd=tsmcoupon::get_list_tour_id_by_hotel_addon_id($this->item->tsmart_hotel_addon_id);
				//get list tour

				$this->item->list_tour_id = tsmcoupon::get_list_tour_id_by_hotel_addon_id($this->item->tsmart_hotel_addon_id);

				//end get list tour


			}


		}

		parent::display($tpl);
	}

}
// pure php no closing tag
