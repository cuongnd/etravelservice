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
class VirtueMartModelproduct extends VmModel {


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


	/**
	 * Retireve a list of product from the database.
	 * This function is used in the backend for the product listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of product objects
	 */
	function getItemList($search='') {
		echo $this->getListQuery()->dump();
		$data=parent::getItems();
		return $data;
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

	/**
	 * Retireve a list of product from the database.
	 *
	 * This is written to get a list for selecting product. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of product objects
	 */

	function store(&$data){
		if(!vmAccess::manager('product')){
			vmWarn('Insufficient permissions to store product');
			return false;
		}
        $table_product = $this->getTable ('products');
        if(!empty($data['virtuemart_product_id'])){
            $table_product -> load($data['virtuemart_product_id']);
        }
        $stored = $table_product->bindChecknStore ($data, false);
        $errors = $table_product->getErrors ();
        if(!$stored or count($errors)>0){
            foreach ($errors as $error) {
                vmError ('Product store '.$error);
            }
            if(!$stored){
                vmError('You are not an administrator or the correct vendor, storing of product cancelled');
            }
            return FALSE;
        }
        $virtuemart_product_id=$this->virtuemart_product_id = $data['virtuemart_product_id'] = (int)$table_product->virtuemart_product_id;
        if (empty($this->virtuemart_product_id)) {
            vmError('Product not stored, no id');
            return FALSE;
        }

        if($virtuemart_product_id) {
            $db = JFactory::getDbo();
            //inser to activity
            $query = $db->getQuery(true);
            $query->delete('#__virtuemart_tour_id_activity_id')
                ->where('virtuemart_product_id=' . (int)$virtuemart_product_id);
            $db->setQuery($query)->execute();
            $err = $db->getErrorMsg();
            if (!empty($err)) {
                vmError('can not delete activity in this tour', $err);
            }
            $list_activity_id = $data['list_activity_id'];
            foreach ($list_activity_id as $virtuemart_activity_id) {
                $query->clear()
                    ->insert('#__virtuemart_tour_id_activity_id')
                    ->set('virtuemart_product_id=' . (int)$virtuemart_product_id)
                    ->set('virtuemart_activity_id=' . (int)$virtuemart_activity_id);
                $db->setQuery($query)->execute();
                $err = $db->getErrorMsg();
                if (!empty($err)) {
                    vmError('can not insert activity in this tour', $err);
                }
            }
            //end inser tour type

            //inser to countries
            $query = $db->getQuery(true);
            $query->delete('#__virtuemart_tour_id_country_id')
                ->where('virtuemart_product_id=' . (int)$virtuemart_product_id);
            $db->setQuery($query)->execute();
            $err = $db->getErrorMsg();
            if (!empty($err)) {
                vmError('can not delete country in this tour', $err);
            }
            $list_virtuemart_country_id = $data['list_virtuemart_country_id'];
            foreach ($list_virtuemart_country_id as $virtuemart_country_id) {
                $query->clear()
                    ->insert('#__virtuemart_tour_id_country_id')
                    ->set('virtuemart_product_id=' . (int)$virtuemart_product_id)
                    ->set('virtuemart_country_id=' . (int)$virtuemart_country_id);
                $db->setQuery($query)->execute();
                $err = $db->getErrorMsg();
                if (!empty($err)) {
                    vmError('can not insert country in this tour', $err);
                }
            }
            //inser to tour class
            $query = $db->getQuery(true);
            $query->delete('#__virtuemart_tour_id_service_class_id')
                ->where('virtuemart_product_id=' . (int)$virtuemart_product_id);
            $db->setQuery($query)->execute();
            $err = $db->getErrorMsg();
            if (!empty($err)) {
                vmError('can not delete tour in tour class', $err);
            }
            $list_tour_service_class_id = $data['list_tour_service_class_id'];
            foreach ($list_tour_service_class_id as $virtuemart_service_class_id) {
                $query->clear()
                    ->insert('#__virtuemart_tour_id_service_class_id')
                    ->set('virtuemart_product_id=' . (int)$virtuemart_product_id)
                    ->set('virtuemart_service_class_id=' . (int)$virtuemart_service_class_id);
                $db->setQuery($query)->execute();
                $err = $db->getErrorMsg();
                if (!empty($err)) {
                    vmError('can not insert tour in this tour class', $err);
                }
            }
            //inser to tour group size
            $query = $db->getQuery(true);
            $query->delete('#__virtuemart_tour_id_group_size_id')
                ->where('virtuemart_product_id=' . (int)$virtuemart_product_id);
            $db->setQuery($query)->execute();
            $err = $db->getErrorMsg();
            if (!empty($err)) {
                vmError('can not delete tour in tour group size', $err);
            }
            $list_group_size_id = $data['list_group_size_id'];
            foreach ($list_group_size_id as $virtuemart_group_size_id) {
                $query->clear()
                    ->insert('#__virtuemart_tour_id_group_size_id')
                    ->set('virtuemart_product_id=' . (int)$virtuemart_product_id)
                    ->set('virtuemart_group_size_id=' . (int)$virtuemart_group_size_id);
                $db->setQuery($query)->execute();
                $err = $db->getErrorMsg();
                if (!empty($err)) {
                    vmError('can not insert tour in this tour group size', $err);
                }
            }


        }
        return $virtuemart_product_id;
	}

	function remove($ids){
		if(!vmAccess::manager('product')){
			vmWarn('Insufficient permissions to remove product');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
