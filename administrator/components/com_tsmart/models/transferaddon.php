<?php
/**
 *
 * Data module for shop currencies
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: currency.php 8970 2015-09-06 23:19:17Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if (!class_exists('tmsModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');
/**
 * Model class for shop Currencies
 *
 * @package    tsmart
 * @subpackage Currency
 */
class tsmartModeltransferaddon extends tmsModel
{
    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('transfer_addon');
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
        $data = parent::getItems();
        return $data;
    }
    function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('transfer_addon.*')
            ->from('#__tsmart_transfer_addon AS transfer_addon')
            ->leftJoin('me1u8_tsmart_cityarea AS cityarea USING(tsmart_cityarea_id)')
            ->select('cityarea.city_area_name AS city_area_name');
        //get list tour apply
        $query1 = $db->getQuery(true);
        $query1->select('GROUP_CONCAT(products_en_gb.product_name)')
            ->from('#__tsmart_tour_id_transfer_addon_id AS tour_id_transfer_addon_id')
            ->leftJoin('#__tsmart_products_en_gb AS products_en_gb USING(tsmart_product_id)')
            ->where('tour_id_transfer_addon_id.tsmart_transfer_addon_id=transfer_addon.tsmart_transfer_addon_id');
        $query->select("($query1) AS list_tour");
        $user = JFactory::getUser();
        $shared = '';
        if (vmAccess::manager()) {
            //$query->where('transferaddon.shared=1','OR');
        }
        $search = vRequest::getCmd('search', false);
        if (empty($search)) {
            $search = vRequest::getString('search', false);
        }
        // add filters
        if ($search) {
            $db = JFactory::getDBO();
            $search = '"%' . $db->escape($search, true) . '%"';
            $query->where('transfer_addon.transfer_addon_name LIKE ' . $search);
        }
        if (empty($this->_selectedOrdering)) vmTrace('empty _getOrdering');
        if (empty($this->_selectedOrderingDir)) vmTrace('empty _selectedOrderingDir');
        $query->order($this->_selectedOrdering . ' ' . $this->_selectedOrderingDir);
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
        $db = JFactory::getDbo();
        if (!vmAccess::manager('transferaddon')) {
            vmWarn('Insufficient permissions to store transferaddon');
            return false;
        }
        $data['vail_from']=JFactory::getDate($data['vail_from'])->toSql();
        $data['vail_to']=JFactory::getDate($data['vail_to'])->toSql();

        $tsmart_transfer_addon_id = parent::store($data);
        if ($tsmart_transfer_addon_id) {
            //inser to excusionaddon
            $query = $db->getQuery(true);
            $query->delete('#__tsmart_tour_id_transfer_addon_id')
                ->where('tsmart_transfer_addon_id=' . (int)$tsmart_transfer_addon_id);
            $db->setQuery($query)->execute();
            $err = $db->getErrorMsg();
            if (!empty($err)) {
                vmError('can not delete tour in transfer_addon', $err);
            }
            $list_tour_id = $data['list_tour_id'];
            foreach ($list_tour_id as $tsmart_product_id) {
                $query->clear()
                    ->insert('#__tsmart_tour_id_transfer_addon_id')
                    ->set('tsmart_product_id=' . (int)$tsmart_product_id)
                    ->set('tsmart_transfer_addon_id=' . (int)$tsmart_transfer_addon_id);
                $db->setQuery($query)->execute();
                $err = $db->getErrorMsg();
                if (!empty($err)) {
                    vmError('can not insert tour in this transfer_addon', $err);
                }
            }
            $tsm_config=$data['tsm_config'];
            $params=$tsm_config['params'];
            $tsm_config=tsmConfig::get_config();
            $tsm_config->params->set('transfer_arrange_year_old_from',$params['transfer_arrange_year_old_from']);
            $tsm_config->params->set('transfer_arrange_year_old_to',$params['transfer_arrange_year_old_to']);
            $table_general=tsmTable::getInstance('general','Table');
            $table_general->load($tsm_config->tsmart_general_id);
            $table_general->params=$tsm_config->params->toString();
            $table_general->store();
            //end insert group size
        }
        return $tsmart_transfer_addon_id;
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