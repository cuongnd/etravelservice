<?php
/**
*
* Orders table
*
* @package	tsmart
* @subpackage Orders
* @author RolandD
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: orders.php 8971 2015-09-07 09:35:42Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtabledata.php');

/**
 * Orders table class
 * The class is is used to manage the orders in the shop.
 *
 * @package	tsmart
 * @author Max Milbers
 */
class TablePassenger extends tsmTableData {

	/** @var int Primary key */
	var $tsmart_passenger_id = 0;
	var $tsmart_parent_passenger_id = null;
	/** @var int User ID */
	var $passenger_index = "";
	var $tsmart_order_id = null;
	var $first_name = "";
	/** @var int Vendor ID */
	var $middle_name = "";
	var $payment = 0;
	var $title = "";
	var $last_name = "";
	var $gender = "";

	var $date_of_birth  = "";
	var $nationality = "";
	var $passport_no  = null;
	var $issue_date = null;
	var $expiry_date = null;
	var $phone_no = "";
	var $email_address = "";
	var $res_country = "";
	var $street_address  = "";
	var $suburb_town = "";
	var $state_province = "";
	var $postcode_zip = "";
	var $emergency_contact_name = "";
	var $emergency_contact_email = "";
	var $emergency_contact_phone_no = "";
	var $pre_existing_medical_condition = "";
	var $special_meal_requirements = "";
	var $extra_fee = 0;
	var $is_temporary = 0;
	var $discount  = 0;
	var $cancel_fee   = 0;
	var $room_fee   = 0;
	var $tour_cost   = 0;
	var $tsmart_room_order_id   = null;
	var $tour_tsmart_passenger_state_id = null;


	var $pre_tsmart_order_hotel_addon_id = null;
	var $pre_night_hotel_fee = 0;

	var $post_tsmart_order_hotel_addon_id = null;
	var $post_night_hotel_fee = 0;


	var $pre_tsmart_order_transfer_addon_id = null;
	var $pre_transfer_fee = 0;

	var $post_tsmart_order_transfer_addon_id = null;
	var $post_transfer_fee = 0;


	/**
	 *
	 * @author Max Milbers
	 * @param $db Class constructor; connect to the database
	 *
	 */
	function __construct($db) {
		parent::__construct('#__tsmart_passenger', 'tsmart_passenger_id', $db);

		$this->setLoggable();

		$this->setTableShortCut('o');
	}

	function check(){



		return parent::check();
	}


}

