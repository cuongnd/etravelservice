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
class tsmexcursionaddon
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_tour_id_by_excursion_addon_id($tsmart_excursion_addon_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tsmart_product_id')
            ->from('#__tsmart_tour_id_excursion_addon_id')
            ->where('tsmart_excursion_addon_id='.(int)$tsmart_excursion_addon_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_data_price_by_tsmart_excursion_addon_id($tsmart_excursion_addon_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_excursion_addon AS excursion_addon')
            ->where('excursion_addon.tsmart_excursion_addon_id='.(int)$tsmart_excursion_addon_id);

        ;
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $excursion_addon=$db->setQuery($query)->loadObject();
        $excursion_addon->data_price=base64_decode($excursion_addon->data_price);
        $data_price = up_json_decode($excursion_addon->data_price, false, 512, JSON_PARSE_JAVASCRIPT);
        return $data_price;
    }


    public static function get_children_price_by_total_passenger_and_tsmart_excursion_addon_id($total_passenger = 1, $tsmart_excursion_addon_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_excursion_addon AS excursion_addon')
            ->where('excursion_addon.tsmart_excursion_addon_id=' . (int)$tsmart_excursion_addon_id);
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        //echo $query->dump();
        $excursion_addon = $db->setQuery($query)->loadObject();
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        //echo $query->dump();
        $data_price = base64_decode($excursion_addon->data_price);
        $data_price = up_json_decode($data_price, false, 512, JSON_PARSE_JAVASCRIPT);
        $children_under_year=$data_price->children_under_year;

        $adult_price=self::get_adult_price_by_total_passenger_and_tsmart_excursion_addon_id($total_passenger,$tsmart_excursion_addon_id);
        $children_discount_amount=$data_price->children_discount_amount;
        $children_discount_percent=$data_price->children_discount_percent;
        $children_price=0;
        if(!empty($children_discount_amount) && is_numeric($children_discount_amount) ){
            $children_price=$adult_price->sale_price-$children_discount_amount;
        }else{
            $children_price=$adult_price->sale_price-$adult_price->sale_price*$children_discount_percent/100;
        }
        $object_return=new stdClass();
        $object_return->children_under_year=$children_under_year;
        $object_return->children_price=$children_price;
        return  $object_return;
    }
    public static function get_adult_price_by_total_passenger_and_tsmart_excursion_addon_id($total_passenger = 1, $tsmart_excursion_addon_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_excursion_addon AS excursion_addon')
            ->where('excursion_addon.tsmart_excursion_addon_id=' . (int)$tsmart_excursion_addon_id);
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        //echo $query->dump();
        $excursion_addon = $db->setQuery($query)->loadObject();
        $excursion_addon->data_price = base64_decode($excursion_addon->data_price);
        $excursion_addon->data_price = up_json_decode($excursion_addon->data_price, false, 512, JSON_PARSE_JAVASCRIPT);
        $data_price = $excursion_addon->data_price;
        $adult_price=new stdClass();
        if ($data_price != null) {
            $data_price = $excursion_addon->data_price;
            $item_flat = $data_price->item_flat;
            $net_price = (float)($item_flat->net_price);
            $mark_up_amount = (float)($item_flat->mark_up_amount);
            $mark_up_percent = (float)($item_flat->mark_up_percent);
            $tax = $item_flat->tax;
            if ((float)($net_price) > 0) {
                $item_flat_mark_up_type = $data_price->item_flat_mark_up_type;
                $current_price = 0;
                if ($item_flat_mark_up_type == 'percent') {
                    $current_price = $net_price + ($net_price * $mark_up_percent) / 100;
                    $adult_price->net_price=$net_price;
                    $adult_price->mark_up=($net_price * $mark_up_percent) / 100;
                    $adult_price->tax=($current_price * $tax) / 100;
                    $adult_price->sale_price = $current_price + ($current_price * $tax) / 100;
                    return $adult_price;
                } else {
                    $current_price = $net_price + $mark_up_amount;
                    $adult_price->net_price=$net_price;
                    $adult_price->mark_up=$mark_up_amount;
                    $adult_price->tax=($current_price * $tax) / 100;
                    $adult_price->sale_price = $current_price + ($current_price * $tax) / 100;
                    return $adult_price;
                }
            } else {
                $items = $data_price->items;
                $item_mark_up_type = $data_price->item_mark_up_type;
                $item_cost=new stdClass();
                if($total_passenger<count($items)){
                    $item_cost=$items[$total_passenger-1];
                }else{
                    $item_cost=end($items);
                }

                $mark_up_amount = (float)($item_cost->mark_up_amount);
                $mark_up_percent = (float)($item_cost->mark_up_percent);
                $net_price = (float)($item_cost->net_price);
                $tax = (float)($item_cost->tax);
                if ($item_mark_up_type == 'percent') {
                    $item_sale_price = $net_price + ($net_price * $mark_up_percent) / 100;
                    $adult_price->net_price=$net_price;
                    $adult_price->mark_up=($net_price * $mark_up_percent) / 100;
                    $adult_price->tax=($item_sale_price * $tax) / 100;
                    $adult_price->sale_price = $item_sale_price + ($item_sale_price * $tax) / 100;
                    return $adult_price;
                } else {
                    $item_sale_price = $net_price + $mark_up_amount;
                    $adult_price->net_price=$net_price;
                    $adult_price->mark_up=$mark_up_amount;
                    $adult_price->tax=($item_sale_price * $tax) / 100;
                    $adult_price->sale_price = $item_sale_price + ($item_sale_price * $tax) / 100;
                    return $adult_price;
                }

            }
        }
    }


    public static function get_excursion_addon_by_tsmart_excursion_addon_id($tsmart_excursion_addon_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_excursion_addon AS excursion_addon')
            ->where('excursion_addon.tsmart_excursion_addon_id=' . (int)$tsmart_excursion_addon_id)
        ;
        $excursion = $db->setQuery($query)->loadObject();
        return $excursion;
    }




    public static function  get_min_price($excursion_addon){
        $date_price=$excursion_addon->data_price;
        $item_mark_up_type=$date_price->item_mark_up_type;
        $min_price=999999;

        $item_flat=$date_price->item_flat;
        if($item_flat->net_price>0){
            $net_price=$item_flat->net_price;
            $mark_up_percent=$item_flat->mark_up_percent;
            $mark_up_amount=$item_flat->mark_up_amount;
            $tax=$item_flat->tax;
            if($item_mark_up_type=='percent'){

                $min_price=$net_price+($net_price*$mark_up_percent)/100;
                $min_price=$min_price+($min_price*$tax)/100;
                return $min_price;
            }else{
                $min_price=$net_price+$mark_up_amount;
                $min_price=$min_price+($min_price*$tax)/100;
                return $min_price;
            }
        }else{
            $items=$date_price->items;
            foreach($items as $item){
                $net_price=$item->net_price;
                $mark_up_percent=$item->mark_up_percent;
                $mark_up_amount=$item->mark_up_amount;
                $tax=$item->tax;
                if($item_mark_up_type=='percent'){

                    $current_price=$net_price+($net_price*$mark_up_percent)/100;
                    $current_price=$current_price+($current_price*$tax)/100;
                }else{
                    $current_price=$net_price+$mark_up_amount;
                    $current_price=$current_price+($current_price*$tax)/100;
                }
                if($current_price<$min_price){
                    $min_price=$current_price;
                }
            }
            return $min_price;
        }
    }
    public static function get_list_excursion_addon_by_tour_id($tsmart_product_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('excursion_addon.*')
            ->from('#__tsmart_excursion_addon AS excursion_addon')
            ->leftJoin('#__tsmart_tour_id_excursion_addon_id AS tour_id_excursion_addon_id USING(tsmart_excursion_addon_id)')
            ->where('tour_id_excursion_addon_id.tsmart_product_id='.(int)$tsmart_product_id)
        ;

        $list_excursion=$db->setQuery($query)->loadObjectList();
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        foreach($list_excursion as &$excursion){
            $data_price=base64_decode($excursion->data_price);
            $data_price = up_json_decode($data_price, false, 512, JSON_PARSE_JAVASCRIPT);
            $excursion->data_price=$data_price;
        }

        return $list_excursion;
    }
    public static function get_excursion_addon_by_excursion_addon_id($tsmart_excursion_addon_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('excursion_addon.*')
            ->from('#__tsmart_excursion_addon AS excursion_addon')
            ->where('excursion_addon.tsmart_excursion_addon_id='.(int)$tsmart_excursion_addon_id)
        ;

        $excursion=$db->setQuery($query)->loadObject();
        return $excursion;
    }

    public static function get_list_excursion_payment_type()
    {
        $list_excursion_payment_type=array(
            'instant_payment'=>'Instant payment',
            'last_payment'=>'Last payment'
        );
        $a_list_excursion_payment_type=array();
        foreach($list_excursion_payment_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_excursion_payment_type[]=$a_item;
        }
        return $a_list_excursion_payment_type;

    }


}