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
class tsmpassenger
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_passenger_status()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_passenger_states')
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_passenger_by_order_id($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_passenger')
            ->where('tsmart_order_id='.(int)$tsmart_order_id)
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_passenger_in_room_by_order_id($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_passenger')
            ->where('tsmart_order_id='.(int)$tsmart_order_id)
            ->where('tsmart_room_order_id >0')

            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_passenger_not_in_room_by_order_id($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_passenger')
            ->where('tsmart_order_id='.(int)$tsmart_order_id)
            ->where('tsmart_room_order_id is null')

            ;
        $list=$db->setQuery($query)->loadObjectList();
        return $list;
    }
    public static function get_list_passenger_in_temporary_by_order_id($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_passenger')
            ->where('tsmart_order_id='.(int)$tsmart_order_id)
            ->where('is_temporary =1')

            ;
        $list=$db->setQuery($query)->loadObjectList();
        return $list;
    }
    public static function get_list_passenger_not_in_temporary_by_order_id($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_passenger')
            ->where('tsmart_order_id='.(int)$tsmart_order_id)
            ->where('is_temporary =0')

            ;
        $list=$db->setQuery($query)->loadObjectList();
        return $list;
    }
    public static function get_list_passenger_not_in_temporary_and_not_room($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_passenger')
            ->where('tsmart_order_id='.(int)$tsmart_order_id)
            ->where('is_temporary =0')
            ->where('tsmart_room_order_id is null')

            ;
        $list=$db->setQuery($query)->loadObjectList();
        return $list;
    }
    public static function get_list_passenger_by_room_oder_id($tsmart_room_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_passenger')
            ->where('tsmart_room_order_id='.(int)$tsmart_room_order_id)
            ;
        $list=$db->setQuery($query)->loadObjectList();
        return $list;
    }
    public static function get_list_passenger_of_night_hotel_by_order_id($type="pre",$tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('passenger.*')
            ->from('#__tsmart_passenger AS passenger')
            ->leftJoin('#__tsmart_hotel_addon_order AS hotel_addon_order ON hotel_addon_order.tsmart_order_hotel_addon_id= passenger.'.$type.'_tsmart_order_hotel_addon_id')
            ->leftJoin('#__tsmart_hotel_addon AS hotel_addon ON hotel_addon.tsmart_hotel_addon_id= hotel_addon_order.tsmart_hotel_addon_id')
            ->leftJoin('#__tsmart_hotel AS hotel ON hotel.tsmart_hotel_id= hotel_addon.tsmart_hotel_id')
            ->select("hotel.hotel_name")
            ->select("hotel_addon_order.checkin_date")
            ->select("hotel_addon_order.checkout_date")
            ->select("hotel_addon_order.tsmart_order_hotel_addon_id")
            ->where('passenger.tsmart_order_id='.(int)$tsmart_order_id)
            ->where('passenger.'.$type.'_tsmart_order_hotel_addon_id!=0')

            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_night_hotel_by_order_id($type="pre",$tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('hotel_addon_order.*')
            ->from('#__tsmart_hotel_addon_order AS hotel_addon_order')
            ->leftJoin('#__tsmart_passenger  AS passenger ON passenger.'.$type.'_tsmart_order_hotel_addon_id=hotel_addon_order.tsmart_order_hotel_addon_id')
            ->leftJoin('#__tsmart_hotel_addon AS hotel_addon ON hotel_addon.tsmart_hotel_addon_id= hotel_addon_order.tsmart_hotel_addon_id')
            ->leftJoin('#__tsmart_hotel AS hotel ON hotel.tsmart_hotel_id= hotel_addon.tsmart_hotel_id')
            ->select("hotel.hotel_name")
            ->select("hotel_addon_order.checkin_date")
            ->select("hotel_addon_order.checkout_date")
            ->select("COUNT(passenger.tsmart_passenger_id) AS total_confirm")
            ->select("SUM(passenger.".$type."_night_hotel_fee) AS total_cost")
            ->select("hotel_addon_order.tsmart_order_hotel_addon_id")
            ->where('passenger.tsmart_order_id='.(int)$tsmart_order_id)
            ->where('passenger.'.$type.'_tsmart_order_hotel_addon_id!=0')
            ->group("hotel_addon_order.tsmart_order_hotel_addon_id")

            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_transfer_by_order_id($type="pre",$tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('transfer_addon_order.*')
            ->from('#__tsmart_transfer_addon_order AS transfer_addon_order')
            ->leftJoin('#__tsmart_passenger  AS passenger ON passenger.'.$type.'_tsmart_order_transfer_addon_id=transfer_addon_order.tsmart_order_transfer_addon_id')
            ->leftJoin('#__tsmart_transfer_addon AS transfer_addon ON transfer_addon.tsmart_transfer_addon_id= transfer_addon_order.tsmart_transfer_addon_id')
            ->select("transfer_addon.transfer_addon_name")
            ->select("transfer_addon_order.checkin_date")
            ->select("COUNT(passenger.tsmart_passenger_id) AS total_confirm")
            ->select("SUM(passenger.".$type."_transfer_fee) AS total_cost")
            ->select("transfer_addon_order.tsmart_order_transfer_addon_id")
            ->where('passenger.tsmart_order_id='.(int)$tsmart_order_id)
            ->where('passenger.'.$type.'_tsmart_order_transfer_addon_id!=0')
            ->group("transfer_addon_order.tsmart_order_transfer_addon_id")

            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_passenger_of_transfer_by_order_id($type="pre",$tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('passenger.*')
            ->from('#__tsmart_passenger AS passenger')
            ->leftJoin('#__tsmart_transfer_addon_order AS transfer_addon_order ON transfer_addon_order.tsmart_order_transfer_addon_id= passenger.'.$type.'_tsmart_order_transfer_addon_id')
            ->leftJoin('#__tsmart_transfer_addon AS transfer_addon ON transfer_addon.tsmart_transfer_addon_id= transfer_addon_order.tsmart_transfer_addon_id')
            ->select("transfer_addon.transfer_addon_name")
            ->select("transfer_addon_order.checkin_date")
            ->select("transfer_addon_order.tsmart_order_transfer_addon_id")
            ->where('passenger.tsmart_order_id='.(int)$tsmart_order_id)
            ->where('passenger.'.$type.'_tsmart_order_transfer_addon_id!=0')

            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_passenger_in_excursion_by_order_id($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('passenger.*')
            ->from('#__tsmart_passenger AS passenger')
            ->innerJoin('#__tsmart_excursion_addon_passenger_price_order AS excursion_addon_passenger_price_order ON excursion_addon_passenger_price_order.tsmart_passenger_id= passenger.tsmart_passenger_id')
            ->leftJoin('#__tsmart_excursion_addon_order AS excursion_addon_order ON excursion_addon_order.tsmart_order_excursion_addon_id= excursion_addon_passenger_price_order.tsmart_order_excursion_addon_id')
            ->leftJoin('#__tsmart_excursion_addon AS excursion_addon ON excursion_addon.tsmart_excursion_addon_id= excursion_addon_order.tsmart_excursion_addon_id')
            ->select("excursion_addon.excursion_addon_name")
            ->select("excursion_addon_order.tsmart_order_excursion_addon_id")
            ->select("excursion_addon_passenger_price_order.excusion_fee")
            ->where('passenger.tsmart_order_id='.(int)$tsmart_order_id)

        ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_excursion($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('excursion_addon_order.*')
            ->from('#__tsmart_excursion_addon_order AS excursion_addon_order')
            ->leftJoin('#__tsmart_excursion_addon_passenger_price_order AS excursion_addon_passenger_price_order ON excursion_addon_passenger_price_order.tsmart_order_excursion_addon_id= excursion_addon_order.tsmart_order_excursion_addon_id')
            ->leftJoin('#__tsmart_passenger  AS passenger ON passenger.tsmart_passenger_id= excursion_addon_passenger_price_order.tsmart_passenger_id')
            ->leftJoin('#__tsmart_excursion_addon AS excursion_addon ON excursion_addon.tsmart_excursion_addon_id= excursion_addon_order.tsmart_excursion_addon_id')
            ->select("excursion_addon.excursion_addon_name")
            ->select("excursion_addon_order.tsmart_order_excursion_addon_id")
            ->select("SUM(excursion_addon_passenger_price_order.excusion_fee) AS total_cost")
            ->select("COUNT(excursion_addon_passenger_price_order.tsmart_passenger_id) AS total_confirm")
            ->select("excursion_addon_passenger_price_order.excusion_fee")
            ->where('excursion_addon_order.tsmart_order_id='.(int)$tsmart_order_id)
            ->group("excursion_addon_order.tsmart_order_excursion_addon_id")

        ;
        return $db->setQuery($query)->loadObjectList();
    }

    public static function is_confirm($passenger)
    {
       return $passenger->tour_tsmart_passenger_state_id==2;
    }
    public static function get_list_gender()
    {
       return array(
           (object)array(
               value=>"male",
               text=>JText::_("Male")
           ),
           (object)array(
               value=>"female",
               text=>JText::_("Female")
           )
       );
    }



}