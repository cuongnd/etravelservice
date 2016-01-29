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

jimport('joomla.application.component.controllerform');

/**
 * Booking controller class.
 */
class BookingControllerBooking extends JControllerForm
{

    function __construct() {
        $this->view_list = 'bookings';
        parent::__construct();
    }

}