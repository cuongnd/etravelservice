<<<<<<< master
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
class vmaccommodation
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
            ->from('#__virtuemart_accommodation')
            ;
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_hotel_selected_by_service_class_id_and_itinerary_id_accommodation_id($list_service_class, $virtuemart_itinerary_id,$virtuemart_accommodation_id)
    {
        $db=JFactory::getDbo();
        foreach($list_service_class as &$service_class) {
            $query = $db->getQuery(true);
            $query->select('hotel_id_service_class_id_accommodation_id.*')
                ->from('#__virtuemart_hotel_id_service_class_id_accommodation_id AS hotel_id_service_class_id_accommodation_id')
                ->where('hotel_id_service_class_id_accommodation_id.virtuemart_accommodation_id='.(int)$virtuemart_accommodation_id)
                ->where('hotel_id_service_class_id_accommodation_id.virtuemart_service_class_id='.(int)$service_class->virtuemart_service_class_id)
            ;
            $service_class->list_hotel=$db->setQuery($query)->loadObjectList();
        }
        return $list_service_class;
    }


=======
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
class vmaccommodation
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
            ->from('#__virtuemart_accommodation')
            ;
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_hotel_selected_by_service_class_id_and_itinerary_id_accommodation_id($list_service_class, $virtuemart_itinerary_id,$virtuemart_accommodation_id)
    {
        $db=JFactory::getDbo();
        foreach($list_service_class as &$service_class) {
            $query = $db->getQuery(true);
            $query->select('hotel_id_service_class_id_accommodation_id.*')
                ->from('#__virtuemart_hotel_id_service_class_id_accommodation_id AS hotel_id_service_class_id_accommodation_id')
                ->where('hotel_id_service_class_id_accommodation_id.virtuemart_accommodation_id='.(int)$virtuemart_accommodation_id)
                ->where('hotel_id_service_class_id_accommodation_id.virtuemart_service_class_id='.(int)$service_class->virtuemart_service_class_id)
            ;
            $service_class->list_hotel=$db->setQuery($query)->loadObjectList();
        }
        return $list_service_class;
    }


>>>>>>> local
}