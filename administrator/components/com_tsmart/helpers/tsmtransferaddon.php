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
class tsmtransferaddon
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_list_transfer_type()
    {
        $list_transfer_type=array(
            'pre_transfer'=>'Pre transfer',
            'post_transfer'=>'Post transfer'
        );
        $a_list_transfer_type=array();
        foreach($list_transfer_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_transfer_type[]=$a_item;
        }
        return $a_list_transfer_type;
    }

    public static function get_activities()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__virtuemart_activity')
            ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_tour_id_by_transfer_addon_id($tsmart_transfer_addon_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('virtuemart_product_id')
            ->from('#__virtuemart_tour_id_transfer_addon_id')
            ->where('virtuemart_transfer_addon_id='.(int)$tsmart_transfer_addon_id)
        ;
        return $db->setQuery($query)->loadColumn();
    }

    public static function get_list_transfer_payment_type()
    {
        $list_transfer_payment_type=array(
            'instant_payment'=>'Instant payment',
            'last_payment'=>'Last transfer'
        );
        $a_list_transfer_payment_type=array();
        foreach($list_transfer_payment_type as $key=>$text)
        {
            $a_item=new stdClass();
            $a_item->value=$key;
            $a_item->text=$text;
            $a_list_transfer_payment_type[]=$a_item;
        }
        return $a_list_transfer_payment_type;

    }



}