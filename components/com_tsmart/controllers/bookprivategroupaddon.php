<?php
/**
*
* State controller
*
* @package	VirtueMart
* @subpackage State
* @author jseros, RickG, Max Milbers
* @link http://www.tsmart.net
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

class VirtueMartControllerbookprivategroupaddon extends JControllerLegacy {
    public function go_to_bookprivategroupsumary(){
        $app=JFactory::getApplication();
        $input=$app->input;
        $virtuemart_price_id=$input->getInt('virtuemart_price_id',0);
        $booking_date=$input->getString('booking_date','');

        $session=JFactory::getSession();
        $build_pre_transfer=$input->getString('build_pre_transfer','');
        $session->set('build_pre_transfer',$build_pre_transfer);
        $build_post_transfer=$input->getString('build_post_transfer','');
        $session->set('build_post_transfer',$build_post_transfer);
        $extra_pre_night_hotel=$input->getString('extra_pre_night_hotel','');
        $session->set('extra_pre_night_hotel',$extra_pre_night_hotel);
        $extra_post_night_hotel=$input->getString('extra_post_night_hotel','');
        $session->set('extra_post_night_hotel',$extra_post_night_hotel);

        $this->setRedirect(JRoute::_('index.php?option=com_virtuemart&view=bookprivategroupsumary&virtuemart_price_id='.$virtuemart_price_id.'&booking_date='.$booking_date));
        return true;
    }

}