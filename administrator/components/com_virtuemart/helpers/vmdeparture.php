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
class vmDeparture
{


    public static function get_list_tour_jont_group()
    {
        require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmgroupsize.php';
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('product.*,products_en_gb.product_name')
            ->from('#__virtuemart_products AS product')
            ->leftJoin('#__virtuemart_products_en_gb AS products_en_gb USING(virtuemart_product_id)')
            ->where('product.price_type='.$query->q(vmGroupSize::FLAT_PRICE))
            ;
        $db->setQuery($query);
        return $db->loadObjectList();

    }
}