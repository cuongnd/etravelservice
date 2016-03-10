<?php
/**
 *
 * Data module for shop currencies
 *
 * @package    VirtueMart
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

if (!class_exists('VmModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'vmmodel.php');

/**
 * Model class for shop Currencies
 *
 * @package    VirtueMart
 * @subpackage Currency
 */
class VirtueMartModelExcursionaddon extends VmModel
{


    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('excursion_addon');
    }

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
    function getItem($id = 0)
    {
        return $this->getData($id);
    }


    /**
     * Retireve a list of currencies from the database.
     * This function is used in the backend for the currency listing, therefore no asking if enabled or not
     * @author Max Milbers
     * @return object List of currency objects
     */
    function getItemList($search = '')
    {
        $data=parent::getItems();
        return $data;
    }
    function getListQuery()
    {
        $db = JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('excursion_addon.*')
            ->from('#__virtuemart_excursion_addon AS excursion_addon')
            ->leftJoin('me1u8_virtuemart_cityarea AS cityarea USING(virtuemart_cityarea_id)')
            ->select('cityarea.city_area_name AS city_area_name')
        ;
        $user = JFactory::getUser();
        $shared = '';
        if (vmAccess::manager()) {
            //$query->where('excursionaddon.shared=1','OR');
        }
        $search=vRequest::getCmd('search', false);
        if (empty($search)) {
            $search = vRequest::getString('search', false);
        }
        // add filters
        if ($search) {
            $db = JFactory::getDBO();
            $search = '"%' . $db->escape($search, true) . '%"';
            $query->where('excursion_addon.excursion_addon_name LIKE '.$search);
        }
        if(empty($this->_selectedOrdering)) vmTrace('empty _getOrdering');
        if(empty($this->_selectedOrderingDir)) vmTrace('empty _selectedOrderingDir');
        $query->order($this->_selectedOrdering.' '.$this->_selectedOrderingDir);
        return $query;
    }

    /**
     * Retireve a list of currencies from the database.
     *
     * This is written to get a list for selecting currencies. Therefore it asks for enabled
     * @author Max Milbers
     * @return object List of currency objects
     */

    function store(&$data)
    {
        $db=JFactory::getDbo();
        if (!vmAccess::manager('excursionaddon')) {
            vmWarn('Insufficient permissions to store excursionaddon');
            return false;
        }
        $virtuemart_excursion_addon_id= parent::store($data);
        if($virtuemart_excursion_addon_id) {
            //inser to excusionaddon
            $query = $db->getQuery(true);
            $query->delete('#__virtuemart_tour_id_excursion_addon_id')
                ->where('virtuemart_excursion_addon_id=' . (int)$virtuemart_excursion_addon_id);
            $db->setQuery($query)->execute();
            $err = $db->getErrorMsg();
            if (!empty($err)) {
                vmError('can not delete tour in excursion_addon', $err);
            }
            $list_tour_id = $data['list_tour_id'];
            foreach ($list_tour_id as $virtuemart_product_id) {
                $query->clear()
                    ->insert('#__virtuemart_tour_id_excursion_addon_id')
                    ->set('virtuemart_product_id=' . (int)$virtuemart_product_id)
                    ->set('virtuemart_excursion_addon_id=' . (int)$virtuemart_excursion_addon_id);
                $db->setQuery($query)->execute();
                $err = $db->getErrorMsg();
                if (!empty($err)) {
                    vmError('can not insert tour in this excursion_addon', $err);
                }
            }
            //end insert group size
        }

        return $virtuemart_excursion_addon_id;
    }

    function remove($ids)
    {
        if (!vmAccess::manager('excursionaddon')) {
            vmWarn('Insufficient permissions to remove excursionaddon');
            return false;
        }
        return parent::remove($ids);
    }

}
// pure php no closing tag