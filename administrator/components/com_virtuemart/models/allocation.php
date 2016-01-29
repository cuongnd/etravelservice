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
class VirtueMartModelAllocation extends VmModel
{


    /**
     * constructs a VmModel
     * setMainTable defines the maintable of the model
     * @author Max Milbers
     */
    function __construct()
    {
        parent::__construct();
        $this->setMainTable('allocation');
    }

    /**
     * Retrieve the detail record for the current $id if the data has not already been loaded.
     *
     * @author Max Milbers
     */
    function getAllocation($allocation_id = 0)
    {
        return $this->getData($allocation_id);
    }


    /**
     * Retireve a list of currencies from the database.
     * This function is used in the backend for the currency listing, therefore no asking if enabled or not
     * @author Max Milbers
     * @return object List of currency objects
     */
    function getAllocationList($search)
    {

        $where = array();

        $user = JFactory::getUser();

        if (empty($search)) {
            $search = vRequest::getString('search', false);
        }
        // add filters
        if ($search) {
            $db = JFactory::getDBO();
            $search = '"%' . $db->escape($search, true) . '%"';
            $where[] = '`departure_name` LIKE ' . $search;
        }

        $whereString = '';
        if (count($where) > 0) $whereString = ' WHERE ' . implode(' AND ', $where);

        $data = $this->exeSortSearchListQuery(0, '*', ' FROM `#__virtuemart_allocation`', $whereString, '', $this->_getOrdering());

        return $data;
    }
    public function save_allocation_item()
    {
        $list_promotion_available2=$this->save_allocation_item();

    }
    public function get_allocation_item()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $allocation_id=$input->get('allocation_id',0,'int');
        $min_space=$input->get('min_space',0,'int');
        $tour_id=$input->get('tour_id',0,'int');
        $tour_service_class_id=$input->get('tour_service_class_id',0,'int');
        $vail_period_from=$input->get('vail_period_from',JFactory::getDate(),'date');
        $vail_period_to=$input->get('vail_period_to',JFactory::getDate(),'date');


