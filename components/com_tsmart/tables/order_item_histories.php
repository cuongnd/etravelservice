<?php
/**
*
* Order item history table
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
* @version $Id: order_histories.php 3284 2011-05-18 20:52:40Z Electrocity $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Order history table class
 * The class is is used to manage the order history in the shop.
 *
 * @package	tsmart
 * @author RolandD
 */
class TableOrder_item_histories extends tsmTable {

	/** @var int Primary key */
	var $tsmart_order_item_history_id = 0;
	/** @var int Order ID */
	var $tsmart_order_item_id = 0;
	/** @var char Order status code */
	var $order_status_code = 0;
//	/** @var datetime Date added */
//	var $date_added = '0000-00-00 00:00:00';
	/** @var int Customer notified */
	var $customer_notified = 0;
	/** @var text Comments */
	var $comments = NULL;

	/**
	 * @param $db Class constructor; connect to the database
	 */
	function __construct($db) {
		parent::__construct('#__tsmart_order_item_histories', 'tsmart_order_item_history_id', $db);

		$this->setObligatoryKeys('tsmart_order_item_id');

		$this->setLoggable();
	}
}
// pure php no closing tag