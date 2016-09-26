<?php

/**
 *
 * Product table
 *
 * @package	tsmart
 * @subpackage Product
 * @author RolandD
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_prices.php 8398 2014-10-09 10:02:58Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('tsmTableData'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmtabledata.php');

/**
 * Product table class
 * The class is is used to manage the products in the shop.
 *
 * @package	tsmart
 * @author RolandD
 * @author Max Milbers
 */
class TableProduct_prices extends tsmTableData {

    /** @var int Primary key */
    var $tsmart_product_price_id = 0;
    /** @var int Product id */
    var $tsmart_product_id = 0;
    /** @var int Shopper group ID */
    var $tsmart_shoppergroup_id = null;

    /** @var string Product price */
    var $product_price = null;
    var $override = null;
    var $product_override_price = null;
    var $product_tax_id = null;
    var $product_discount_id = null;

    /** @var string Product currency */
    var $product_currency = null;

    var $product_price_publish_up = 0;
    var $product_price_publish_down = 0;

    /** @var int Price quantity start */
    var $price_quantity_start = null;
    /** @var int Price quantity end */
    var $price_quantity_end = null;

    /**
     * @author RolandD
     * @param JDataBase $db
     */
    function __construct(&$db) {
        parent::__construct('#__tsmart_product_prices', 'tsmart_product_price_id', $db);

        $this->setPrimaryKey('tsmart_product_price_id');
		$this->setLoggable();
		$this->setTableShortCut('pp');
		$this->_updateNulls = true;
    }

    /**
     * @author Max Milbers
     * @param
     */

	function check(){

		if(isset($this->product_price)){
			$this->product_price = str_replace(array(',',' '),array('.',''),$this->product_price);
		}

		if(isset($this->product_override_price)){
			$this->product_override_price = str_replace(array(',',' '),array('.',''),$this->product_override_price);
		}

		return parent::check();
	}

}

// pure php no closing tag
