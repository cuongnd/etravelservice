<?php

/**
 *
 * Calc table ( for calculations)
 *
 * @package	tsmart
 * @subpackage Payment Methods
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: paymentmethods.php 8310 2014-09-21 17:51:47Z Milbo $
 */
defined('_JEXEC') or die();

if (!class_exists('tsmTable'))
    require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmtable.php');

/**
 * Calculator table class
 * The class is is used to manage the calculation in the shop.
 *
 * @author Max Milbers
 * @package		tsmart
 */
class TablePaymentmethods extends tsmTable {

    /** @var int Primary key */
    var $tsmart_paymentmethod_id = 0;

    /** @var string VendorID of the payment_method creator */
    var $tsmart_vendor_id = 0;

    /** @var id for the used plugin */
    var $payment_jplugin_id = 0;

    /** @var string Paymentmethod name */
    var $payment_name = '';
    /** @var string Element of paymentmethod */

    /** @var string payment  description */
    var $payment_desc = '';
    var $slug;
    var $payment_element = '';

    /** @var string parameter of the paymentmethod */
    var $payment_params = 0;

    /** @var string ordering */
    var $ordering = '';

    /** @var for all Vendors? */
    var $shared = 0;
    ////this must be forbidden to set for normal vendors, that means only setable Administrator permissions or vendorId=1
    /** @var int published or unpublished */
    var $published = 0;

    /**
     * @author Max Milbers
     * @param JDataBase $db
     */
    function __construct(&$db) {
	parent::__construct('#__tsmart_paymentmethods', 'tsmart_paymentmethod_id', $db);

	$this->setObligatoryKeys('payment_jplugin_id');
	$this->setObligatoryKeys('payment_name');
	$this->setLoggable();
	$this->setTranslatable(array('payment_name', 'payment_desc'));
	$this->setSlug('payment_name');
// 	$this->setUniqueName('ordering');
    }

}

// pure php no closing tag