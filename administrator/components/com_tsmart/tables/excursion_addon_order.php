<?php
/**
*
* Currency table
*
* @package	tsmart
* @subpackage Currency
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
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
 * @package		tsmart
 * @author RickG, Max Milbers
 */
class TableExcursion_addon_order extends tsmTableData {

	/** @var int Primary key */
	var $tsmart_order_excursion_addon_id= 0;
	var $note= "";
	var $excursion_addon_data= null;
	var $adult_cost= 0;
	var $children_cost = 0;
	var $net_price = 0;
	var $group_excursion_payment = 0;
	var $mark_up = 0;
	var $tax = 0;
	var $tsmart_excursion_addon_id= null;
	var $children_under_year= 0;
	var $tsmart_order_id= null;
	var $reservation_notes= null;
	var $terms_condition= null;

	/**
	 * @author Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_excursion_addon_order', 'tsmart_order_excursion_addon_id', $db);


		//$this->setLoggable();

		//$this->setOrderable();
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
