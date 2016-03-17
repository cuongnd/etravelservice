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
            ->from('#__virtuemart_products AS products')
            ->innerJoin('#__virtuemart_products_en_gb AS products_en_gb USING(virtuemart_product_id)')
            ->group('products.virtuemart_product_id')
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_html_tour_information(&$view,$virtuemart_product_id=0)
    {
        $product_model=VmModel::getModel('product');
        $product = $product_model->getItem($virtuemart_product_id);
        $db=JFactory::getDbo();


        $query=$db->getQuery(true);
        $query->select('tour_service_class.service_class_name')
            ->from('#__virtuemart_service_class AS tour_service_class')
            ->leftJoin('#__virtuemart_tour_id_service_class_id  AS tour_id_service_class_id USING(virtuemart_service_class_id)')
            ->where('tour_id_service_class_id.virtuemart_product_id='.(int)$product->virtuemart_product_id)
            ;
        $product->list_tour_service_class=$db->setQuery($query)->loadColumn();



        $query=$db->getQuery(true);
        $query->select('countries.country_name')
            ->from('#__virtuemart_countries AS countries')
            ->leftJoin('#__virtuemart_tour_id_country_id  AS tour_id_country_id USING(virtuemart_country_id)')
            ->where('tour_id_country_id.virtuemart_product_id='.(int)$product->virtuemart_product_id)
            ;
        $product->list_country=implode(',',$db->setQuery($query)->loadColumn());


        $query=$db->getQuery(true);
        $query->select('cityarea.city_area_name')
            ->from('#__virtuemart_cityarea AS cityarea')
            ->where('cityarea.virtuemart_cityarea_id='.(int)$product->start_city)
            ;
        $product->start_city=$db->setQuery($query)->loadResult();


        $query=$db->getQuery(true);
        $query->select('tour_section.title')
            ->from('#__virtuemart_tour_section AS tour_section')
            ->where('tour_section.virtuemart_tour_section_id='.(int)$product->virtuemart_tour_section_id)
            ;
        $product->tour_section=$db->setQuery($query)->loadResult();

        $query=$db->getQuery(true);
        $query->select('cityarea.city_area_name')
            ->from('#__virtuemart_cityarea AS cityarea')
            ->where('cityarea.virtuemart_cityarea_id='.(int)$product->end_city)
            ;
        $product->end_city=$db->setQuery($query)->loadResult();

        $query=$db->getQuery(true);
        $query->select('tour_type.title')
            ->from('#__virtuemart_tour_type AS tour_type')
            ->where('tour_type.virtuemart_tour_type_id='.(int)$product->virtuemart_tour_type_id)
            ;
        $product->tour_type=$db->setQuery($query)->loadResult();


        $query=$db->getQuery(true);
        $query->select('tour_style.title')
            ->from('#__virtuemart_tour_style AS tour_style')
            ->where('tour_style.virtuemart_tour_style_id='.(int)$product->virtuemart_tour_style_id)
            ;
        $product->tour_style=$db->setQuery($query)->loadResult();


        $query=$db->getQuery(true);
        $query->select('physicalgrade.title')
            ->from('#__virtuemart_physicalgrade AS physicalgrade')
            ->where('physicalgrade.virtuemart_physicalgrade_id='.(int)$product->virtuemart_physicalgrade_id)
            ;
        $product->physicalgrade=$db->setQuery($query)->loadResult();



        $view->product=  $product;
        ob_start();
        include_once JPATH_ROOT.'/administrator/components/com_virtuemart/views/product/tmpl/productinformation.php';
        $content=ob_get_clean();
        return $content;


    }


}