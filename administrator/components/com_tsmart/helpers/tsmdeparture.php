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
class tsmDeparture
{


    public static function get_list_tour_jont_group()
    {
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('product.*,products_en_gb.product_name')
            ->from('#__tsmart_products AS product')
            ->leftJoin('#__tsmart_products_en_gb AS products_en_gb USING(tsmart_product_id)')
            ->where('product.price_type='.$query->q(tsmGroupSize::FLAT_PRICE))
            ;
        $db->setQuery($query);
        return $db->loadObjectList();

    }
    public static function get_list_departure()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('departure.*')
            ->from('#__tsmart_departure AS departure')
            ->where('departure.tsmart_departure_parent_id is not NULL')
            ;
        $db->setQuery($query);
        return $db->loadObjectList();

    }
    public static function get_list_departure_exclude_parent_departure()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('departure.*')
            ->from('#__tsmart_departure AS departure')
            ->leftJoin('#__tsmart_service_class AS service_class ON service_class.tsmart_service_class_id=departure.tsmart_service_class_id')
            ->select('service_class.service_class_name')
            ->where('departure.tsmart_departure_parent_id is not NULL')
            ;
        $db->setQuery($query);
        return $db->loadObjectList();

    }
    public static function get_list_departure_by_tour_id($tsmart_product_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('departure.*')
            ->from('#__tsmart_departure AS departure')
            ->where('departure.tsmart_departure_parent_id is not NULL')
            ->where('tsmart_product_id='.(int)$tsmart_product_id)
            ;
        $db->setQuery($query);
        return $db->loadObjectList();

    }
    public static function get_list_departure_id_by_tour_id_exclude_parent_departure($tsmart_product_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('departure.tsmart_departure_id')
            ->from('#__tsmart_departure AS departure')
            ->where('departure.tsmart_departure_parent_id is not NULL')
            ->where('tsmart_product_id='.(int)$tsmart_product_id)
            ;
        $db->setQuery($query);
        return $db->loadColumn();

    }

    public static function get_format_departure_code($tsmart_departure_id,$day)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('departure.tsmart_departure_id')
            ->from('#__tsmart_departure AS departure')
            ->where('departure.tsmart_departure_id='.(int)$tsmart_departure_id)
            ->innerJoin('#__tsmart_products AS product ON product.tsmart_product_id=departure.tsmart_product_id')
            ->innerJoin('#__tsmart_products_en_gb AS products_en_gb ON products_en_gb.tsmart_product_id=product.tsmart_product_id')
            ->select('products_en_gb.product_name AS product_name')
            ->innerJoin('#__tsmart_service_class AS service_class ON service_class.tsmart_service_class_id=departure.tsmart_service_class_id')
            ->select('service_class.service_class_name')
        ;
        $db->setQuery($query);
        $departure_item=$db->loadObject();
        $departure_code=strtoupper(substr($departure_item->product_name,0,2).substr($departure_item->service_class_name,0,2)."SD".JUserHelper::genRandomPassword(2).$day->format('dm-y'));
        return $departure_code;



    }

}