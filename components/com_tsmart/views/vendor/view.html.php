<?php

/**
 *
 * List/add/edit/remove Vendors
 *
 * @package	tsmart
 * @subpackage User
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 5133 2011-12-19 12:02:41Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmView'))require(VMPATH_SITE.DS.'helpers'.DS.'vmview.php');

// Set to '0' to use tabs i.s.o. sliders
// Might be a config option later on, now just here for testing.
define('__VM_USER_USE_SLIDERS', 0);

/**
 * HTML View class for maintaining the list of users
 *
 * @package	tsmart
 * @subpackage Vendor
 * @author Max Milbers
 */
class TsmartViewVendor extends VmView {

	/**
	 * Displays the view, collects needed data for the different layouts
	 *
	 * Okey I try now a completly new idea.
	 * We make a function for every tab and the display is getting the right tabs by an own function
	 * putting that in an array and after that we call the preparedataforlayoutBlub
	 *
	 * @author Max Milbers
	 */
	function display($tpl = null) {

		$document = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		$layoutName = $this->getLayout();

		$model = tmsModel::getModel();

		$tsmart_vendor_id = vRequest::getInt('tsmart_vendor_id',1);

// 		if ($layoutName=='default') {
		if (empty($tsmart_vendor_id)) {
			$document->setTitle( tsmText::_('com_tsmart_VENDOR_LIST') );
			$pathway->addItem(tsmText::_('com_tsmart_VENDOR_LIST'));

			$vendors = $model->getVendors();
			$model->addImages($vendors);

			$this->assignRef('vendors', $vendors);

		} else {

			$vendor = $model->getVendor($tsmart_vendor_id);
			$model->addImages($vendor);
			if (tsmConfig::get ('enable_content_plugin', 0)) {
				if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
				shopFunctionsF::triggerContentPlugin($vendor, 'vendor','vendor_store_desc');
				shopFunctionsF::triggerContentPlugin($vendor, 'vendor','vendor_terms_of_service');
			}
			$this->assignRef('vendor', $vendor);

			if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
			$userId = tsmartModelVendor::getUserIdByVendorId($tsmart_vendor_id);

			if ($layoutName=='tos') {
				$document->setTitle( tsmText::_('com_tsmart_VENDOR_TOS') );
				$pathway->addItem(tsmText::_('com_tsmart_VENDOR_TOS'));
			}
			elseif ($layoutName=='contact') {
				$user = JFactory::getUser();
				$document->setTitle( tsmText::_('com_tsmart_VENDOR_CONTACT') );
				$pathway->addItem(tsmText::_('com_tsmart_VENDOR_CONTACT'));
				$this->assignRef('user', $user);

			} else {
				$document->setTitle( tsmText::_('com_tsmart_VENDOR_DETAILS') );
				$pathway->addItem(tsmText::_('com_tsmart_VENDOR_DETAILS'));
				$this->setLayout('details');
			}

			$linkdetails = '<a href="'.JRoute::_('index.php?option=com_tsmart&view=vendor&layout=details&tsmart_vendor_id=' .
				$tsmart_vendor_id, FALSE).'">'.tsmText::_('com_tsmart_VENDOR_DETAILS').'</a>';
			$linkcontact = '<a href="'.JRoute::_('index.php?option=com_tsmart&view=vendor&layout=contact&tsmart_vendor_id=' . $tsmart_vendor_id, FALSE).'">'.tsmText::_('com_tsmart_VENDOR_CONTACT').'</a>';
			$linktos = '<a href="'.JRoute::_('index.php?option=com_tsmart&view=vendor&layout=tos&tsmart_vendor_id=' . $tsmart_vendor_id, FALSE).'">'.tsmText::_('com_tsmart_VENDOR_TOS').'</a>';

			$this->assignRef('linkdetails', $linkdetails);
			$this->assignRef('linkcontact', $linkcontact);
			$this->assignRef('linktos', $linktos);
		}

		parent::display($tpl);

	}


	function renderMailLayout($doVendor, $recipient) {

		$this->setLayout('mail_html_question');
		$this->comment = vRequest::getString('comment');
		$tsmart_vendor_id = vRequest::getInt('tsmart_vendor_id');
		$this->doVendor=$doVendor;
		//$this->doVendor=TRUE;
		$vendorModel = tmsModel::getModel('vendor');
		$this->vendor = $vendorModel->getVendor($tsmart_vendor_id);
		// in this particular case, overwrite the value for fix the recipient name
		$this->vendor->vendor_name= $this->user['name'];
		$this->subject = tsmText::_('com_tsmart_VENDOR_CONTACT') .' '.$this->user['name'];
		$this->vendorEmail= $this->user['email'];
		//$this->vendorName= $this->user['email'];
		if (tsmConfig::get('order_mail_html')) {
			$tpl = 'mail_html_question';
		} else {
			$tpl = 'mail_raw_question';
		}
		$this->setLayout($tpl);
		$this->isMail = true;
		parent::display( );
	}

}

//No Closing Tag
