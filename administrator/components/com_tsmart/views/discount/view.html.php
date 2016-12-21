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
class TsmartViewdiscount extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();

		$input = JFactory::getApplication()->input;
		$task = $input->get('task');
		$config = JFactory::getConfig();
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
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmcities.php';
		$this->list_cityarea=tsmcities::get_city_state_country();
		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmdiscount.php';
		$this->list_discount_type=tsmdiscount::get_list_discount_type();
		$this->list_discount_service_class=tsmdiscount::get_list_discount_service_class();
		require_once  JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmhotel.php';

		require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmproduct.php';
		$list_tour = tsmproduct::get_list_product();
		$this->assignRef('list_tour', $list_tour);

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
			require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmdiscount.php';

			$this->item->list_service_class_id = tsmdiscount::get_list_service_class_id_by_discount_id($this->item->tsmart_discount_id);
			$this->item->list_departure_id = tsmdiscount::get_list_departure_id_by_discount_id($this->item->tsmart_discount_id);

			//end get list tour


		}

		parent::display($tpl);
	}

}
// pure php no closing tag
