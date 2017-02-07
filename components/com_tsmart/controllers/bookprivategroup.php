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

class TsmartControllerbookprivategroup extends JControllerLegacy {
    public function go_to_booking_add_on_from(){
        $app=JFactory::getApplication();
        $session=JFactory::getSession();
        $input=$app->input;
        $tsmart_price_id=$input->getInt('tsmart_price_id',0);
        $booking_date=$input->getString('booking_date','');

        $session->set('booking_date',$booking_date);


        $json_list_passenger=$input->getString('json_list_passenger','');
        $session->set('json_list_passenger',$json_list_passenger);


        $build_room=$input->getString('build_room','');
        $session->set('build_room',$build_room);

        $list_passenger=$input->get('list_passenger',array(),'array');
        $list_passenger=json_encode($list_passenger);
        $session->set('list_passenger',$list_passenger);

        $contact_data=$input->getString('contact_data','');
        $contact_data=json_encode($contact_data);
        $session->set('contact_data',$contact_data);


        $this->setRedirect(JRoute::_('index.php?option=com_tsmart&view=bookprivategroupaddon&tsmart_price_id='.$tsmart_price_id.'&booking_date='.$booking_date,false));
        return true;
    }
    public function ajax_get_price_book_private_group(){
        $app=JFactory::getApplication();
        $input=$app->input;
        $total_senior_adult_teen=$input->getInt('total_senior_adult_teen',1);
        $tsmart_price_id=$input->getInt('tsmart_price_id',0);
        $booking_date=$input->getString('booking_date','');
        $privategrouptrip_model=tmsModel::getModel('privategrouptrip');
        $app->setUserState($privategrouptrip_model->getContext() . '.filter.total_passenger_from_12_years_old',$total_senior_adult_teen);
        $item=$privategrouptrip_model->getItem($tsmart_price_id,$booking_date);
        $view = $this->getView('bookprivategroup', 'html', 'TsmartView');
        $input->set('layout','default');
        $view->item=$item;
        ob_start();
        $view->display('price');
        $contents = ob_get_clean();
        $response=new stdClass();
        $response->e=0;
        $response->r_html=$contents;
        $response->item=$item;
        echo json_encode($response);
        die;
    }

}