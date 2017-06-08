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
class tsmroom
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_room()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_room')
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_room_by_hotel_id($tsmart_hotel_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_room AS room')
            ->where('room.tsmart_hotel_id='.(int)$tsmart_hotel_id)
        ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_room_booking_by_order_id($tsmart_order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_room_order AS room_order')
            ->where('room_order.tsmart_order_id='.(int)$tsmart_order_id)
        ;
        return $db->setQuery($query)->loadObjectList();
    }


    public static function get_list_room_id_by_itinerary_id($tsmart_itinerary_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tsmart_room_id')
            ->from('#__tsmart_itinerary_id_room_id')
            ->where('tsmart_itinerary_id='.(int)$tsmart_itinerary_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_list_room_by_itinerary_id($tsmart_itinerary_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('room.*')
            ->from('#__tsmart_itinerary_id_room_id AS itinerary_id_room_id')
            ->leftJoin('#__tsmart_room AS room ON room.tsmart_room_id=itinerary_id_room_id.tsmart_room_id')
            ->where('tsmart_itinerary_id='.(int)$tsmart_itinerary_id)
        ;
        return $db->setQuery($query)->loadObjectList();
    }



}