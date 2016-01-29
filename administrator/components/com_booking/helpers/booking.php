<?php

/**
 * @version     1.0.0
 * @package     com_booking
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      asianventure.com <info@asianventure.com> - http://asianventure.com
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Booking helper.
 */
class BookingHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_BOOKING_TITLE_BOOKINGS'),
			'index.php?option=com_booking&view=bookings',
			$vName == 'bookings'
		);

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_booking';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
