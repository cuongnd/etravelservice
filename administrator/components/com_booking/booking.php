<?php
/**
 * @version     1.0.0
 * @package     com_booking
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      asianventure.com <info@asianventure.com> - http://asianventure.com
 */


// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_booking')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Booking');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
