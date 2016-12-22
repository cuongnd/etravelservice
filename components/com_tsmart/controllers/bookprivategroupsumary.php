<?php
/**
*
* State controller
*
* @package	tsmart
* @subpackage State
* @author jseros, RickG, Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2014 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: state.php 8615 2014-12-04 13:56:26Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

if(!class_exists('tsmartModelState')) require( VMPATH_ADMIN.DS.'models'.DS.'state.php' );

class TsmartControllerbookprivategroupsumary extends JControllerLegacy {
    public function ajax_get_coupon(){
       $input=JFactory::getApplication()->input;
        $coupon_code=$input->getString('coupon_code','');
        $coupon_model=tmsModel::getModel('coupon');
        require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/vmcoupon.php';
        $coupon=tsmcoupon::get_coupon_by_coupon_code($coupon_code);
        echo json_encode($coupon);
        die;

    }

    public function go_to_pay_now(){
        $input=JFactory::getApplication()->input;
        $booking_summary=$input->getString('booking_summary','');
        $booking_summary=json_decode($booking_summary);
        $contact_data=$booking_summary->contact_data;
        $contact_data=json_decode($contact_data);
        $bookprivategroupsumary_model=tmsModel::getModel('bookprivategroupsumary');
        $email_address=$contact_data->email_address;
        $user_model=tmsModel::getModel('user');
        $table_user=JTable::getInstance('user');
        $table_user->parent_load(array('email'=>$email_address));
        $send_email=false;
        $new_member=false;
        if(!$table_user->id){
            $user_model->create_new_user_from_contact_data($contact_data,$send_email);
            $new_member=true;
        }
        $table_user->parent_load(array('email'=>$email_address));
        $order=$bookprivategroupsumary_model->save_order($booking_summary,$table_user->id);
        $bookprivategroupsumary_model->send_bookprivategroupsumary($booking_summary,$contact_data->email_address,$new_member,$order,$table_user->activation);
        die;
        $this->setRedirect(JRoute::_('index.php?option=com_tsmart&view=bookprivategroupsumaryalert'));
        return true;
    }
}