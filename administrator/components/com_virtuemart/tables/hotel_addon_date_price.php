<?php
/**
*
* Currency table
*
* @package	VirtueMart
* @subpackage Currency
* @author RickG
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: currencies.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmtabledata.php');

/**
 * Currency table class
 * The class is is used to manage the currencies in the shop.
 *
 * @package		VirtueMart
 * @author RickG, Max Milbers
 */
class TableHotel_addon_date_price extends VmTableData {

	/** @var int Primary key */
	var $id				= 0;
	var $hotel_addon_type			= '';
	var $virtuemart_product_id			= 0;
	var $date				= null;
	var $single_room_net_price				= 0;
	var $virtuemart_hotel_addon_id				= 0;
	var $single_room_mark_up_percent				= 0;
	var $single_room_mark_up_amout				= 0;
	var $single_room_tax				= 0;
	var $doulble_twin_room_net_price				= 0;
	var $doulble_twin_room_mark_up_percent				= 0;
	var $doulble_twin_room_mark_up_amount				= 0;
	var $doulble_twin_room_tax				= 0;
	var $triple_room_net_price				= 0;
	var $triple_room_mark_up_percent				= 0;
	var $triple_room_mark_up_amout				= 0;
	var $triple_room_tax				= 0;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__virtuemart_hotel_addon_date_price', 'id', $db);

	}

	function check(){

		//$this->checkCurrencySymbol();
		return parent::check();
	}

	/**
	 * ATM Unused !
	 * Checks a allocation symbol wether it is a HTML entity.
	 * When not and $convertToEntity is true, it converts the symbol
	 * Seems not be used      ATTENTION   seems BROKEN, working only for euro, ...
	 *
	 */
}
// pure php no closing tag
