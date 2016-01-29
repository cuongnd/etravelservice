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
class VirtueMartModeltransferaddon extends VmModel
{


    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('transferaddon');
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
        $query->select('transferaddon.*')
            ->from('#__virtuemart_transferaddon AS transferaddon')
            ->leftJoin('me1u8_virtuemart_cityarea AS cityarea USING(virtuemart_cityarea_id)')
            ->select('cityarea.title AS cityarea_name')
        ;
        $query1=$db->getQuery(true);
        $query1->select('GROUP_CONCAT(products_en_gb.product_name)')
            ->from('#__virtuemart_tour_id_transferaddon_id AS tour_id_transferaddon_id')
            ->leftJoin('#__virtuemart_products_en_gb AS products_en_gb USING(virtuemart_product_id)')
            ->where('tour_id_transferaddon_id.virtuemart_transferaddon_id=transferaddon.virtuemart_transferaddon_id')
        ;
        $query->select("($query1) AS list_tour");
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
            $query->where('transferaddon.title LIKE '.$search);
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
        if (!vmAccess::manager('transferaddon')) {
            vmWarn('Insufficient permissions to store transferaddon');
            return false;
        }
        $virtuemart_transferaddon_id= parent::store($data);
        if($virtuemart_transferaddon_id) {
            //inser to transferaddon
            $query = $db->getQuery(true);
            $query->delete('#__virtuemart_tour_id_transferaddon_id')
                ->where('virtuemart_transferaddon_id=' . (int)$virtuemart_transferaddon_id);
            $db->setQuery($query)->execute();
            $err = $db->getErrorMsg();
            if (!empty($err)) {
                vmError('can not delete tour in transferaddon', $err);
            }
            $list_tour_id = $data['list_tour_id'];
            foreach ($list_tour_id as $virtuemart_product_id) {
                $query->clear()
                    ->insert('#__virtuemart_tour_id_transferaddon_id')
                    ->set('virtuemart_product_id=' . (int)$virtuemart_product_id)
                    ->set('virtuemart_transferaddon_id=' . (int)$virtuemart_transferaddon_id);
                $db->setQuery($query)->execute();
                $err = $db->getErrorMsg();
                if (!empty($err)) {
                    vmError('can not insert tour in this transferaddon', $err);
                }
            }
            //end insert group size
        }

        return $virtuemart_transferaddon_id;
    }

    function remove($ids)
    {
        if (!vmAccess::manager('transferaddon')) {
            vmWarn('Insufficient permissions to remove transferaddon');
            return false;
        }
        return parent::remove($ids);
    }

}
// pure php no closing tag