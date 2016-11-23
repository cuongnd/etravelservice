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
class TsmartViewitinerary extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)
		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');
        $this->setLayout('default');


		$model = tmsModel::getModel();
		$app=JFactory::getApplication();
		$task=$app->input->getString('task','');
		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		$this->tsmart_product_id=$app->input->get('tsmart_product_id',0,'int');
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmproduct.php';
        $this->SetViewTitle();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::deleteList();
        $model->setDefaultValidOrderingFields('itinerary');
        $this->addStandardDefaultViewLists($model,0,'ASC');
        $this->state         = $model->getState();
        $this->filterForm    = $this->getFilterForm();
        $this->items = $model->getItemList(vRequest::getCmd('search', false));
        $this->pagination = $model->getPagination();
        if($task=='edit'||$task=='add')
        {
            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmmeal.php';
            $this->list_meal=tmsmeal::get_list_meal();

            require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcities.php';
            $this->item=$model->getItem();
            $cities = tsmcities::get_cities();
            $this->assignRef('cities', $cities);
			$this->item->list_meal_id=tmsmeal::get_list_meal_id_by_itinerary_id($this->item->tsmart_itinerary_id);
			$this->item->list_activity_id=tmsmeal::get_list_activity_id_by_itinerary_id($this->item->tsmart_itinerary_id);
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
