<?php
/**
*
* Order item table
*
* @package	tsmart
* @subpackage Orders Order Calculation Rules
* @author valérie Isaksen
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: order_calc_rules.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Order calculation rules table class
 * The class is is used to manage the order items in the shop.
 *
 * @package	tsmart
 * @author Valerie Isaksen
 */
class TableOrder_calc_rules extends tsmTable {

	/** @var int Primary key */
	var $tsmart_order_calc_rule_id = 0;
	/** @var int Calculation ID */
	var $tsmart_calc_id = NULL;
	/** @var int Order ID */
	var $tsmart_order_id = NULL;

	/** @var int Vendor ID */
	var $tsmart_vendor_id = NULL;
	/** @var int Product ID */
	var $tsmart_order_item_id = NULL;
	/** @var string Calculation Rule name name */
	var $calc_rule_name = NULL;
	/** @var int Product Quantity */
	var $calc_kind = NULL;
	/** @var decimal Product item price */
	var $calc_amount = 0.00000;
	/** @var decimal Calculation Rule Result */
	var $calc_result = 0.00000;

	var $calc_mathop = NULL;
	var $calc_value = NULL;
	var $calc_currency = NULL;
	var $calc_params = NULL;

	/**
	 * @param $db Class constructor; connect to the database
	 */
	function __construct($db) {
		parent::__construct('#__tsmart_order_calc_rules', 'tsmart_order_calc_rule_id', $db);

		$this->setLoggable();
	}

}
// pure php no closing tag
