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
class vmpromotion
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

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
    public static function get_list_group_size_by_tour_id($virtuemart_product_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('group_size.virtuemart_group_size_id,CONCAT(group_size.group_name,"(",group_size.from,"-",group_size.to,")") AS group_name,group_size.type AS group_type')
            ->from('#__virtuemart_tour_id_group_size_id AS tour_id_group_size_id')
            ->leftJoin('#__virtuemart_group_size AS group_size ON group_size.virtuemart_group_size_id=tour_id_group_size_id.virtuemart_group_size_id')
            ->where('tour_id_group_size_id.virtuemart_product_id='.(int)$virtuemart_product_id)
            ->where('group_size.virtuemart_group_size_id!=0')
            ->order('group_size.from')

        ;
        $model_product = VmModel::getModel('product');
        $product=$model_product->getItem($virtuemart_product_id);

        if($product->price_type=='multi_price')
        {
            $query->where('group_size.type!='.$query->q('flat_price'));
        }
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_tour_promotion_price_by_tour_promotion_price_id($tour_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('group_size_id_tour_promotion_price_id.*')
            ->from('#__virtuemart_group_size_id_tour_promotion_price_id AS group_size_id_tour_promotion_price_id')
            ->where('group_size_id_tour_promotion_price_id.virtuemart_promotion_price_id=' . (int)$tour_price_id);
        return $db->setQuery($query)->loadObjectList('virtuemart_group_size_id');
    }
    public static function get_list_tour_promotion_price_by_tour_price_id_for_promotion_price($virtuemart_promotion_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('group_size_id_tour_promotion_price_id.*')
            ->from('#__virtuemart_group_size_id_tour_promotion_price_id AS group_size_id_tour_promotion_price_id')
            ->where('group_size_id_tour_promotion_price_id.virtuemart_promotion_price_id=' . (int)$virtuemart_promotion_price_id)
        ;
        return $db->setQuery($query)->loadObjectList('virtuemart_group_size_id');
    }

    public static function get_list_mark_up_by_tour_promotion_price_id($tour_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('mark_up_tour_promotion_price_id.*')
            ->from('#__virtuemart_mark_up_tour_promotion_price_id AS mark_up_tour_promotion_price_id')
            ->where('mark_up_tour_promotion_price_id.virtuemart_promotion_price_id=' . (int)$tour_price_id);
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_promotion_by_tour_promotion_price_id($tour_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('group_size_id_tour_promotion_price_id.*')
            ->from('#__virtuemart_mark_up_tour_promotion_net_price_id AS group_size_id_tour_promotion_price_id')
            ->where('group_size_id_tour_promotion_price_id.virtuemart_promotion_price_id=' . (int)$tour_price_id);
        return $db->setQuery($query)->loadObjectList();
    }

    public static function get_list_markup_promotion_price_by_tour_promotion_price_id($price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('mark_up_tour_promotion_price_id.*')
            ->from('#__virtuemart_mark_up_tour_promotion_price_id AS mark_up_tour_promotion_price_id')
            ->where('mark_up_tour_promotion_price_id.virtuemart_promotion_price_id=' . (int)$price_id);
        return $db->setQuery($query)->loadObjectList();

    }

    public static function get_product_by_promotion_price_id($virtuemart_promotion_price_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('product.*')
            ->from('#__virtuemart_products AS product')
            ->innerJoin('#__virtuemart_tour_promotion_price AS tour_promotion_price ON tour_promotion_price.virtuemart_product_id=product.virtuemart_product_id')
            ->where('tour_promotion_price.virtuemart_promotion_price_id=' . (int)$virtuemart_promotion_price_id);
        return $db->setQuery($query)->loadObject();
    }

    public static function get_sale_promotion_price_by_mark_up_and_tax($promotion_price, $mark_up_promotion_amount, $mark_up_promotion_percent, $mark_up_promotion_type, $mark_up_promotion_net_amount, $mark_up_promotion_net_percent, $mark_up_promotion_net_type, $tax)
    {
        $promotion_price1= $mark_up_promotion_type=='amount'?$promotion_price+$mark_up_promotion_amount:$promotion_price+($promotion_price*$mark_up_promotion_percent)/100;
        $mark_up_promotion_price= $mark_up_promotion_net_type=='amount'?$promotion_price+$mark_up_promotion_net_amount:$promotion_price+($promotion_price*$mark_up_promotion_net_percent)/100;
        $promotion_price=$promotion_price-$promotion_price1+$mark_up_promotion_price;
        return $promotion_price+($promotion_price*$tax)/100;
    }

}