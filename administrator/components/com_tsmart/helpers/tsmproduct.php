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
class vmproduct
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_product()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('products_en_gb.*')
            ->from('#__tsmart_products AS products')
            ->innerJoin('#__tsmart_products_en_gb AS products_en_gb USING(tsmart_product_id)')
            ->group('products.tsmart_product_id')

            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_product_by_tour_type_id($tsmart_tour_type_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('products_en_gb.*')
            ->from('#__tsmart_products AS products')
            ->innerJoin('#__tsmart_products_en_gb AS products_en_gb USING(tsmart_product_id)')
            ->group('products.tsmart_product_id')
            ->where('products.tsmart_tour_type_id='.(int)$tsmart_tour_type_id)
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_html_tour_information(&$view,$tsmart_product_id=0)
    {
        $product_model=tmsModel::getModel('product');
        $product = $product_model->getItem($tsmart_product_id);
        $db=JFactory::getDbo();


        $query=$db->getQuery(true);
        $query->select('tour_service_class.service_class_name')
            ->from('#__tsmart_service_class AS tour_service_class')
            ->leftJoin('#__tsmart_tour_id_service_class_id  AS tour_id_service_class_id USING(tsmart_service_class_id)')
            ->where('tour_id_service_class_id.tsmart_product_id='.(int)$product->tsmart_product_id)
            ;
        $product->list_tour_service_class=$db->setQuery($query)->loadColumn();



        $query=$db->getQuery(true);
        $query->select('countries.country_name')
            ->from('#__tsmart_countries AS countries')
            ->leftJoin('#__tsmart_tour_id_country_id  AS tour_id_country_id USING(tsmart_country_id)')
            ->where('tour_id_country_id.tsmart_product_id='.(int)$product->tsmart_product_id)
            ;
        $product->list_country=implode(',',$db->setQuery($query)->loadColumn());


        $query=$db->getQuery(true);
        $query->select('cityarea.city_area_name')
            ->from('#__tsmart_cityarea AS cityarea')
            ->where('cityarea.tsmart_cityarea_id='.(int)$product->start_city)
            ;
        $product->start_city=$db->setQuery($query)->loadResult();


        $query=$db->getQuery(true);
        $query->select('tour_section.tour_section_name')
            ->from('#__tsmart_tour_section AS tour_section')
            ->where('tour_section.tsmart_tour_section_id='.(int)$product->tsmart_tour_section_id)
            ;
        $product->tour_section=$db->setQuery($query)->loadResult();

        $query=$db->getQuery(true);
        $query->select('cityarea.city_area_name')
            ->from('#__tsmart_cityarea AS cityarea')
            ->where('cityarea.tsmart_cityarea_id='.(int)$product->end_city)
            ;
        $product->end_city=$db->setQuery($query)->loadResult();

        $query=$db->getQuery(true);
        $query->select('tour_type.title')
            ->from('#__tsmart_tour_type AS tour_type')
            ->where('tour_type.tsmart_tour_type_id='.(int)$product->tsmart_tour_type_id)
            ;
        $product->tour_type=$db->setQuery($query)->loadResult();


        $query=$db->getQuery(true);
        $query->select('tour_style.title')
            ->from('#__tsmart_tour_style AS tour_style')
            ->where('tour_style.tsmart_tour_style_id='.(int)$product->tsmart_tour_style_id)
            ;
        $product->tour_style=$db->setQuery($query)->loadResult();


        $query=$db->getQuery(true);
        $query->select('physicalgrade.title')
            ->from('#__tsmart_physicalgrade AS physicalgrade')
            ->where('physicalgrade.tsmart_physicalgrade_id='.(int)$product->tsmart_physicalgrade_id)
            ;
        $product->physicalgrade=$db->setQuery($query)->loadResult();



        $view->product=  $product;
        ob_start();
        include_once JPATH_ROOT.'/administrator/components/com_tsmart/views/product/tmpl/productinformation.php';
        $content=ob_get_clean();
        return $content;


    }
    public static function get_list_tour_method()
    {
        $list_tour_method=array();


        $item=new stdClass();
        $item->value="tour_group";
        $item->text="Tour group";
        $list_tour_method[]=$item;




        $item=new stdClass();
        $item->value="tour_private";
        $item->text="Tour private";
        $list_tour_method[]=$item;

        return $list_tour_method;
    }


}