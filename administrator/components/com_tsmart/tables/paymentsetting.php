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

if(!class_exists('tsmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtabledata.php');

/**
 * Currency table class
 * The class is is used to manage the currencies in the shop.
 *
 * @package		VirtueMart
 * @author RickG, Max Milbers
 */
class Tablepaymentsetting extends tsmTableData {

	/** @var int Primary key */
	var $virtuemart_paymentsetting_id				= 0;
	var $virtuemart_currency_id				= 0;
	var $credit_card_fee				= "";
	var $deposit_type				= "";
	var $deposit_amount				= "";
	var $balance_day_1				= "";
	var $balance_percent_1					= 0;
	var $balance_day_2				= "";
	var $balance_percent_2					= 0;
	var $balance_day_3				= "";
	var $balance_percent_3					= 0;
	var $config_mode					= '';
	var $deposit_term					= '';
	var $type					= 'default';
	var $hold_seat					= '';
	var $rule_note					= '';

	var $published				= 0;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__virtuemart_paymentsetting', 'virtuemart_paymentsetting_id', $db);


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
