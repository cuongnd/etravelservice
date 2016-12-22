<?php
/**
 *
 * Controller for the front end Orderviews
 *
 * @package	tsmart
 * @subpackage User
 * @author Oscar van Eijk
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: orders.php 7821 2014-04-08 11:07:57Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * tsmart Component Controller
 *
 * @package		tsmart
 */
class TsmartControllerOrders extends JControllerLegacy
{

	/**
	 * Todo do we need that anylonger? that way.
	 * @see JController::display()
	 */
	public function display($cachable = false, $urlparams = false)  {

		$format = vRequest::getCmd('format','html');
		if  ($format == 'pdf') $viewName= 'pdf';
		else $viewName='orders';
		tsmConfig::loadJLang('com_tsmart_orders',TRUE);
		tsmConfig::loadJLang('com_tsmart_shoppers',TRUE);
		$view = $this->getView($viewName, $format);

		// Display it all
		$view->display();
	}
	public function go_to_last_booking(){
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('tsmart_order_id')
			->from('#__tsmart_orders')
			->order('tsmart_order_id DESC');
		$order_id=$db->setQuery($query)->loadResult();
		if($order_id){
			$this->setRedirect('index.php?option=com_tsmart&view=order&id='.$order_id.'&Itemid=140');
		}else{
			$this->setRedirect('index.php?option=com_tsmart&view=mypage');
		}
	}

}

// No closing tag
