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

class TsmartControllerreset extends JControllerLegacy {
    public function reset_password(){
        $input=JFactory::getApplication()->input;
        $data=$input->getArray();
        $user_model=tmsModel::getModel('user');
        if($user_model->reset_password($data,false)){
            $go_to=$data['go_to'];
            if($go_to=='last_booking')
            {
                $this->setRedirect('index.php?option=com_tsmart&controller=orders&task=go_to_last_booking');
            }else{
                $this->setRedirect('index.php?option=com_tsmart&view=order');
            }


        }else{

        }
    }
}