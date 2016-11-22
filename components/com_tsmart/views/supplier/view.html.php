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
class TsmartViewsupplier extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)
		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();
		$app=JFactory::getApplication();

		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		$this->tsmart_product_id=$app->input->get('tsmart_product_id',0,'int');

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

			//get supplier_type and service_type
			require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmsupplier.php';
			$list_supplier_type = vmsupplier::get_list_supplier_type();
			$this->assignRef('list_supplier_type', $list_supplier_type);
			$list_service_type = vmsupplier::get_list_service_type();
			$this->assignRef('list_service_type', $list_service_type);



			//end get supplier_type and service_type
			$list_state_province = vmsupplier::get_list_state_province();
			$this->assignRef('list_state_province', $list_state_province);

			$list_country = vmsupplier::get_list_country();
			$this->assignRef('list_country', $list_country);

			$list_city_area = vmsupplier::get_list_city_area();
			$this->assignRef('list_city_area', $list_city_area);



			$this->SetViewTitle('',$this->item->title);
			$this->addStandardEditViewCommandsPopup();

		} else {
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmproduct.php';
			$this->SetViewTitle();
			$this->addStandardDefaultViewCommandsPopup();
			$model->setDefaultValidOrderingFields('supplier');
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
			'supplier.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
			'supplier.title'        => JText::_('JGLOBAL_TITLE')
		);
	}

}
// pure php no closing tag
