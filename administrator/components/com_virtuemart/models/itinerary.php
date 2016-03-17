<?php
/**
 *
 * Data module for shop currencies
 *
 * @package	VirtueMart
 * @subpackage Currency
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package	VirtueMart
 * @subpackage Currency
 */
class VirtueMartModelitinerary extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('itinerary');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem($id=0) {
		return $this->getData($id);
	}


	/**
	 * Retireve a list of currencies from the database.
	 * This function is used in the backend for the currency listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of currency objects
	 */


	function getItemList($search='') {

		$select = ' itinerary.*,cityarea.city_area_name AS city_name';
		$joinedTables = ' FROM #__virtuemart_itinerary AS itinerary
				  LEFT JOIN #__virtuemart_cityarea AS cityarea ON cityarea.virtuemart_cityarea_id= itinerary.virtuemart_cityarea_id ';



		$where = array();

		$user = JFactory::getUser();
		$shared = '';
		if(vmAccess::manager() ){
			$shared = 'OR `shared`="1"';
		}

		if(empty($search)){
			$search = vRequest::getString('search', false);
		}
		// add filters
		if($search){
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape( $search, true ) . '%"' ;
			$where[] = '`title` LIKE '.$search;
		}

		$whereString='';
		if (count($where) > 0) $whereString = ' WHERE '.implode(' AND ', $where) ;

		$data = $this->exeSortSearchListQuery(0,$select,$joinedTables,$whereString,'',$this->_getOrdering());
		require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmmeal.php';
		foreach($data as $key=>$item)
		{
			$list_meal=vmmeal::get_list_meal_by_itinerary_id($item->virtuemart_itinerary_id);
			$list_meal=implode(', ', array_map(
				function ($item, $index) {
					return $item->title;
				},
				$list_meal,
				array_keys($list_meal)
			));
			$data[$key]->list_meal=$list_meal;
		}
		return $data;
	}


	/**
	 * Retireve a list of currencies from the database.
	 *
	 * This is written to get a list for selecting currencies. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of currency objects
	 */

	function store(&$data){
		if(!vmAccess::manager('itinerary')){
			vmWarn('Insufficient permissions to store itinerary');
			return false;
		}
		$virtuemart_itinerary_id= parent::store($data);
		if($virtuemart_itinerary_id) {
			$db = JFactory::getDbo();
			//inser to meal
			$query = $db->getQuery(true);
			$query->delete('#__virtuemart_itinerary_id_meal_id')
				->where('virtuemart_itinerary_id=' . (int)$virtuemart_itinerary_id);
			$db->setQuery($query)->execute();
			$err = $db->getErrorMsg();
			if (!empty($err)) {
				vmError('can not delete meal in this itinerary', $err);
			}
			$list_meal_id = $data['list_meal_id'];
			foreach ($list_meal_id as $virtuemart_meal_id) {
				$query->clear()
					->insert('#__virtuemart_itinerary_id_meal_id')
					->set('virtuemart_itinerary_id=' . (int)$virtuemart_itinerary_id)
					->set('virtuemart_meal_id=' . (int)$virtuemart_meal_id);
				$db->setQuery($query)->execute();
				$err = $db->getErrorMsg();
				if (!empty($err)) {
					vmError('can not insert meal in this itinerary', $err);
				}
			}
		}
		return $virtuemart_itinerary_id;
		//end insert group size

	}

	function remove($ids){
		if(!vmAccess::manager('itinerary')){
			vmWarn('Insufficient permissions to remove itinerary');
			return false;
		}
		return parent::remove($ids);
	}
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->_context .= '.' . $layout;
		}

		$search = $this->getUserStateFromRequest($this->_context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->_context . '.filter.access', 'filter_access');
		$this->setState('filter.access', $access);

		$authorId = $app->getUserStateFromRequest($this->_context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		$published = $this->getUserStateFromRequest($this->_context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);


		$level = $this->getUserStateFromRequest($this->_context . '.filter.level', 'filter_level');
		$this->setState('filter.level', $level);

		$language = $this->getUserStateFromRequest($this->_context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		$tag = $this->getUserStateFromRequest($this->_context . '.filter.tag', 'filter_tag', '');
		$this->setState('filter.tag', $tag);

		// List state information.
		parent::populateState('itinerary.id', 'desc');

		// Force a language
		$forcedLanguage = $app->input->get('forcedLanguage');

		if (!empty($forcedLanguage))
		{
			$this->setState('filter.language', $forcedLanguage);
			$this->setState('filter.forcedLanguage', $forcedLanguage);
		}
	}


}
// pure php no closing tag