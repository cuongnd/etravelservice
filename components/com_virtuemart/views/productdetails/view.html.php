<?php

/**
 *
 * Product details view
 *
 * @package VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 9031 2015-10-29 20:20:33Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmView'))
    require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');

/**
 * Product details
 *
 * @package VirtueMart
 * @author Max Milbers
 */
class VirtueMartViewProductdetails extends VmView {

    /**
		 * Collect all data to show on the template
		 *
		 * @author RolandD, Max Milbers
		 */
		function display($tpl = null) {
            $virtuemart_product_idArray = vRequest::getInt('virtuemart_product_id', 0);
            if (is_array($virtuemart_product_idArray) and count($virtuemart_product_idArray) > 0) {
                $virtuemart_product_id = (int)$virtuemart_product_idArray[0];
            } else {
                $virtuemart_product_id = (int)$virtuemart_product_idArray;
            }
            $product_model = VmModel::getModel('product');
            $trip_model = VmModel::getModel('trip');
            $product = $product_model->getItem($virtuemart_product_id);
            $list_trip=$trip_model->getItem();
            parent::display($tpl);
        }
	function renderMailLayout ($doVendor, $recipient) {
		$tpl = VmConfig::get('order_mail_html') ? 'mail_html_notify' : 'mail_raw_notify';

		$this->doVendor=$doVendor;
		$this->fromPdf=false;
		$this->uselayout = $tpl;
		$this->subject = !empty($this->subject) ? $this->subject : vmText::_('COM_VIRTUEMART_CART_NOTIFY_MAIL_SUBJECT');
		$this->layoutName = $tpl;
		$this->setLayout($tpl);
		$this->isMail = true;
		$this->user=new stdClass();
		$this->user->name=$this->vendor->vendor_store_name;
		$this->user->email=$this->vendorEmail;
		parent::display();
	}
    public function showLastCategory($tpl) {
		$this->prepareContinueLink();
		parent::display ($tpl);
    }


}

// pure php no closing tag