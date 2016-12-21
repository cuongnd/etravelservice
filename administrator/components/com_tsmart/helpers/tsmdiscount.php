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
class tsmdiscount
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_service_class_id_by_discount_id($tsmart_discount_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tsmart_service_class_id')
            ->from('#__tsmart_discount_id_service_class_id')
            ->where('tsmart_discount_id='.(int)$tsmart_discount_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_list_departure_id_by_discount_id($tsmart_discount_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tsmart_departure_id')
            ->from('#__tsmart_discount_id_departure_id')
            ->where('tsmart_discount_id='.(int)$tsmart_discount_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_detail_discount_by_discount_id($vituemart_discount_id=0){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('discount.*,cityarea.city_area_name')
            ->from('#__tsmart_discount AS discount')
            ->leftJoin('#__tsmart_cityarea AS cityarea USING(tsmart_cityarea_id)')
            ->where('discount.tsmart_discount_id='.(int)$vituemart_discount_id)
        ;

        return $db->setQuery($query)->loadObject();
    }

    public static function get_list_discount_payment_type()
    {
        $list_discount_payment_type=array(
            'instant_payment'=>'Instant payment',
            'last_payment'=>'Last transfer'
        );
        $a_list_discount_payment_type=array();
        foreach($list_discount_payment_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_discount_payment_type[]=$a_item;
        }
        return $a_list_discount_payment_type;

    }

    public static function get_list_discount_type()
    {
        $list_discount_type=array(
            'pre_transfer'=>'Pre night',
            'post_transfer'=>'Post night'
        );
        $a_list_discount_type=array();
        foreach($list_discount_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_discount_type[]=$a_item;
        }
        return $a_list_discount_type;

    }

    public static function get_list_discount_service_class()
    {
        $list_discount_type=array(
            'budget'=>'budget',
            'standard'=>'standard',
            'superior'=>'superior',
            'deluxe'=>'deluxe',
            'Luxury'=>'Luxury'
        );
        $a_list_discount_type=array();
        foreach($list_discount_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_discount_type[]=$a_item;
        }
        return $a_list_discount_type;
    }

    public static function get_discount_by_discount_code($discount_code)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('discounts.*')
            ->from('#__tsmart_discounts AS discounts')
            ->where('discounts.discount_code='.$query->q($discount_code))
            ->where('discounts.discount_start_date<='.$query->q($db->getNullDate()))
            ->where('discounts.discount_expiry_date>='.$query->q($db->getNullDate()))
            ->leftJoin('#__tsmart_orders AS orders ON orders.discount_code='.$query->q($discount_code))
            ->select('COUNT(orders.discount_code) AS total_use')
        ;
        $discount=$db->setQuery($query)->loadObject();
        if($discount->total_use>=$discount->discount_use_amout)
            return null;
        return $discount;
    }


}