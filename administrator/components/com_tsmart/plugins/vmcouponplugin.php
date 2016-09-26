<?php

/**
 * Abstract plugin class to extend the coupons
 *
 * @version $Id: vmcalculationplugin.php 4634 2011-11-09 21:07:44Z Milbo $
 * @package tsmart
 * @subpackage vmplugins
 * @copyright Copyright (C) 2011-2011 tsmart Team - All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 2,
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_tsmart/COPYRIGHT.php for copyright notices and details.
 *
 * http://tsmart.net
 *
 * @author Max Milbers
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!class_exists('vmPlugin')) require(VMPATH_PLUGINLIBS . DS . 'vmplugin.php');

abstract class vmCouponPlugin extends vmPlugin {


	function __construct(& $subject, $config) {

		parent::__construct($subject, $config);

		$this->_tablename = '#__tsmart_coupon_' . $this->_name;

	}


}