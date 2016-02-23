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
class virtuemartViewitinerary extends VmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)
		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = VmModel::getModel();
		$app=JFactory::getApplication();

		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		$this->virtuemart_product_id=$app->input->get('virtuemart_product_id',0,'int');

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

			//get meal
			require_once JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/vmmeal.php';
			$list_meal=vmmeal::get_list_meal();
			$this->item->list_meal_id=vmmeal::get_list_meal_id_by_itinerary_id($this->item->virtuemart_itinerary_id);
			$this->assignRef('list_meal', $list_meal);
			//end get meal

			//get cities
			require_once JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/vmcities.php';
			$cities = vmcities::get_cities();
			$this->assignRef('cities', $cities);
			//end get cities


			$this->SetViewTitle('',$this->item->title);
			$this->addStandardEditViewCommandsPopup();

		} else {
			require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmproduct.php';
			$this->SetViewTitle();
			$this->addStandardDefaultViewCommandsPopup();
			$model->setDefaultValidOrderingFields('itinerary');
			$this->addStandardDefaultViewLists($model,0,'ASC');
			$this->state         = $model->getState();
			$this->filterForm    = $this->getFilterForm();
			$this->items = $model->getItemList(vRequest::getCmd('search', false));
			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'itinerary.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
			'itinerary.title'        => JText::_('JGLOBAL_TITLE')
		);
	}

}
// pure php no closing tag
