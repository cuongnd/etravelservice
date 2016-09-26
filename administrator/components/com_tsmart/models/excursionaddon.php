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

if (!class_exists('VmModel')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmmodel.php');

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
                    throw new Exception($db->getErrorMsg());
                }
            }

            //end insert group size
            $excursion_payment_type=$data['excursion_payment_type'];
            $table_excursion_ad_don_date_price=$this->getTable('excursion_ad_don_date_price');
            //end insert group size
            $vail_from=$data['vail_from'];
            $vail_from=JFactory::getDate($vail_from);
            $vail_to=$data['vail_to'];
            $vail_to=JFactory::getDate($vail_to);
            $data_price=$data['data_price'];
            $data_price=base64_decode($data_price);
            require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
            $data_price = up_json_decode($data_price, false, 512, JSON_PARSE_JAVASCRIPT);
            $item_mark_up_type=$data_price->item_mark_up_type;
            foreach($list_tour_id as $tour_id)
            {

                $single_room=$data_price->items->single_room;
                $double_twin_room=$data_price->items->double_twin_room;
                $triple_room=$data_price->items->triple_room;

                while ($vail_from->getTimestamp() <= $vail_to->getTimestamp()) {

                    $date=$vail_from->format('Y-m-d');
                    $table_excursion_ad_don_date_price->id=0;
                    $table_excursion_ad_don_date_price->jload(array('date'=>$date,'virtuemart_product_id'=>$tour_id,'hotel_addon_type'=>$hotel_addon_type));
                    $table_excursion_ad_don_date_price->date=$date;
                    $table_excursion_ad_don_date_price->virtuemart_hotel_addon_id=$virtuemart_hotel_addon_id;
                    $table_excursion_ad_don_date_price->virtuemart_product_id=$tour_id;
                    $table_excursion_ad_don_date_price->hotel_addon_type=$hotel_addon_type;
                    $table_excursion_ad_don_date_price->single_room_net_price=$single_room->net_price;
                    $table_excursion_ad_don_date_price->doulble_twin_room_net_price=$double_twin_room->net_price;
                    $table_excursion_ad_don_date_price->triple_room_net_price=$triple_room->net_price;

                    //tax
                    $table_excursion_ad_don_date_price->single_room_tax=$single_room->tax;
                    $table_excursion_ad_don_date_price->doulble_twin_room_tax=$double_twin_room->tax;
                    $table_excursion_ad_don_date_price->triple_room_tax=$triple_room->tax;
                    if($item_mark_up_type=='percent')
                    {
                        $table_excursion_ad_don_date_price->single_room_mark_up_percent=$single_room->mark_up_percent;
                        $table_excursion_ad_don_date_price->doulble_twin_room_mark_up_percent=$double_twin_room->mark_up_percent;
                        $table_excursion_ad_don_date_price->triple_room_mark_up_percent=$triple_room->mark_up_percent;
                    }else{
                        $table_excursion_ad_don_date_price->single_room_mark_up_amout=$single_room->mark_up_amount;
                        $table_excursion_ad_don_date_price->doulble_twin_room_mark_up_amount=$double_twin_room->mark_up_amount;
                        $table_excursion_ad_don_date_price->triple_room_mark_up_amout=$triple_room->mark_up_amount;
                    }
                    $ok=$table_excursion_ad_don_date_price->store();
                    if(!$ok)
                    {
                        throw new  Exception($table_excursion_ad_don_date_price->getError());
                    }
                    $vail_from->modify('+1 day');

                }

            }



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