        // Start date
        $date = JFactory::getDate($vail_period_from);
        // End date
        $end_date = JFactory::getDate($vail_period_to);
        $list_date=array();
        while ($date->getTimestamp() <= $end_date->getTimestamp()) {
            $list_date[]= $date->format('Y-m-d');
            $date->modify('+1 day');
        }//l?y ra các promotion theo ?i?u kiên tour_id, tour class và group size
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);


        $query->select('promotion_price.*')
            ->from('#__virtuemart_tour_promotion_price AS promotion_price')
            ->where('virtuemart_product_id='.(int)$tour_id)
            ->where('service_class_id='.(int)$tour_service_class_id)
            ->leftJoin('#__virtuemart_group_size_id_tour_promotion_price_id AS group_size_id_tour_promotion_price_id
			ON group_size_id_tour_promotion_price_id.virtuemart_tour_promotion_price_id=promotion_price.virtuemart_promotion_price_id')
            ->select('group_size_id_tour_promotion_price_id.*')
            ->leftJoin('#__virtuemart_group_size AS group_size ON group_size.virtuemart_group_size_id=group_size_id_tour_promotion_price_id.virtuemart_group_size_id')
            ->where('group_size.from>='.(int)$min_space)

        ;
        $list_promotion_price=$db->setQuery($query)->loadObjectList();
        // l?y giá có promotion l?c theo ?i?u ki?n th?a mãn danh sách ngày
        $list_promotion_available=array();
        foreach($list_date as $item_date)
        {
            $time_stamp_item_date=JFactory::getDate($item_date)->getTimestamp();
            foreach($list_promotion_price as $item_promotion_price_date)
            {
                $start_date=$item_promotion_price_date->sale_period_from;
                $end_date=$item_promotion_price_date->sale_period_to;
                $time_stamp_start_date=JFactory::getDate($start_date)->getTimestamp();
                $time_stamp_end_date=JFactory::getDate($end_date)->getTimestamp();
                if($time_stamp_item_date>=$time_stamp_start_date || $time_stamp_item_date<=$time_stamp_end_date)
                {
                    $item_promotion_price_date1=clone $item_promotion_price_date;
                    $item_promotion_price_date1->date_select=$item_date;
                    $list_promotion_available[]=$item_promotion_price_date1;
                }
            }
        }
        //l?y ra các giá markup (giá tr? promotion) theo tour có net promotion price
        $list_mark_up_tour_promotion_net_price=array();
        $list_virtuemart_promotion_price_id=array();
        foreach($list_promotion_price as $item_promotion_price)
        {
            if(!in_array($item_promotion_price->virtuemart_promotion_price_id,$list_virtuemart_promotion_price_id))
            {
                $list_virtuemart_promotion_price_id[]=$item_promotion_price->virtuemart_promotion_price_id;
            }
        }
        if(count($list_virtuemart_promotion_price_id)>=1) {
            $query->clear()
                ->from('#__virtuemart_mark_up_tour_promotion_net_price_id AS mark_up_tour_promotion_net_price_id')
                ->select('mark_up_tour_promotion_net_price_id.*')
                ->where('mark_up_tour_promotion_net_price_id.virtuemart_tour_promotion_price_id IN ('.implode(',', $list_virtuemart_promotion_price_id).')')
            ;

            $list_mark_up_tour_promotion_net_price=$db->setQuery($query)->loadObjectList();
        }
        $list_mark_up_tour_promotion_net_price2=array();
        foreach($list_mark_up_tour_promotion_net_price as $mark_up_tour_promotion_net_price)
        {
            $virtuemart_tour_promotion_price_id=$mark_up_tour_promotion_net_price->virtuemart_tour_promotion_price_id;
            $list_mark_up_tour_promotion_net_price2[$virtuemart_tour_promotion_price_id][$mark_up_tour_promotion_net_price->type]=$mark_up_tour_promotion_net_price;
        }
        //tính giá l?i c?a promotion
        //1.tính giá th?c sau khi promotion
        foreach($list_promotion_available as $key=> $promotion_available)
        {
            $virtuemart_promotion_price_id=$promotion_available->virtuemart_promotion_price_id;

            $percent_price=$list_mark_up_tour_promotion_net_price2[$virtuemart_promotion_price_id]['percent'];
            if(
                $percent_price->senior!=0
                ||$percent_price->adult!=0
                || $percent_price->teen!=0
                || $percent_price->infant!=0
                || $percent_price->children1!=0
                || $percent_price->children2!=0
                || $percent_price->private_room!=0)
            {
                //giá ???c tính theo percent thì không tính theo amount n?a
                $price_senior=$list_promotion_available[$key]->price_senior;
                $price_senior=$price_senior-($price_senior*$percent_price->senior)/100;
                $list_promotion_available[$key]->price_senior=$price_senior;

                $price_adult=$list_promotion_available[$key]->price_adult;
                $price_adult=$price_adult-($price_adult*$percent_price->adult)/100;
                $list_promotion_available[$key]->price_adult=$price_adult;

                $price_teen=$list_promotion_available[$key]->price_teen;
                $price_teen=$price_teen-($price_teen*$percent_price->teen)/100;
                $list_promotion_available[$key]->price_teen=$price_teen;

                $price_infant=$list_promotion_available[$key]->price_infant;
                $price_infant=$price_infant-($price_infant*$percent_price->infant)/100;
                $list_promotion_available[$key]->price_infant=$price_infant;

                $price_children1=$list_promotion_available[$key]->price_children1;
                $price_children1=$price_children1-($price_children1*$percent_price->children1)/100;
                $list_promotion_available[$key]->price_children1=$price_children1;

                $price_children2=$list_promotion_available[$key]->price_children2;
                $price_children2=$price_children2-($price_children2*$percent_price->children2)/100;
                $list_promotion_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_promotion_available[$key]->price_private_room;
                $price_private_room=$price_private_room-($price_private_room*$percent_price->private_room)/100;
                $list_promotion_available[$key]->price_private_room=$price_private_room;


            }else{

                $amount_price=$list_mark_up_tour_promotion_net_price2[$virtuemart_promotion_price_id]['amount'];

                $price_senior=$list_promotion_available[$key]->price_senior;
                $price_senior=$price_senior-$amount_price->senior;
                $list_promotion_available[$key]->price_senior=$price_senior;

                $price_adult=$list_promotion_available[$key]->price_adult;
                $price_adult=$price_adult-$amount_price->adult;
                $list_promotion_available[$key]->price_adult=$price_adult;

                $price_teen=$list_promotion_available[$key]->price_teen;
                $price_teen=$price_teen-$amount_price->teen;
                $list_promotion_available[$key]->price_teen=$price_teen;

                $price_infant=$list_promotion_available[$key]->price_infant;
                $price_infant=$price_infant-$amount_price->infant;
                $list_promotion_available[$key]->price_infant=$price_infant;

                $price_children1=$list_promotion_available[$key]->price_children1;
                $price_children1=$price_children1-$amount_price->children1;
                $list_promotion_available[$key]->price_children1=$price_children1;

                $price_children2=$list_promotion_available[$key]->price_children2;
                $price_children2=$price_children2-$amount_price->children2;
                $list_promotion_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_promotion_available[$key]->price_private_room;
                $price_private_room=$price_private_room-$amount_price->private_room;
                $list_promotion_available[$key]->price_private_room=$price_private_room;
            }



        }


        //l?y ra các giá markup (giá tr? có lãi) theo tour có net promotion price
        $list_mark_up_tour_promotion_price=array();
        if(count($list_virtuemart_promotion_price_id)>=1) {
            $query->clear()
                ->from('#__virtuemart_mark_up_tour_promotion_price_id AS mark_up_tour_promotion_price_id')
                ->select('mark_up_tour_promotion_price_id.*')
                ->where('mark_up_tour_promotion_price_id.virtuemart_tour_promotion_price_id IN ('.implode(',', $list_virtuemart_promotion_price_id).')')
            ;
            $list_mark_up_tour_promotion_price=$db->setQuery($query)->loadObjectList();
        }

        $list_mark_up_tour_promotion_price2=array();
        foreach($list_mark_up_tour_promotion_price as $mark_up_tour_promotion_price)
        {
            $virtuemart_tour_promotion_price_id=$mark_up_tour_promotion_price->virtuemart_tour_promotion_price_id;
            $list_mark_up_tour_promotion_price2[$virtuemart_tour_promotion_price_id][$mark_up_tour_promotion_price->type]=$mark_up_tour_promotion_price;
        }
        //2.tính giá th?c sau khi khi có markup (có ph?n lãi)

        foreach($list_promotion_available as $key=> $promotion_available)
        {
            $virtuemart_promotion_price_id=$promotion_available->virtuemart_promotion_price_id;

            $percent_price=$list_mark_up_tour_promotion_price2[$virtuemart_promotion_price_id]['percent'];
            if(
                $percent_price->senior!=0
                ||$percent_price->adult!=0
                || $percent_price->teen!=0
                || $percent_price->infant!=0
                || $percent_price->children1!=0
                || $percent_price->children2!=0
                || $percent_price->private_room!=0)
            {
                //giá ???c tính theo percent thì không tính theo amount n?a
                $price_senior=$list_promotion_available[$key]->price_senior;
                $price_senior=$price_senior-($price_senior*$percent_price->senior)/100;
                $list_promotion_available[$key]->price_senior=$price_senior;

                $price_adult=$list_promotion_available[$key]->price_adult;
                $price_adult=$price_adult-($price_adult*$percent_price->adult)/100;
                $list_promotion_available[$key]->price_adult=$price_adult;

                $price_teen=$list_promotion_available[$key]->price_teen;
                $price_teen=$price_teen-($price_teen*$percent_price->teen)/100;
                $list_promotion_available[$key]->price_teen=$price_teen;

                $price_infant=$list_promotion_available[$key]->price_infant;
                $price_infant=$price_infant-($price_infant*$percent_price->infant)/100;
                $list_promotion_available[$key]->price_infant=$price_infant;

                $price_children1=$list_promotion_available[$key]->price_children1;
                $price_children1=$price_children1-($price_children1*$percent_price->children1)/100;
                $list_promotion_available[$key]->price_children1=$price_children1;

                $price_children2=$list_promotion_available[$key]->price_children2;
                $price_children2=$price_children2-($price_children2*$percent_price->children2)/100;
                $list_promotion_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_promotion_available[$key]->price_private_room;
                $price_private_room=$price_private_room-($price_private_room*$percent_price->private_room)/100;
                $list_promotion_available[$key]->price_private_room=$price_private_room;


            }else{

                $amount_price=$list_mark_up_tour_promotion_price2[$virtuemart_promotion_price_id]['amount'];

                $price_senior=$list_promotion_available[$key]->price_senior;
                $price_senior=$price_senior-$amount_price->senior;
                $list_promotion_available[$key]->price_senior=$price_senior;

                $price_adult=$list_promotion_available[$key]->price_adult;
                $price_adult=$price_adult-$amount_price->adult;
                $list_promotion_available[$key]->price_adult=$price_adult;

                $price_teen=$list_promotion_available[$key]->price_teen;
                $price_teen=$price_teen-$amount_price->teen;
                $list_promotion_available[$key]->price_teen=$price_teen;

                $price_infant=$list_promotion_available[$key]->price_infant;
                $price_infant=$price_infant-$amount_price->infant;
                $list_promotion_available[$key]->price_infant=$price_infant;

                $price_children1=$list_promotion_available[$key]->price_children1;
                $price_children1=$price_children1-$amount_price->children1;
                $list_promotion_available[$key]->price_children1=$price_children1;

                $price_children2=$list_promotion_available[$key]->price_children2;
                $price_children2=$price_children2-$amount_price->children2;
                $list_promotion_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_promotion_available[$key]->price_private_room;
                $price_private_room=$price_private_room-$amount_price->private_room;
                $list_promotion_available[$key]->price_private_room=$price_private_room;
            }



        }
        $list_promotion_available2=array();
        foreach($list_promotion_available as $promotion_available)
        {
            $list_promotion_available2[$promotion_available->virtuemart_product_id.'-'.$promotion_available->service_class_id.'-'.$promotion_available->virtuemart_group_size_id.'-'.$promotion_available->date_select]=$promotion_available;
        }

        //s? lý v?i giá basic
        $query->select('tour_price.*')
            ->from('#__virtuemart_tour_price AS tour_price')
            ->where('virtuemart_product_id='.(int)$tour_id)
            ->where('service_class_id='.(int)$tour_service_class_id)
            ->leftJoin('#__virtuemart_group_size_id_tour_price_id AS group_size_id_tour_price_id
			ON group_size_id_tour_price_id.virtuemart_tour_price_id=tour_price.virtuemart_price_id')
            ->select('group_size_id_tour_price_id.*')
            ->leftJoin('#__virtuemart_group_size AS group_size ON group_size.virtuemart_group_size_id=group_size_id_tour_price_id.virtuemart_group_size_id')
            ->where('group_size.from>='.(int)$min_space)

        ;
        $list_price=$db->setQuery($query)->loadObjectList();
        // l?y giá có promotion l?c theo ?i?u ki?n th?a mãn danh sách ngày
        $list_price_available=array();
        foreach($list_date as $item_date)
        {
            $time_stamp_item_date=JFactory::getDate($item_date)->getTimestamp();
            foreach($list_price as $item_price_date)
            {
                $start_date=$item_price_date->sale_period_from;
                $end_date=$item_price_date->sale_period_to;
                $time_stamp_start_date=JFactory::getDate($start_date)->getTimestamp();
                $time_stamp_end_date=JFactory::getDate($end_date)->getTimestamp();
                if($time_stamp_item_date>=$time_stamp_start_date || $time_stamp_item_date<=$time_stamp_end_date)
                {
                    $item_price_date1=clone $item_price_date;
                    $item_price_date1->date_select=$item_date;
                    $list_price_available[]=$item_price_date1;
                }
            }
        }


        //l?y ra các giá markup (giá tr? có lãi) theo tour có net  price


        $list_virtuemart_price_id=array();
        foreach($list_price as $item_price)
        {
            if(!in_array($item_price->virtuemart_price_id,$list_virtuemart_price_id))
            {
                $list_virtuemart_price_id[]=$item_price->virtuemart_price_id;
            }
        }


        $list_mark_up_tour_price=array();
        if(count($list_virtuemart_price_id)>=1) {
            $query->clear()
                ->from('#__virtuemart_mark_up_tour_promotion_price_id AS mark_up_tour_promotion_price_id')
                ->select('mark_up_tour_promotion_price_id.*')
                ->where('mark_up_tour_promotion_price_id.virtuemart_tour_promotion_price_id IN ('.implode(',', $list_virtuemart_price_id).')')
            ;
            $list_mark_up_tour_price=$db->setQuery($query)->loadObjectList();
        }

        $list_mark_up_tour_price2=array();
        foreach($list_mark_up_tour_price as $mark_up_tour_price)
        {
            $virtuemart_tour_price_id=$mark_up_tour_price->virtuemart_tour_price_id;
            $list_mark_up_tour_promotion_price2[$virtuemart_tour_price_id][$mark_up_tour_price->type]=$mark_up_tour_price;
        }
        //2.tính giá th?c sau khi khi có markup basic (có ph?n lãi)

        foreach($list_price_available as $key=> $price_available)
        {
            $virtuemart_price_id=$price_available->virtuemart_promotion_price_id;

            $percent_price=$list_mark_up_tour_price2[$virtuemart_price_id]['percent'];
            if(
                $percent_price->senior!=0
                ||$percent_price->adult!=0
                || $percent_price->teen!=0
                || $percent_price->infant!=0
                || $percent_price->children1!=0
                || $percent_price->children2!=0
                || $percent_price->private_room!=0)
            {
                //giá ???c tính theo percent thì không tính theo amount n?a
                $price_senior=$list_price_available[$key]->price_senior;
                $price_senior=$price_senior-($price_senior*$percent_price->senior)/100;
                $list_price_available[$key]->price_senior=$price_senior;

                $price_adult=$list_price_available[$key]->price_adult;
                $price_adult=$price_adult-($price_adult*$percent_price->adult)/100;
                $list_price_available[$key]->price_adult=$price_adult;

                $price_teen=$list_price_available[$key]->price_teen;
                $price_teen=$price_teen-($price_teen*$percent_price->teen)/100;
                $list_price_available[$key]->price_teen=$price_teen;

                $price_infant=$list_price_available[$key]->price_infant;
                $price_infant=$price_infant-($price_infant*$percent_price->infant)/100;
                $list_price_available[$key]->price_infant=$price_infant;

                $price_children1=$list_price_available[$key]->price_children1;
                $price_children1=$price_children1-($price_children1*$percent_price->children1)/100;
                $list_price_available[$key]->price_children1=$price_children1;

                $price_children2=$list_price_available[$key]->price_children2;
                $price_children2=$price_children2-($price_children2*$percent_price->children2)/100;
                $list_price_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_price_available[$key]->price_private_room;
                $price_private_room=$price_private_room-($price_private_room*$percent_price->private_room)/100;
                $list_price_available[$key]->price_private_room=$price_private_room;


            }else{

                $amount_price=$list_mark_up_tour_price2[$virtuemart_price_id]['amount'];

                $price_senior=$list_price_available[$key]->price_senior;
                $price_senior=$price_senior-$amount_price->senior;
                $list_price_available[$key]->price_senior=$price_senior;

                $price_adult=$list_price_available[$key]->price_adult;
                $price_adult=$price_adult-$amount_price->adult;
                $list_price_available[$key]->price_adult=$price_adult;

                $price_teen=$list_price_available[$key]->price_teen;
                $price_teen=$price_teen-$amount_price->teen;
                $list_price_available[$key]->price_teen=$price_teen;

                $price_infant=$list_price_available[$key]->price_infant;
                $price_infant=$price_infant-$amount_price->infant;
                $list_price_available[$key]->price_infant=$price_infant;

                $price_children1=$list_price_available[$key]->price_children1;
                $price_children1=$price_children1-$amount_price->children1;
                $list_price_available[$key]->price_children1=$price_children1;

                $price_children2=$list_price_available[$key]->price_children2;
                $price_children2=$price_children2-$amount_price->children2;
                $list_price_available[$key]->price_children2=$price_children2;

                $price_private_room=$list_price_available[$key]->price_private_room;
                $price_private_room=$price_private_room-$amount_price->private_room;
                $list_price_available[$key]->price_private_room=$price_private_room;
            }



        }
        $list_price_available2=array();
        foreach($list_price_available as $price_available)
        {
            $list_price_available2[$price_available->virtuemart_product_id.'-'.$price_available->service_class_id.'-'.$price_available->virtuemart_group_size_id.'-'.$price_available->date_select]=$price_available;
        }

        foreach($list_price_available2 as $key=>$value)
        {
            if(!is_object($list_promotion_available2[$key]))
            {
                $list_promotion_available2[$key]=new stdClass();
                $list_promotion_available2[$key]->base_price=$value;
                $list_promotion_available2[$key]->date_select=$value->date_select;
            }else{
                $list_promotion_available2[$key]->base_price=$value;
            }
        }

        return $list_promotion_available2;
    }
    function getVendorAcceptedCurrrenciesList($vendorId = 0)
    {

        static $currencies = array();
        if ($vendorId === 0) {
            $multix = Vmconfig::get('multix', 'none');
            if (strpos($multix, 'payment') !== FALSE) {
                if (!class_exists('VirtueMartModelVendor'))
                    require(VMPATH_ADMIN . DS . 'models' . DS . 'vendor.php');
                $vendorId = VirtueMartModelVendor::getLoggedVendor();

            } else {
                $vendorId = 1;
            }
        }
        if (!isset($currencies[$vendorId])) {
            $db = JFactory::getDbo();
            $q = 'SELECT `vendor_accepted_currencies`, `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id`=' . $vendorId;
            $db->setQuery($q);
            $vendor_currency = $db->loadAssoc();
            if (!$vendor_currency['vendor_accepted_currencies']) {
                $vendor_currency['vendor_accepted_currencies'] = $vendor_currency['vendor_currency'];
                vmWarn('No accepted currencies defined');
                if (empty($vendor_currency['vendor_accepted_currencies'])) {
                    $uri = JFactory::getURI();
                    $link = $uri->root() . 'administrator/index.php?option=com_virtuemart&view=user&task=editshop';
                    vmWarn(vmText::sprintf('COM_VIRTUEMART_CONF_WARN_NO_CURRENCY_DEFINED', '<a href="' . $link . '">' . $link . '</a>'));
                    $currencies[$vendorId] = false;
                    return $currencies[$vendorId];
                }
            }
            $q = 'SELECT `virtuemart_currency_id`,CONCAT_WS(" ",`currency_name`,`currency_symbol`) as currency_txt
					FROM `#__virtuemart_currencies` WHERE `virtuemart_currency_id` IN (' . $vendor_currency['vendor_accepted_currencies'] . ')';
            if ($vendorId != 1) {
                $q .= ' AND (`virtuemart_vendor_id` = "' . $vendorId . '" OR `shared`="1")';
            }
            $q .= '	AND published = "1"
					ORDER BY `ordering`,`currency_name`';

            $db->setQuery($q);
            $currencies[$vendorId] = $db->loadObjectList();
        }

        return $currencies[$vendorId];
    }

    /**
     * Retireve a list of currencies from the database.
     *
     * This is written to get a list for selecting currencies. Therefore it asks for enabled
     * @author Max Milbers
     * @return object List of currency objects
     */
    function getCurrencies($vendorId = 1)
    {
        $db = JFactory::getDBO();
        $q = 'SELECT * FROM `#__virtuemart_currencies` WHERE (`virtuemart_vendor_id` = "' . (int)$vendorId . '" OR `shared`="1") AND published = "1" ORDER BY `ordering`,`#__virtuemart_currencies`.`currency_name`';
        $db->setQuery($q);
        return $db->loadObjectList();
    }

    function store(&$data)
    {
        if (!vmAccess::manager('currency')) {
            vmWarn('Insufficient permissions to store currency');
            return false;
        }
        return parent::store($data);
    }

    function remove($ids)
    {
        if (!vmAccess::manager('currency')) {
            vmWarn('Insufficient permissions to remove currency');
            return false;
        }
        return parent::remove($ids);
    }

}
// pure php no closing tag