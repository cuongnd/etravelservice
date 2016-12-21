<?php
/**
*
* discount table
*
* @package	tsmart
* @subpackage discount
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: discounts.php 8955 2015-08-19 12:58:20Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTable'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtable.php');

/**
 * discount table class
 * The class is is used to manage the discounts in the shop.
 *
 * @package		tsmart
 * @author RickG
 */
class Tablediscounts extends tsmTable {

	/** @var int Primary key */
	var $tsmart_discount_id			 	= 0;
	var $tsmart_vendor_id	= 0;
	/** @var varchar discount name */
	var $discount_code         	= '';
	var $discount_name         	= '';
	/** @var string discount percentage or total */
	var $percent_or_total    	= 'percent';
	var $model_price    	= 'flat_price';
	/** @var string discount type */
	var $discount_type		    = 'gift';
	/** @var Decimal discount value */
	var $discount_value 			= '';
	var $image 			= '';
	/** @var datetime discount start date */
	var $discount_start_date 		= '';
	/** @var datetime discount expiry date */
	var $discount_expiry_date 	= '';
	/** @var decimal discount valid value */
	var $discount_value_valid 	= 0;
	var $tsmart_product_id 	= 0;
	var $exclude_coupon_product 	= 0;
	var $exclude_discount_product 	= 0;
	/** @var decimal discount valid value */
	var $discount_used			= 0;
	var $description			= "";
	var $use_per_customer			= 0;
	var $private_or_joingroup			= '';
	/**
	 * @author RickG, Max Milbers
	 * @param JDataBase $db
	 */
	function __construct(&$db)
	{
		parent::__construct('#__tsmart_discounts', 'tsmart_discount_id', $db);
		$this->setObligatoryKeys('discount_code');
		$this->setLoggable();
	}


}
// pure php no closing tag
