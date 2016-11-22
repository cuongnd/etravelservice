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
class vmroom
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
            ->from('#__virtuemart_room')
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_room_id_by_itinerary_id($virtuemart_itinerary_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_room_id')
            ->from('#__virtuemart_itinerary_id_room_id')
            ->where('virtuemart_itinerary_id='.(int)$virtuemart_itinerary_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_list_room_by_itinerary_id($virtuemart_itinerary_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('room.*')
            ->from('#__virtuemart_itinerary_id_room_id AS itinerary_id_room_id')
            ->leftJoin('#__virtuemart_room AS room ON room.virtuemart_room_id=itinerary_id_room_id.virtuemart_room_id')
            ->where('virtuemart_itinerary_id='.(int)$virtuemart_itinerary_id)
        ;
        return $db->setQuery($query)->loadObjectList();
    }



}
=======
<?php/** * Class for getting with language keys translated text. The original code was written by joomla Platform 11.1 * * @package    VirtueMart * @subpackage Helpers * @author Max Milbers * @copyright Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved. * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved. * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php * VirtueMart is free software. This version may have been modified pursuant * to the GNU General Public License, and as distributed it includes or * is derivative of works licensed under the GNU General Public License or * other free or open source software licenses. * See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details. * * http://virtuemart.net *//** * Text handling class. * * @package     Joomla.Platform * @subpackage  Language * @since       11.1 */class tsmroom{    /**     * javascript strings     *     * @var    array     * @since  11.1     */    protected static $strings = array();    public static function get_list_room()    {        $db=JFactory::getDbo();        $query=$db->getQuery(true);        $query->select('*')            ->from('#__virtuemart_room')            ;        return $db->setQuery($query)->loadObjectList();    }    public static function get_list_room_id_by_itinerary_id($virtuemart_itinerary_id=0)    {        $db=JFactory::getDbo();        $query=$db->getQuery(true);        $query->select('virtuemart_room_id')            ->from('#__virtuemart_itinerary_id_room_id')            ->where('virtuemart_itinerary_id='.(int)$virtuemart_itinerary_id)        ;        return $db->setQuery($query)->loadColumn();    }    public static function get_list_room_by_itinerary_id($virtuemart_itinerary_id=0)    {        $db=JFactory::getDbo();        $query=$db->getQuery(true);        $query->select('room.*')            ->from('#__virtuemart_itinerary_id_room_id AS itinerary_id_room_id')            ->leftJoin('#__virtuemart_room AS room ON room.virtuemart_room_id=itinerary_id_room_id.virtuemart_room_id')            ->where('virtuemart_itinerary_id='.(int)$virtuemart_itinerary_id)        ;        return $db->setQuery($query)->loadObjectList();    }}
>>>>>>> local
