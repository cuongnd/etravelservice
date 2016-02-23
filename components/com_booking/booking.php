<?php
/**
 * @version     1.0.0
 * @package     com_booking
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      asianventure.com <info@asianventure.com> - http://asianventure.com
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::register('BookingFrontendHelper', JPATH_COMPONENT . '/helpers/booking.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Booking');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
