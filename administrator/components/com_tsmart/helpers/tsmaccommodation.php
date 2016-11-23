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
class tsmaccommodation
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_accommodation()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_accommodation')
            ;
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_hotel_selected_by_service_class_id_and_itinerary_id_accommodation_id($list_service_class, $tsmart_itinerary_id,$tsmart_accommodation_id)
    {
        $db=JFactory::getDbo();
        foreach($list_service_class as &$service_class) {
            $query = $db->getQuery(true);
            $query->select('hotel_id_service_class_id_accommodation_id.*')
                ->from('#__tsmart_hotel_id_service_class_id_accommodation_id AS hotel_id_service_class_id_accommodation_id')
                ->where('hotel_id_service_class_id_accommodation_id.tsmart_accommodation_id='.(int)$tsmart_accommodation_id)
                ->where('hotel_id_service_class_id_accommodation_id.tsmart_service_class_id='.(int)$service_class->tsmart_service_class_id)
            ;
            $list_hotel=$db->setQuery($query)->loadObjectList();

            $query = $db->getQuery(true);
            $query->select('room_id_service_class_id_accommodation_id.*')
                ->from('#__tsmart_room_id_service_class_id_accommodation_id AS room_id_service_class_id_accommodation_id')
                ->where('room_id_service_class_id_accommodation_id.tsmart_accommodation_id='.(int)$tsmart_accommodation_id)
                ->where('room_id_service_class_id_accommodation_id.tsmart_service_class_id='.(int)$service_class->tsmart_service_class_id)
            ;
            $list_room=$db->setQuery($query)->loadObjectList();
            if(count($list_room))
            {
                for($i=0;$i<count($list_hotel);$i++){
                    $list_hotel[$i]->room_item=$list_room[$i];
                }
            }

            $service_class->list_hotel=$list_hotel;
        }

        return $list_service_class;
    }


}