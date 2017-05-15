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

class TsmartControllerbookprivategroupaddon extends JControllerLegacy {
    public function go_to_bookprivategroupsumary(){
        $app=JFactory::getApplication();
        $input=$app->input;
        $tsmart_price_id=$input->getInt('tsmart_price_id',0);
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

        $build_excursion_addon=$input->getString('build_excursion_addon','');
        $session->set('build_excursion_addon',$build_excursion_addon);

        $this->setRedirect(JRoute::_('index.php?option=com_tsmart&view=bookprivategroupsumary&tsmart_price_id='.$tsmart_price_id.'&booking_date='.$booking_date));
        return true;
    }

}