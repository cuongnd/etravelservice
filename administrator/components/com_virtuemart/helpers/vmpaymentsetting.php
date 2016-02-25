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
class vmpaymentsetting
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public static function get_config_mode()
    {
        $list_config_mode = array(
            'mode1',
            'mode2',
            'mode3',
            'mode4',
        );
        foreach ($list_config_mode as $key => $mode) {
            $list_config_mode[$mode] = $mode;
            unset($list_config_mode[$key]);
        }
        return $list_config_mode;
    }
    public static function get_hold_seat_type()
    {
        $list_hold_seat_type=array(
            'Accept',
            'no_accept'
        );
        $a_list_hold_seat_type=array();
        foreach($list_hold_seat_type as $item)
        {
            $a_item=new stdClass();
            $a_item->value=$item;
            $a_item->text=$item;
            $a_list_hold_seat_type[]=$a_item;
        }
        return $a_list_hold_seat_type;
    }


}