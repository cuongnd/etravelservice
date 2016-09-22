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

class VirtueMartControllerbookprivategroup extends JControllerLegacy {
    public function go_to_booking_add_on_from(){
        $app=JFactory::getApplication();
        $session=JFactory::getSession();
        $input=$app->input;
        $virtuemart_price_id=$input->getInt('virtuemart_price_id',0);
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


        $this->setRedirect(JRoute::_('index.php?option=com_virtuemart&view=bookprivategroupaddon&virtuemart_price_id='.$virtuemart_price_id.'&booking_date='.$booking_date,false));
        return true;
    }

}