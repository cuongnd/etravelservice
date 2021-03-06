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
* @version $Id: orders.php 5339 2012-01-30 16:42:50Z alatak $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * Orders table class
 * The class is is used to manage the orders in the shop.
 *
 * @package	tsmart
 * @author Max Milbers
 */
class TableInvoices extends tsmTable {

	/** @var int Primary key */
	var $tsmart_invoice_id = 0;

	var $tsmart_vendor_id = 0;

	var $tsmart_order_id = 0;

	var $invoice_number = '';

	var $order_status = '';

	var $xhtml = '';

	/**
	 *
	 * @author Max Milbers
	 * @param $db Class constructor; connect to the database
	 *
	 */
	function __construct($db) {
		parent::__construct('#__tsmart_invoices', 'tsmart_invoice_id', $db);

		$this->setUniqueName('invoice_number');
		$this->setLoggable();

		$this->setTableShortCut('inv');
	}

}

