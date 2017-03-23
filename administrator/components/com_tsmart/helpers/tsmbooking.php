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
class tsmbooking
{
    /**
     * javascript strings
     *
     * @var    array
     * @since  11.1
     */
    protected static $strings = array();

    public function get_total_price(){
        $session = JFactory::getSession();
        $build_room = $session->get('build_room', '');

        $json_list_passenger = $session->get('json_list_passenger', '');

        $json_list_passenger = json_decode($json_list_passenger);
        $list_passenger = array_merge($json_list_passenger->senior_adult_teen, $json_list_passenger->children_infant);

        $build_room = json_decode($build_room);
        $build_pre_transfer = $session->get('build_pre_transfer', '');
        $build_post_transfer = $session->get('build_post_transfer', '');
        $extra_pre_night_hotel = $session->get('extra_pre_night_hotel', '');
        $extra_post_night_hotel = $session->get('extra_post_night_hotel', '');



        $total_price=0;
        foreach($list_passenger as $passenger){
            $total_price+=$passenger->tour_cost;
        }
        foreach($build_room as $room){
            $tour_cost_and_room_price=$room->tour_cost_and_room_price;
            foreach($tour_cost_and_room_price as $passenger){
                $total_price+=$passenger->room_price+$passenger->extra_bed_price;
            }
        }
        return $total_price;
    }


}