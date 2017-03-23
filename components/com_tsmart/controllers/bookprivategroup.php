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
        $tsmart_departure_id=$input->getInt('tsmart_departure_id',0);
        $booking_date=$input->getString('booking_date','');
        $rooming=$input->getString('rooming','');

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
        $enable_book_addon=true;
        $price_helper=tsmHelper::getHepler('price');
        $product=$price_helper->get_product_by_tour_price_id($tsmart_price_id);
        $hoteladdon_helper=tsmHelper::getHepler('hoteladdon');
        $pre_night_hotel_group_min_price=$hoteladdon_helper->get_group_min_price($product->tsmart_product_id,$booking_date,'pre_night');
        $post_night_hotel_group_min_price=$hoteladdon_helper->get_group_min_price($product->tsmart_product_id,$booking_date,'post_night');

        $transferaddon_helper=tsmHelper::getHepler('transferaddon');

        $pre_transfer_group_min_price=$transferaddon_helper->get_min_price($product->tsmart_product_id,$booking_date,'pre_transfer');
        $post_transfer_group_min_price=$transferaddon_helper->get_min_price($product->tsmart_product_id,$booking_date,'post_transfer');

        $excursionaddon_helper=tsmHelper::getHepler('excursionaddon');
        $list_excursion=$excursionaddon_helper->get_list_excursion_addon_by_tour_id($product->tsmart_product_id);

        if(count($list_excursion)==0 && $pre_night_hotel_group_min_price==null && $post_night_hotel_group_min_price==null && $pre_transfer_group_min_price==null && $post_transfer_group_min_price==null){
            $enable_book_addon=false;
        }

        if($enable_book_addon)
        {
            if($tsmart_departure_id){
                $this->setRedirect(JRoute::_('index.php?option=com_tsmart&view=bookprivategroupaddon&tsmart_departure_id='.$tsmart_departure_id.'&booking_date='.$booking_date.'&rooming='.$rooming,false));
            }
            else{
                $this->setRedirect(JRoute::_('index.php?option=com_tsmart&view=bookprivategroupaddon&tsmart_price_id='.$tsmart_price_id.'&booking_date='.$booking_date.'&rooming='.$rooming,false));
            }

        }else{
            if($tsmart_departure_id)
            {
                $this->setRedirect(JRoute::_('index.php?option=com_tsmart&view=bookprivategroupsumary&tsmart_departure_id='.$tsmart_departure_id.'&booking_date='.$booking_date.'&rooming='.$rooming,false));
            }else{
                $this->setRedirect(JRoute::_('index.php?option=com_tsmart&view=bookprivategroupsumary&tsmart_price_id='.$tsmart_price_id.'&booking_date='.$booking_date.'&rooming='.$rooming,false));
            }

        }
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
        $item=$privategrouptrip_model->getItem($tsmart_price_id,$booking_date,'multi_price');
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
    public function ajax_get_transfer_book_private_group_by_date(){
        $app=JFactory::getApplication();
        $input=$app->input;
        $tsmart_product_id=$input->getInt('tsmart_product_id',0);
        $booking_date=$input->getString('booking_date','');
        $pickup_transfer_type=$input->getString('pickup_transfer_type','');
        $transfer_addon_helper=tsmHelper::getHepler('transferaddon');
        $transfer_addon=$transfer_addon_helper->get_transfer_addon($tsmart_product_id,$booking_date,$pickup_transfer_type);
        $response=new stdClass();
        $response->e=0;
        $response->transfer=$transfer_addon;
        echo json_encode($response);
        die;
    }
    public function ajax_get_extra_night_book_private_group_by_date(){
        $app=JFactory::getApplication();
        $input=$app->input;
        $tsmart_product_id=$input->getInt('tsmart_product_id',0);
        $booking_date=$input->getString('booking_date','');
        $extra_night_type=$input->getString('extra_night_type','');
        $hotel_addon_helper=tsmHelper::getHepler('hoteladdon');
        $hotel_addone=$hotel_addon_helper->get_extra_night_addon($tsmart_product_id,$booking_date,$extra_night_type);
        $response=new stdClass();
        $response->e=0;
        $response->hotel_addone=$hotel_addone;
        echo json_encode($response);
        die;
    }

}