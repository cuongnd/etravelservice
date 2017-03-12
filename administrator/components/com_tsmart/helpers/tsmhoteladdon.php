<?php
/**
 * Class for getting with language keys translated text. The original code was written by joomla Platform 11.1
 *
 * @package    tsmart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @copyright Copyright (c) 2014 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
 *
 * http://tsmart.net
 */

/**
 * Text handling class.
 *
 * @package     Joomla.Platform
 * @subpackage  Language
 * @since       11.1
 */
class tsmHotelAddon
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_tour_id_by_hotel_addon_id($tsmart_hotel_addon_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tsmart_product_id')
            ->from('#__tsmart_tour_id_hotel_addon_id')
            ->where('tsmart_hotel_addon_id='.(int)$tsmart_hotel_addon_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_extra_night_addon($tsmart_product_id=0, $booking_date, $extra_night_type='pre_transfer')
    {
        $booking_date=JFactory::getDate($booking_date);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_hotel_addon AS hotel_addon')
            ->leftJoin('#__tsmart_tour_id_hotel_addon_id AS tour_id_hotel_addon_id ON tour_id_hotel_addon_id.tsmart_hotel_addon_id=hotel_addon.tsmart_hotel_addon_id')
            ->where('hotel_addon.hotel_addon_type='.$query->q($extra_night_type))
            ->where('tour_id_hotel_addon_id.tsmart_product_id='.(int)$tsmart_product_id)
            ->where('hotel_addon.vail_from<='.$query->q($booking_date->toSql()))
            ->where('hotel_addon.vail_to >='.$query->q($booking_date->toSql()))
        ;
        $hotel_addon=$db->setQuery($query)->loadObject();
        $hotel_addon->data_price=base64_decode($hotel_addon->data_price);
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $hotel_addon->data_price = up_json_decode($hotel_addon->data_price, false, 512, JSON_PARSE_JAVASCRIPT);
        return $hotel_addon;
    }
    public static function get_group_min_price($tsmart_product_id=0, $rule_date, $extra_night_type='pre_night')
    {
        $rule_date=JFactory::getDate($rule_date);
        $config=tsmConfig::get_config();
        $params=$config->params;
        if($extra_night_type=='pre_night')
        {
            $hotel_night_booking_days_allow=$params->get('hotel_pre_night_booking_days_allow',1);
            $before_date=clone $rule_date;
            $before_date->modify("-$hotel_night_booking_days_allow day");
        }else{
            $hotel_night_booking_days_allow=$params->get('hotel_post_night_booking_days_allow',1);
            $after_date=clone $rule_date;
            $after_date->modify("+$hotel_night_booking_days_allow day");
        }


        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_hotel_addon AS hotel_addon')
            ->leftJoin('#__tsmart_tour_id_hotel_addon_id AS tour_id_hotel_addon_id ON tour_id_hotel_addon_id.tsmart_hotel_addon_id=hotel_addon.tsmart_hotel_addon_id')
            ->where('hotel_addon.hotel_addon_type='.$query->q($extra_night_type))
            ->where('tour_id_hotel_addon_id.tsmart_product_id='.(int)$tsmart_product_id);
        if($extra_night_type=='pre_night'){
            $query
                ->where('hotel_addon.vail_to <='.$query->q($rule_date->toSql()))
                ->where('hotel_addon.vail_from >='.$query->q($before_date->toSql()));
        }else{
            $query
                ->where('hotel_addon.vail_from <='.$query->q($rule_date->toSql()))
                ->where('hotel_addon.vail_to >='.$query->q($after_date->toSql()));
        }

        ;
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $list_hotel_addon=$db->setQuery($query)->loadObjectList();
        if(count($list_hotel_addon)==0){
            return null;
        }
        foreach($list_hotel_addon as &$hotel_addon){
            $hotel_addon->data_price=base64_decode($hotel_addon->data_price);
            $hotel_addon->data_price = up_json_decode($hotel_addon->data_price, false, 512, JSON_PARSE_JAVASCRIPT);
        }

        $single_min_price=9999;
        $dbl_twin_min_price=9999;
        $tpl_min_price=9999;
        for($i=0;$i<count($list_hotel_addon);$i++) {
            $price_night_hotel = $list_hotel_addon[$i];
            $data_price = $price_night_hotel->data_price;
            if ($data_price != null) {
                $item_mark_up_type =$data_price->item_mark_up_type;
                $items =$data_price->items;
                $double_twin_room =$items->double_twin_room;
                $single_room =$items->single_room;
                $triple_room =$items->triple_room;
                $double_twin_room_mark_up_amount =(float)$double_twin_room->mark_up_amount;
                $double_twin_room_mark_up_percent =(float)$double_twin_room->mark_up_percent;
                $double_twin_room_net_price =(float)$double_twin_room->net_price;
                $double_twin_room_tax =(float)$double_twin_room->tax;
                $single_room_mark_up_amount =(float)$single_room->mark_up_amount;
                $single_room_mark_up_percent =(float)$single_room->mark_up_percent;
                $single_room_net_price =(float)$single_room->net_price;
                $single_room_tax =(float)$single_room->tax;
                $triple_room_mark_up_amount =(float)$triple_room->mark_up_amount;
                $triple_room_mark_up_percent =(float)$triple_room->mark_up_percent;
                $triple_room_net_price =(float)$triple_room->net_price;
                $triple_room_tax =(float)$triple_room->tax;
                if ($item_mark_up_type =='percent') {
                    $current_double_twin_room_sale_price =$double_twin_room_net_price + ($double_twin_room_net_price * $double_twin_room_mark_up_percent) / 100;
                    $current_double_twin_room_sale_price =$current_double_twin_room_sale_price + ($current_double_twin_room_sale_price * $double_twin_room_tax) / 100;
                    if ($current_double_twin_room_sale_price < $dbl_twin_min_price) {
                        $dbl_twin_min_price =$current_double_twin_room_sale_price;
                    }
                    $single_room_sale_price =$single_room_net_price + ($single_room_net_price * $single_room_mark_up_percent) / 100;
                    $single_room_sale_price =$single_room_sale_price + ($single_room_sale_price * $single_room_tax) / 100;
                    if ($single_room_sale_price < $single_min_price) {
                        $single_min_price =$single_room_sale_price;
                    }
                    $current_triple_room_sale_price =$triple_room_net_price + ($triple_room_net_price * $triple_room_mark_up_percent) / 100;
                    $current_triple_room_sale_price =$current_triple_room_sale_price + ($current_triple_room_sale_price * $triple_room_tax) / 100;
                    if ($current_triple_room_sale_price < $tpl_min_price) {
                        $tpl_min_price =$current_triple_room_sale_price;
                    }
                } else {
                    $current_double_twin_room_sale_price =$double_twin_room_net_price + $double_twin_room_mark_up_amount;
                    $current_double_twin_room_sale_price =$current_double_twin_room_sale_price + ($current_double_twin_room_sale_price * $double_twin_room_tax) / 100;
                    if ($current_double_twin_room_sale_price < $dbl_twin_min_price) {
                        $dbl_twin_min_price =$current_double_twin_room_sale_price;
                    }
                    $single_room_sale_price =$single_room_net_price + $single_room_mark_up_amount;
                    $single_room_sale_price =$single_room_sale_price + ($single_room_sale_price * $single_room_tax) / 100;
                    if ($single_room_sale_price < $single_min_price) {
                        $single_min_price =$single_room_sale_price;
                    }
                    $current_triple_room_sale_price =$triple_room_net_price + $triple_room_mark_up_amount;
                    $current_triple_room_sale_price =$current_triple_room_sale_price + ($current_triple_room_sale_price * $triple_room_tax) / 100;
                    if ($current_triple_room_sale_price < $tpl_min_price) {
                        $tpl_min_price =$current_triple_room_sale_price;
                    }
                }
            }
        }
        $min_price=new stdClass();
        $min_price->single_min_price=$single_min_price;
        $min_price->dbl_twin_min_price=$dbl_twin_min_price;
        $min_price->tpl_min_price=$tpl_min_price;
        return $min_price;
    }

    public static function get_detail_hotel_by_hotel_id($vituemart_hotel_id=0){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('hotel.*,cityarea.city_area_name')
            ->from('#__tsmart_hotel AS hotel')
            ->leftJoin('#__tsmart_cityarea AS cityarea USING(tsmart_cityarea_id)')
            ->where('hotel.tsmart_hotel_id='.(int)$vituemart_hotel_id)
        ;

        return $db->setQuery($query)->loadObject();
    }

    public static function get_list_hotel_payment_type()
    {
        $list_hotel_payment_type=array(
            'instant_payment'=>'Instant payment',
            'last_payment'=>'Last transfer'
        );
        $a_list_hotel_payment_type=array();
        foreach($list_hotel_payment_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_hotel_payment_type[]=$a_item;
        }
        return $a_list_hotel_payment_type;

    }

    public static function get_list_hotel_addon_type()
    {
        $list_hotel_type=array(
            'pre_night'=>'Pre night',
            'post_night'=>'Post night'
        );
        $a_list_hotel_type=array();
        foreach($list_hotel_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_hotel_type[]=$a_item;
        }
        return $a_list_hotel_type;

    }

    public static function get_list_hotel_addon_service_class()
    {
        $list_hotel_type=array(
            'budget'=>'budget',
            'standard'=>'standard',
            'superior'=>'superior',
            'deluxe'=>'deluxe',
            'Luxury'=>'Luxury'
        );
        $a_list_hotel_type=array();
        foreach($list_hotel_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_hotel_type[]=$a_item;
        }
        return $a_list_hotel_type;
    }


}