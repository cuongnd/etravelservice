<?php
/**
 *
 * Data module for shop product
 *
 * @package	VirtueMart
 * @subpackage product
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop product
 *
 * @package	VirtueMart
 * @subpackage product
 */
class VirtueMartModelTrip extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('products');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem($id=0) {
		$item= $this->getData($id);
        return $item;
	}



	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);

		$query->select('product.*,products_en_gb.product_name')
			->from('#__virtuemart_products AS product')
            ->leftJoin('#__virtuemart_products_en_gb AS products_en_gb USING(virtuemart_product_id)')
			//->leftJoin('#__virtuemart_cityarea AS cityarea using (virtuemart_city_area_id)')
			//->leftJoin('#__virtuemart_states AS states ON states.virtuemart_state_id=cityarea.virtuemart_state_id')
			//->leftJoin('#__virtuemart_countries AS countries ON countries.virtuemart_country_id=states.virtuemart_country_id')
		;
		$user = JFactory::getUser();
		$shared = '';
		if (vmAccess::manager()) {
			//$query->where('transferaddon.shared=1','OR');
		}
		$search=vRequest::getCmd('search', false);
		if (empty($search)) {
			$search = vRequest::getString('search', false);
		}
		// add filters
		if ($search) {
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape($search, true) . '%"';
			$query->where('product.product_name LIKE '.$search);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'product.virtuemart_product_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'product.ordering')
		{
			$orderCol = $db->quoteName('product.virtuemart_product_id') . ' ' . $orderDirn . ', ' . $db->quoteName('product.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		return $query;
	}




}
// pure php no closing tag
