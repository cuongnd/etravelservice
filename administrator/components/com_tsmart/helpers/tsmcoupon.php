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
class tsmcoupon
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_service_class_id_by_coupon_id($tsmart_coupon_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tsmart_service_class_id')
            ->from('#__tsmart_coupon_id_service_class_id')
            ->where('tsmart_coupon_id='.(int)$tsmart_coupon_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_list_departure_id_by_coupon_id($tsmart_coupon_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tsmart_departure_id')
            ->from('#__tsmart_coupon_id_departure_id')
            ->where('tsmart_coupon_id='.(int)$tsmart_coupon_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_detail_coupon_by_coupon_id($vituemart_coupon_id=0){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('coupon.*,cityarea.city_area_name')
            ->from('#__tsmart_coupon AS coupon')
            ->leftJoin('#__tsmart_cityarea AS cityarea USING(tsmart_cityarea_id)')
            ->where('coupon.tsmart_coupon_id='.(int)$vituemart_coupon_id)
        ;

        return $db->setQuery($query)->loadObject();
    }

    public static function get_list_coupon_payment_type()
    {
        $list_coupon_payment_type=array(
            'instant_payment'=>'Instant payment',
            'last_payment'=>'Last transfer'
        );
        $a_list_coupon_payment_type=array();
        foreach($list_coupon_payment_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_coupon_payment_type[]=$a_item;
        }
        return $a_list_coupon_payment_type;

    }

    public static function get_list_coupon_type()
    {
        $list_coupon_type=array(
            'pre_transfer'=>'Pre night',
            'post_transfer'=>'Post night'
        );
        $a_list_coupon_type=array();
        foreach($list_coupon_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_coupon_type[]=$a_item;
        }
        return $a_list_coupon_type;

    }

    public static function get_list_coupon_service_class()
    {
        $list_coupon_type=array(
            'budget'=>'budget',
            'standard'=>'standard',
            'superior'=>'superior',
            'deluxe'=>'deluxe',
            'Luxury'=>'Luxury'
        );
        $a_list_coupon_type=array();
        foreach($list_coupon_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_coupon_type[]=$a_item;
        }
        return $a_list_coupon_type;
    }

    public static function get_coupon_by_coupon_code($coupon_code)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('coupons.*')
            ->from('#__tsmart_coupons AS coupons')
            ->where('coupons.coupon_code='.$query->q($coupon_code))
            ->where('coupons.coupon_start_date<='.$query->q($db->getNullDate()))
            ->where('coupons.coupon_expiry_date>='.$query->q($db->getNullDate()))
            ->leftJoin('#__tsmart_orders AS orders ON orders.coupon_code='.$query->q($coupon_code))
            ->select('COUNT(orders.coupon_code) AS total_use')
        ;
        $coupon=$db->setQuery($query)->loadObject();
        if($coupon->total_use>=$coupon->coupon_use_amout)
            return null;
        return $coupon;
    }


}