<?php
/**
*
* Users table
*
* @package	tsmart
* @subpackage User
* @author Max Milbers
* @link http://www.tsmart.net
* @copyright Copyright (c) 2010 - 2014 tsmart Team and authors. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: user_shoppergroup.php 2420 2010-06-01 21:12:57Z oscar $
*/

defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtabledata.php');


 class TableVmusers extends tsmTableData {

	/** @var int Vendor ID */
	var $tsmart_user_id		= 0;
	var $user_is_vendor 		= 0;
	var $tsmart_vendor_id 	= 0;
	var $customer_number 		= 0;
	var $tsmart_paymentmethod_id = 0;
	var $tsmart_shipmentmethod_id = 0;
	var $agreed					= 0;

	function __construct(&$db) {
		parent::__construct('#__tsmart_vmusers', 'tsmart_user_id', $db);

		$this->setPrimaryKey('tsmart_user_id');

		$this->setLoggable();

		$this->setTableShortCut('vmu');
	}

}
