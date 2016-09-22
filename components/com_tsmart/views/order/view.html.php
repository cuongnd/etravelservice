<?php

/**
 *
 * Product details view
 *
 * @package VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 9031 2015-10-29 20:20:33Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmView'))
    require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');

/**
 * Product details
 *
 * @property  depatrure
 * @package VirtueMart
 * @author Max Milbers
 */
class virtuemartViewOrder extends VmView
{
    public $trip;
    public $product;
    public $privategrouptrip;

    /**
     * Collect all data to show on the template
     *
     * @author RolandD, Max Milbers
     */
    function display($tpl = null)
    {
        $user=JFactory::getUser();
        $app=JFactory::getApplication();

        if(!$user->id)
        {
            $uri=JFactory::getUri();
            $current_link=$uri->toString();
            $current_link=base64_encode($current_link);
            $app->redirect('index.php?option=com_virtuemart&view=user&layout=login&return='.$current_link);
            return false;
        }
        $input=$app->input;
        $order_id=$input->get('id',0);
        $order_mode=VmModel::getModel('orders');
        $orderTable=$order_mode->getTable('orders');
        $orderTable->load($order_id);
        $this->order=$orderTable->getProperties();
        $this->order=JArrayHelper::toObject($this->order);
        $order_data=$this->order->order_data;
        $this->order_data=json_decode($order_data);
        require_once JPATH_ROOT . '/libraries/php-loremipsum-master/src/LoremIpsum.php';
        $this->lipsum = new joshtronic\LoremIpsum();
        parent::display($tpl);
    }
    public function get_total_price($build_room,$build_pre_transfer,$build_post_transfer,$extra_pre_night_hotel,$extra_post_night_hotel){
        $total_price=0;
        foreach($build_room as $room){
            $tour_cost_and_room_price=$room->tour_cost_and_room_price;
            foreach($tour_cost_and_room_price as $passenger){
                $total_price+=$passenger->tour_cost+$passenger->room_price+$passenger->extra_bed_price;
            }
        }
        return $total_price;
    }


}

// pure php no closing tag