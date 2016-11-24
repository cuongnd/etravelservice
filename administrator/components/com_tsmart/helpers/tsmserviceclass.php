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
class tsmserviceclass
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_tour_service_class()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_service_class')
        ;
        return $db->setQuery($query)->loadObjectList();
    }


    public static function get_list_service_class_ids_by_tour_id($tsmart_product_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('tsmart_service_class_id')
            ->from('#__tsmart_tour_id_service_class_id')
            ->where('tsmart_product_id='.(int)$tsmart_product_id)
            ;
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_list_service_class()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('service_class.*')
            ->from('#__tsmart_service_class AS service_class')
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_service_class_by_tour_id($tsmart_product_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('service_class.*')
            ->from('#__tsmart_tour_id_service_class_id')
            ->leftJoin('#__tsmart_service_class AS service_class USING(tsmart_service_class_id)')
            ->where('tsmart_product_id='.(int)$tsmart_product_id)
            ;
        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Translates a string into the current language. This just jText of joomla 2.5.x
     *
     * Examples:
     * <script>alert(Joomla.vmText._('<?php echo vmText::_("JDEFAULT", array("script"=>true));?>'));</script>
     * will generate an alert message containing 'Default'
     * <?php echo vmText::_("JDEFAULT");?> it will generate a 'Default' string
     *
     * @param   string $string The string to translate.
     * @param   mixed $jsSafe Boolean: Make the result javascript safe.
     * @param   boolean $interpretBackSlashes To interpret backslashes (\\=\, \n=carriage return, \t=tabulation)
     * @param   boolean $script To indicate that the string will be push in the javascript language store
     *
     * @return  string  The translated string or the key is $script is true
     *
     * @since   11.1
     */
    public static function get_list_group_size_by_tour_id($tour_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('group_size.tsmart_group_size_id,group_size.group_name')
            ->from('#__tsmart_tour_id_group_size_id AS tour_id_group_size_id')
            ->leftJoin('#__tsmart_group_size AS group_size ON group_size.tsmart_group_size_id=tour_id_group_size_id.tsmart_group_size_id')
            ->where('tour_id_group_size_id.tsmart_product_id=' . (int)$tour_id);
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_tour_departure_price_by_tour_departure_price_id($tour_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('group_size_id_tour_departure_price_id.*')
            ->from('#__tsmart_group_size_id_tour_departure_price_id AS group_size_id_tour_departure_price_id')
            ->where('group_size_id_tour_departure_price_id.tsmart_tour_departure_price_id=' . (int)$tour_price_id);
        return $db->setQuery($query)->loadObjectList('tsmart_group_size_id');
    }
    public static function get_list_tour_departure_price_by_tour_price_id_for_departure_price($tour_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('group_size_id_tour_departure_price_id.*')
            ->from('#__tsmart_group_size_id_tour_departure_price_id AS group_size_id_tour_departure_price_id')
            ->where('group_size_id_tour_departure_price_id.tsmart_tour_departure_price_id=' . (int)$tour_price_id);
        return $db->setQuery($query)->loadObjectList('tsmart_group_size_id');
    }

    public static function get_list_mark_up_by_tour_departure_price_id($tour_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('mark_up_tour_departure_price_id.*')
            ->from('#__tsmart_mark_up_tour_departure_price_id AS mark_up_tour_departure_price_id')
            ->where('mark_up_tour_departure_price_id.tsmart_tour_departure_price_id=' . (int)$tour_price_id);
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_departure_by_tour_departure_price_id($tour_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('group_size_id_tour_departure_price_id.*')
            ->from('#__tsmart_mark_up_tour_departure_net_price_id AS group_size_id_tour_departure_price_id')
            ->where('group_size_id_tour_departure_price_id.tsmart_tour_departure_price_id=' . (int)$tour_price_id);
        return $db->setQuery($query)->loadObject();
    }

}