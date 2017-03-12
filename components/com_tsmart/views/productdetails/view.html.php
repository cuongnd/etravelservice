<?php

/**
 *
 * Product details view
 *
 * @package tsmart
 * @subpackage
 * @author RolandD
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
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
 * @package tsmart
 * @author Max Milbers
 */
class TsmartViewProductdetails extends VmView {

    /**
		 * Collect all data to show on the template
		 *
		 * @author RolandD, Max Milbers
		 */
		function display($tpl = null) {

            $tsmart_product_id = vRequest::getInt('tsmart_product_id', 0);
            if (is_array($tsmart_product_id) and count($tsmart_product_id) > 0) {
                $tsmart_product_id = (int)$tsmart_product_id[0];
            } else {
                $tsmart_product_id = (int)$tsmart_product_id;
            }
            $product_model = tmsModel::getModel('product');


            $this->product = $product_model->getItem($tsmart_product_id);
            require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
            $privategrouptrip_model = tmsModel::getModel('privategrouptrip');
            $this->state=$privategrouptrip_model->getState();
            $this->list_trip=$privategrouptrip_model->getItems();
            $this->start_date = $privategrouptrip_model->getState('filter.start_date');
            $group_size_helper = tsmHelper::getHepler('GroupSize');
            $this->product->list_group_size=$group_size_helper->get_list_group_size_by_tour_id($tsmart_product_id);
            parent::display($tpl);
        }
	function renderMailLayout ($doVendor, $recipient) {
		$tpl = tsmConfig::get('order_mail_html') ? 'mail_html_notify' : 'mail_raw_notify';

		$this->doVendor=$doVendor;
		$this->fromPdf=false;
		$this->uselayout = $tpl;
		$this->subject = !empty($this->subject) ? $this->subject : tsmText::_('com_tsmart_CART_NOTIFY_MAIL_SUBJECT');
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