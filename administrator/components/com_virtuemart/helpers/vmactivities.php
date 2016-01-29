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
class vmactivities
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_activities()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__virtuemart_activity')
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_activity_id_by_tour_id($virtuemart_product_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_activity_id')
            ->from('#__virtuemart_tour_id_activity_id')
            ->where('virtuemart_product_id='.(int)$virtuemart_product_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }



}