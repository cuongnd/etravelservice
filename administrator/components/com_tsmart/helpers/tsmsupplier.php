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
 * http://tsmart.net
 */

/**
 * Text handling class.
 *
 * @package     Joomla.Platform
 * @subpackage  Language
 * @since       11.1
 */
class vmsupplier
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_supplier_type()
    {
        $list_supplier_type=array(
            'company',
            'person'
        );
        $a_list_supplier_type=array();
        foreach($list_supplier_type as $item)
        {
            $a_item=new stdClass();
            $a_item->value=$item;
            $a_item->text=$item;
            $a_list_supplier_type[]=$a_item;
        }
        return $a_list_supplier_type;
    }

    public static function get_list_service_type()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__virtuemart_service_type')
            ;
        $db->setQuery($query);
        $list= $db->loadObjectList();
        return $list;
    }

    public static function get_list_state_province()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__virtuemart_states')
        ;
        $db->setQuery($query);
        $list= $db->loadObjectList();
        return $list;
    }

    public static function get_list_country()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__virtuemart_countries')
        ;
        $db->setQuery($query);
        $list= $db->loadObjectList();
        return $list;
    }

    public static function get_list_city_area()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__virtuemart_cityarea')
        ;
        $db->setQuery($query);
        $list= $db->loadObjectList();
        return $list;
    }


}