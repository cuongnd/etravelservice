<?php
/**
 * Class for getting with language keys translated text. The original code was written by joomla Platform 11.1
 *
 * @package    VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

/**
 * Text handling class.
 *
 * @package     Joomla.Platform
 * @subpackage  Language
 * @since       11.1
 */
class vmHotelAddon
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_tour_id_by_hotel_addon_id($virtuemart_hotel_addon_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_product_id')
            ->from('#__virtuemart_tour_id_hotel_addon_id')
            ->where('virtuemart_hotel_addon_id='.(int)$virtuemart_hotel_addon_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_detail_hotel_by_hotel_id($vituemart_hotel_id=0){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('hotel.*,cityarea.city_area_name')
            ->from('#__virtuemart_hotel AS hotel')
            ->leftJoin('#__virtuemart_cityarea AS cityarea USING(virtuemart_cityarea_id)')
            ->where('hotel.virtuemart_hotel_id='.(int)$vituemart_hotel_id)
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
            'pre_transfer'=>'Pre night',
            'post_transfer'=>'Post night'
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