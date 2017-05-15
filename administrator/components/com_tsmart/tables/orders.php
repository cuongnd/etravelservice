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
class TableOrders extends tsmTableData {

	/** @var int Primary key */
	var $tsmart_order_id = 0;
	/** @var int User ID */
	var $tsmart_user_id = 0;
	/** @var int Vendor ID */
	var $tsmart_vendor_id = 0;
	var $object_id = 0;
	var $product_type = 0;
	var $assign_user_id = 0;
	var $tsmart_orderstate_id = null;
	var $receipt = 0;
	/** @var int Order number */
	var $order_number = NULL;
	var $order_pass = NULL;
	var $order_create_invoice_pass = 0;
	var $tsmart_custom_id = NULL;
	/** @var decimal Order total */
	var $order_total = 0.00000;
	/** @var decimal Products sales prices */
	var $order_salesPrice = 0.00000;
	/** @var decimal Order Bill Tax amount */
	var $order_billTaxAmount = 0.00000;
	/** @var string Order Bill Tax */
	var $order_billTax = 0;
	/** @var decimal Order Bill Tax amount */
	var $order_billDiscountAmount = 0.00000;
	/** @var decimal Order  Products Discount amount */
	var $order_discountAmount = 0.00000;
	/** @var decimal Order subtotal */
	var $order_subtotal = 0.00000;
	/** @var decimal Order tax */
	var $order_tax = 0.00000;
	var $order_data = '';

	/** @var decimal Shipment costs */
	var $order_shipment = 0.00000;
	/** @var decimal Shipment cost tax */
	var $order_shipment_tax = 0.00000;
	/** @var decimal Shipment costs */
	var $order_payment = 0.00000;
	/** @var decimal Shipment cost tax */
	var $order_payment_tax = 0.00000;
	/** @var decimal Coupon value */
	var $coupon_discount = 0.00000;
	/** @var string Coupon code */
	var $coupon_code = NULL;
	/** @var decimal Order discount */
	var $order_discount = 0.00000;
	/** @var string Order currency */
	var $order_currency = NULL;
	/** @var char Order status */
	var $order_status = NULL;
	/** @var char User currency id */
	var $user_currency_id = NULL;
	/** @var char User currency rate */
	var $user_currency_rate = NULL;
	/** @var int Payment method ID */
	var $tsmart_paymentmethod_id = NULL;
	/** @var int Shipment method ID */
	var $tsmart_shipmentmethod_id = NULL;
	/** @var string Users IP Address */
	var $ip_address = 0;
	/** @var char Order language */
	var $order_language = NULL;
	var $delivery_date = NULL;


	/**
	 *
	 * @author Max Milbers
	 * @param $db Class constructor; connect to the database
	 *
	 */
	function __construct($db) {
		parent::__construct('#__tsmart_orders', 'tsmart_order_id', $db);

		$this->setLoggable();

		$this->setTableShortCut('o');
	}

	function check(){



		return parent::check();
	}


	/**
	 * Overloaded delete() to delete records from order_userinfo and order payment as well,
	 * and write a record to the order history (TODO Or should the hist table be cleaned as well?)
	 *
	 * @var integer Order id
	 * @return boolean True on success
	 * @auhtor Max Milbers
	 * @author Kohl Patrick
	 */
	function delete( $id=null , $where = 0 ){

		$k = $this->_tbl_key;
		if ($id===null) {
			$id = $this->$k;
		}

		$this->_db->setQuery('DELETE from `#__tsmart_order_userinfos` WHERE `tsmart_order_id` = ' . (int)$id);
		if ($this->_db->execute() === false) {
			vmError($this->_db->getError());
			return false;
		}
		/*vm_order_payment NOT EXIST  have to find the table name*/
		$this->_db->setQuery( 'SELECT `payment_element` FROM `#__tsmart_paymentmethods` , `#__tsmart_orders`
			WHERE `#__tsmart_paymentmethods`.`tsmart_paymentmethod_id` = `#__tsmart_orders`.`tsmart_paymentmethod_id` AND `tsmart_order_id` = ' . $id );
		$payment_element = $this->_db->loadResult();
		if(!empty($payment_element)){
			$paymentTable = '#__tsmart_payment_plg_'.$payment_element ;
			$this->_db->setQuery('DELETE from `'.$paymentTable.'` WHERE `tsmart_order_id` = ' . $id);
			if ($this->_db->execute() === false) {
				vmError($this->_db->getError());
				return false;
			}
		}
				/*vm_order_shipment NOT EXIST  have to find the table name*/
		$this->_db->setQuery( 'SELECT `shipment_element` FROM `#__tsmart_shipmentmethods` , `#__tsmart_orders`
			WHERE `#__tsmart_shipmentmethods`.`tsmart_shipmentmethod_id` = `#__tsmart_orders`.`tsmart_shipmentmethod_id` AND `tsmart_order_id` = ' . $id );
		$shipmentName = $this->_db->loadResult();

		if(!empty($shipmentName)){
			$shipmentTable = '#__tsmart_shipment_plg_'. $shipmentName;
			$this->_db->setQuery('DELETE from `'.$shipmentTable.'` WHERE `tsmart_order_id` = ' . $id);
			if ($this->_db->execute() === false) {
				vmError('TableOrders delete Order shipmentTable = '.$shipmentTable.' `tsmart_order_id` = '.$id.' dbErrorMsg '.$this->_db->getError());
				return false;
			}
		}

		$_q = 'INSERT INTO `#__tsmart_order_histories` ('
				.	' tsmart_order_history_id'
				.	',tsmart_order_id'
				.	',order_status_code'
				.	',created_on'
				.	',customer_notified'
				.	',comments'
				.') VALUES ('
				.	' NULL'
				.	','.$id
				.	",'-'"
				.	',NOW()'
				.	',0'
				.	",'Order deleted'"
			.')';

		$this->_db->setQuery($_q);
		$this->_db->execute(); // Ignore error here
		return parent::delete($id);

	}

}

