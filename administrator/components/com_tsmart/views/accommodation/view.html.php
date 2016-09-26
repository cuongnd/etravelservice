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
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for maintaining the list of currencies
 *
 * @package	VirtueMart
 * @subpackage Currency
 * @author RickG, Max Milbers
 */
class TsmartViewaccommodation extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)

		$app=JFactory::getApplication();
		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');
		$task=$app->input->getString('task','');
		$virtuemart_product_id=$app->input->getInt('virtuemart_product_id',0);
		$model = VmModel::getModel();
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmproduct.php';
		$this->virtuemart_product_id=$app->input->get('virtuemart_product_id',0,'int');
        require_once  JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmhotel.php';
        $this->list_hotel=tsmHotel::get_list_hotel();
        $this->list_hotel=JArrayHelper::pivot($this->list_hotel,'virtuemart_hotel_id');
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
			$this->SetViewTitle('',$this->item->title);
			$this->addStandardEditViewCommandsPopup();

		} else {
			$this->item = $model->getItem();
			$this->SetViewTitle();
			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model,0,'ASC');
			$this->items = $model->getItemList(vRequest::getCmd('search', false));
			$this->pagination = $model->getPagination();

			if ($task == 'edit_item'||$task=='add_new_item') {
				$app=JFactory::getApplication();
				$input=$app->input;
				$virtuemart_itinerary_id=$input->getInt('virtuemart_itinerary_id',0);
				$virtuemart_product_id=$input->getInt('virtuemart_product_id',0);

				$cid	= vRequest::getInt( 'cid' );

				$virtuemart_accommodation_id=$cid[0];
				require_once  JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmserviceclass.php';
				$this->list_service_class=vmServiceclass::get_list_service_class_by_tour_id($virtuemart_product_id);


				require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmaccommodation.php';
				$this->list_hotel_selected_by_service_class_id_and_itinerary_id=tsmaccommodation::get_list_hotel_selected_by_service_class_id_and_itinerary_id_accommodation_id($this->list_service_class,$virtuemart_itinerary_id,$virtuemart_accommodation_id);
			}

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
