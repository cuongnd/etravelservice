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
        $list_transfer_type = array(
            'pre_transfer' => 'Pre transfer',
            'post_transfer' => 'Post transfer'
        );
        $a_list_transfer_type = array();
        foreach ($list_transfer_type as $key => $text) {
            $a_item = new stdClass();
            $a_item->value = $key;
            $a_item->text = $text;
            $a_list_transfer_type[] = $a_item;
        }
        return $a_list_transfer_type;
    }
    public static function get_activities()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_activity');
        return $db->setQuery($query)->loadObjectList();
    }
    public static function get_list_tour_id_by_transfer_addon_id($tsmart_transfer_addon_id = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('tsmart_product_id')
            ->from('#__tsmart_tour_id_transfer_addon_id')
            ->where('tsmart_transfer_addon_id=' . (int)$tsmart_transfer_addon_id);
        return $db->setQuery($query)->loadColumn();
    }
    public static function get_transfer_addon_by_transfer_addon_id($tsmart_transfer_addon_id = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('transfer_addon.*')
            ->from('#__tsmart_transfer_addon AS transfer_addon')
            ->where('tsmart_transfer_addon_id=' . (int)$tsmart_transfer_addon_id);
        return $db->setQuery($query)->loadObject();
    }
    public static function get_min_price($tsmart_product_id = 0, $rule_date, $transfer_type = 'pre_transfer')
    {
        $rule_date = JFactory::getDate($rule_date);
        $config = tsmConfig::get_config();
        $params = $config->params;
        if ($transfer_type == 'pre_transfer') {
            $transfer_booking_days_allow = $params->get('pre_transfer_booking_days_allow', 1);
            $before_date = clone $rule_date;
            $before_date->modify("-$transfer_booking_days_allow day");
        } else {
            $transfer_booking_days_allow = $params->get('post_transfer_booking_days_allow', 1);
            $after_date = clone $rule_date;
            $after_date->modify("+$transfer_booking_days_allow day");
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_transfer_addon AS transfer_addon')
            ->leftJoin('#__tsmart_tour_id_transfer_addon_id AS tour_id_transfer_addon_id ON tour_id_transfer_addon_id.tsmart_transfer_addon_id=transfer_addon.tsmart_transfer_addon_id')
            ->where('transfer_addon.transfer_type =' . $query->q($transfer_type))
            ->where('tour_id_transfer_addon_id.tsmart_product_id=' . (int)$tsmart_product_id);
        if ($transfer_type == 'pre_transfer') {
            $query
                ->where('transfer_addon.vail_to >=' . $query->q($rule_date->toSql()));
                //->where('transfer_addon.vail_from <=' . $query->q($before_date->toSql()));
        }else if ($transfer_type == 'post_transfer') {
            $query
                ->where('transfer_addon.vail_from <=' . $query->q($rule_date->toSql()));
        }

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        //echo $query->dump();
        $list_transfer_addon = $db->setQuery($query)->loadObjectList();
        foreach ($list_transfer_addon as &$transfer_addon) {
            $transfer_addon->data_price = base64_decode($transfer_addon->data_price);
            $transfer_addon->data_price = up_json_decode($transfer_addon->data_price, false, 512, JSON_PARSE_JAVASCRIPT);
        }
        if (count($list_transfer_addon) == 0) {
            return null;
        }
        $min_price = 999999;
        for ($i = 0; $i < count($list_transfer_addon); $i++) {
            $transfer = $list_transfer_addon[$i];
            $data_price = $transfer->data_price;
            if ($data_price != null) {
                $data_price = $transfer->data_price;
                $item_flat = $data_price->item_flat;
                $net_price = (float)($item_flat->net_price);
                $mark_up_amount = (float)($item_flat->mark_up_amount);
                $mark_up_percent = (float)($item_flat->mark_up_percent);
                $tax = $item_flat->tax;
                if ((float)($net_price) > 0) {
                    $item_flat_mark_up_type = $data_price->item_flat_mark_up_type;
                    $current_price = 0;
                    if ($item_flat_mark_up_type == 'percent') {
                        $current_price = $net_price + ($net_price * $mark_up_percent) / 100;
                        $min_price = $current_price + ($current_price * $tax) / 100;
                        return $min_price;
                    } else {
                        $current_price = $current_price + $mark_up_amount;
                        $min_price = $current_price + ($current_price * $tax) / 100;
                        return $min_price;
                    }
                } else {
                    $items = $data_price->items;
                    $item_mark_up_type = $data_price->item_mark_up_type;
                    for ($i = 0; $i < count($items); $i++) {
                        $item = $items[$i];
                        $mark_up_amount = (float)($item->mark_up_amount);
                        $mark_up_percent = (float)($item->mark_up_percent);
                        $net_price = (float)($item->net_price);
                        $tax = (float)($item->tax);
                        if ($item_mark_up_type == 'percent') {
                            $item_sale_price = $net_price + ($net_price * $mark_up_percent) / 100;
                            $item_sale_price = $item_sale_price + ($item_sale_price * $tax) / 100;
                            if ($item_sale_price < $min_price) {
                                $min_price = $item_sale_price;
                            }
                        } else {
                            $item_sale_price = $net_price + $mark_up_amount;
                            $item_sale_price = $item_sale_price + ($item_sale_price * $tax) / 100;
                            if ($item_sale_price < $min_price) {
                                $min_price = $item_sale_price;
                            }
                        }
                    }
                    return $min_price;
                }
            }
        }
    }
    public static function get_transfer_addon($tsmart_product_id = 0, $booking_date, $pickup_transfer_type = 'pre_transfer')
    {
        $booking_date = JFactory::getDate($booking_date);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__tsmart_transfer_addon AS transfer_addon')
            ->leftJoin('#__tsmart_tour_id_transfer_addon_id AS tour_id_transfer_addon_id ON tour_id_transfer_addon_id.tsmart_transfer_addon_id=transfer_addon.tsmart_transfer_addon_id')
            ->where('transfer_addon.transfer_type=' . $query->q($pickup_transfer_type))
            ->where('tour_id_transfer_addon_id.tsmart_product_id=' . (int)$tsmart_product_id)
            ->where('transfer_addon.vail_from<=' . $query->q($booking_date->toSql()))
            ->where('transfer_addon.vail_to >=' . $query->q($booking_date->toSql()))
        ;
        $transfer = $db->setQuery($query)->loadObject();
        $transfer->data_price = base64_decode($transfer->data_price);
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $transfer->data_price = up_json_decode($transfer->data_price, false, 512, JSON_PARSE_JAVASCRIPT);
        return $transfer;
    }
    public static function get_list_transfer_payment_type()
    {
        $list_transfer_payment_type = array(
            'instant_payment' => 'Instant payment',
            'last_payment' => 'Last transfer'
        );
        $a_list_transfer_payment_type = array();
        foreach ($list_transfer_payment_type as $key => $text) {
            $a_item = new stdClass();
            $a_item->value = $key;
            $a_item->text = $text;
            $a_list_transfer_payment_type[] = $a_item;
        }
        return $a_list_transfer_payment_type;
    }
}