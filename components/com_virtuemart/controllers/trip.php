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

class VirtueMartControllerTrip extends JControllerLegacy {

    public function book_now()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $virtuemart_price_id=$input->getInt('virtuemart_price_id',0);
        $this->setRedirect(JRoute::_('index.php?option=com_virtuemart&view=book&virtuemart_price_id='.$virtuemart_price_id));
        return true;
    }
}