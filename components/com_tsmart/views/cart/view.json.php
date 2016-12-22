<?php

/**
 *
 * View for the shopping cart
 *
 * @package	tsmart
 * @subpackage
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2013 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 6292 2012-07-20 12:27:44Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmView'))require(VMPATH_SITE.DS.'helpers'.DS.'vmview.php');

/**
 * View for the shopping cart
 * @package tsmart
 * @author Max Milbers
 */
class TsmartViewCart extends VmView {

	public function display($tpl = null) {

		$layoutName = $this->getLayout();
		if (!$layoutName) $layoutName = vRequest::getCmd('layout', 'default');
		$this->assignRef('layoutName', $layoutName);

		if (!class_exists('tsmartCart'))
		require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$this->cart = tsmartCart::getCart();

    	$this->prepareContinueLink();
		if(!class_exists('VmTemplate')) require(VMPATH_SITE.DS.'helpers'.DS.'vmtemplate.php');
		VmTemplate::setVmTemplate($this, 0, 0, $layoutName);

		parent::display($tpl);
	}


}

//no closing tag
