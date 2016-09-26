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
class tsmcities
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_cities()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('cityarea.*,CONCAT(cityarea.city_area_name,",",states.state_name) AS full_city,states.state_name,countries.country_name')
            ->leftJoin('#__tsmart_states AS states USING(tsmart_state_id)')
            ->leftJoin('#__tsmart_countries AS countries ON countries.tsmart_country_id=states.tsmart_country_id')
            ->from('#__tsmart_cityarea AS cityarea')
        ;
        $list = $db->setQuery($query)->loadObjectList();
        if (!$list) {
            throw new Exception($db->getErrorMsg(), 505);
        }
        return $list;
    }

    public static function get_list_city_by_state_id($tsmart_state_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_cityarea')
            ->leftJoin('#__tsmart_states AS states USING(tsmart_state_id)')
            ->where('states.tsmart_state_id='.(int)$tsmart_state_id)
        ;
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_city_state_country()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('cityarea.*,CONCAT(cityarea.city_area_name,",",states.state_name) AS full_city')
            ->leftJoin('#__tsmart_states AS states USING(tsmart_state_id)')
            ->leftJoin('#__tsmart_countries AS countries ON countries.tsmart_country_id=states.tsmart_country_id')
            ->from('#__tsmart_cityarea AS cityarea')
        ;
        $list = $db->setQuery($query)->loadAssocList();
        if (!$list) {
            throw new Exception($db->getErrorMsg(), 505);
        }
        return $list;
    }


}