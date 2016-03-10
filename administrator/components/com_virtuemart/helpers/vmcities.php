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
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
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
class vmcities
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
        $query->select('*')
            ->from('#__virtuemart_cityarea')
            ;
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_city_by_state_id($virtuemart_state_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__virtuemart_cityarea')
            ->leftJoin('#__virtuemart_states AS states USING(virtuemart_state_id)')
            ->where('states.virtuemart_state_id='.(int)$virtuemart_state_id)
        ;
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_city_state_country()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('cityarea.*,CONCAT(cityarea.city_area_name,",",states.state_name) AS full_city')
            ->leftJoin('#__virtuemart_states AS states USING(virtuemart_state_id)')
            ->leftJoin('#__virtuemart_countries AS countries ON countries.virtuemart_country_id=states.virtuemart_country_id')
            ->from('#__virtuemart_cityarea AS cityarea')
        ;
        $list = $db->setQuery($query)->loadAssocList();
        if (!$list) {
            throw new Exception($db->getErrorMsg(), 505);
        }
        return $list;
    }


}