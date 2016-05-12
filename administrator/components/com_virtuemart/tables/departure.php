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
class TableDeparture extends VmTableData {

	/** @var int Primary key */
	var $virtuemart_departure_id				= 0;
	var $min_space				= 0;
	var $departure_name				= 0;
	var $departure_date				= 0;
	var $max_space				= 0;
	var $sale_period_open				= null;
	var $sale_period_close				= null;
	var $vail_period_from				= null;
	var $vail_period_to				= null;
	var $virtuemart_service_class_id				= 0;
	var $virtuemart_product_id				= 0;
	var $start_date				= 0;
	var $end_date				= 0;
	var $g_guarantee				= 0;
	var $limited_space				= 0;
	var $shared					= 0;
	var $published				= 0;
	var $senior_price				= 0;
	var $adult_price				= 0;
	var $teen_price				= 0;
	var $children1_price				= 0;
	var $children2_price				= 0;
	var $infant_price				= 0;
	var $private_room_price				= 0;
	var $senior_departure_price				= 0;
	var $adult_departure_price				= 0;
	var $teen_departure_price				= 0;
	var $children1_departure_price				= 0;
	var $children2_departure_price				= 0;
	var $infant_departure_price				= 0;
	var $private_room_departure_price				= 0;
	var $days_seleted				= '';
	var $sale_period_from				= '';
	var $sale_period_to				= '';
	var $date_type				= '';
	var $weekly				= '';
	var $allow_passenger				= '';

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__virtuemart_departure', 'virtuemart_departure_id', $db);

		$this->setUniqueName('departure_name');

		$this->setLoggable();

		$this->setOrderable();
	}

	function check(){

		//$this->checkCurrencySymbol();
		return parent::check();
	}

	/**
	 * ATM Unused !
	 * Checks a departure symbol wether it is a HTML entity.
	 * When not and $convertToEntity is true, it converts the symbol
	 * Seems not be used      ATTENTION   seems BROKEN, working only for euro, ...
	 *
	 */
}
// pure php no closing tag
