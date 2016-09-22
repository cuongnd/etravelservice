<?php
/**
*
* State controller
*
* @package	VirtueMart
* @subpackage State
* @author jseros, RickG, Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: state.php 8615 2014-12-04 13:56:26Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

if(!class_exists('VirtueMartModelState')) require( VMPATH_ADMIN.DS.'models'.DS.'state.php' );

class VirtueMartControllerOrder extends JControllerLegacy {
    public function ajax_save_passenger(){
        $input=JFactory::getApplication()->input;
        $virtuemart_order_id=$input->getInt('virtuemart_order_id',0);
        $passenger_id=$input->getInt('passenger_id',0);
        $post=$input->getArray();
        $passenger_data=$post['data'];
        $order_mode=VmModel::getModel('orders');
        $orderTable=$order_mode->getTable('orders');
        $orderTable->load($virtuemart_order_id);
        $order_data=$orderTable->order_data;
        $order_data=json_decode($order_data);
        $list_passenger=$order_data->list_passenger;
        $start_index=0;
        foreach($list_passenger as $key=>$list_passenger1){
            for($i=0;$i<count($list_passenger1);$i++){
                if($start_index==$passenger_id){
                    $list_passenger->{$key}[$i]=(object)array_merge((array)$list_passenger->{$key}[$i],$passenger_data) ;
                }
                $start_index++;

            }
        }
        $order_data->list_passenger=$list_passenger;
        $orderTable->order_data=json_encode($order_data);
        if(!$orderTable->store())
        {
            echo "<pre>";
            print_r($orderTable, false);
            echo "</pre>";
            die;
        }
        echo 1;
        die;
    }
}