<?php
/**
 *
 * Data module for shop tourfaq
 *
 * @package	tsmart
 * @subpackage tourfaq
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: tourfaq.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tmsModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmmodel.php');

/**
 * Model class for shop tourfaq
 *
 * @package	tsmart
 * @subpackage tourfaq
 */
class tsmartModeltourfaq extends tmsModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('tourfaq');
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
	 * Retireve a list of tourfaq from the database.
	 * This function is used in the backend for the tourfaq listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of tourfaq objects
	 */
	function getItemList($search='') {
		//echo $this->getListQuery()->dump();
		$data=parent::getItems();
		return $data;
	}

	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);

		$query->select('faq.*,categoryfaq.tsmart_categoryfaq_id,categoryfaq.categoryfaq_name')
			->from('#__tsmart_tour_id_faq_id AS tour_id_faq_id')
			->leftJoin('#__tsmart_faq AS faq   using (tsmart_faq_id)')
			->leftJoin('#__tsmart_categoryfaq AS categoryfaq ON categoryfaq.tsmart_categoryfaq_id=faq.tsmart_categoryfaq_id')
			->group('faq.tsmart_faq_id')
			//->leftJoin('#__tsmart_tourfaqs AS tourfaqs ON tourfaqs.tsmart_tourfaq_id=tourfaq.tsmart_tourfaq_id')

		;
		$user = JFactory::getUser();
		$shared = '';
		if (vmAccess::manager()) {
			//$query->where('transferaddon.shared=1','OR');
		}
		echo $query->dump();
		return $query;
	}

	/**
	 * Retireve a list of tourfaq from the database.
	 *
	 * This is written to get a list for selecting tourfaq. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of tourfaq objects
	 */

	function store(&$data){
		if(!vmAccess::manager('tourfaq')){
			vmWarn('Insufficient permissions to store tourfaq');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('tourfaq')){
			vmWarn('Insufficient permissions to remove tourfaq');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
