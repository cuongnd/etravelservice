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
 * @version $Id: view.html.php 2796 2011-03-01 11:29:16Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists ('VmView')) {
	require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');
}

/**
 * Product details
 *
 * @package tsmart
 * @author Max Milbers
 */
class TsmartViewAskquestion extends VmView {

	/**
	 * Collect all data to show on the template
	 *
	 * @author Max Milbers
	 */
	function display ($tpl = NULL) {

		$app = JFactory::getApplication();
		if(!tsmConfig::get('ask_question',false) and !tsmConfig::get('askprice',false)){
			$app->redirect(JRoute::_('index.php?option=com_tsmart','Disabled function'));
		}

		$this->login = '';
		if(!tsmConfig::get('recommend_unauth',false)){
			$user = JFactory::getUser();
			if($user->guest){
				$this->login = shopFunctionsF::getLoginForm(false);
			}
		}

		$show_prices = tsmConfig::get ('show_prices', 1);
		if ($show_prices == '1') {
			if (!class_exists ('calculationHelper')) {
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'calculationh.php');
			}
		}
		$this->assignRef ('show_prices', $show_prices);
		$document = JFactory::getDocument ();

		$mainframe = JFactory::getApplication ();
		$pathway = $mainframe->getPathway ();
		$task = vRequest::getCmd ('task');

		if (!class_exists('VmImage'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php');

		// Load the product
		$product_model = tmsModel::getModel ('product');
		$category_model = tmsModel::getModel ('Category');

		$tsmart_product_idArray = vRequest::getInt ('tsmart_product_id', 0);
		if (is_array ($tsmart_product_idArray)) {
			$tsmart_product_id = $tsmart_product_idArray[0];
		} else {
			$tsmart_product_id = $tsmart_product_idArray;
		}

		if (empty($tsmart_product_id)) {
			self::showLastCategory ($tpl);
			return;
		}

		if (!class_exists ('tsmartModelVendor')) {
			require(VMPATH_ADMIN . DS . 'models' . DS . 'vendor.php');
		}
		$product = $product_model->getProduct ($tsmart_product_id);

		// Set Canonic link
		$format = vRequest::getCmd('format', 'html');
		if ($format == 'html') {
			$document->addHeadLink ($product->canonical, 'canonical', 'rel', '');
		}

		// Set the titles
		$document->setTitle (tsmText::sprintf ('com_tsmart_PRODUCT_DETAILS_TITLE', $product->product_name . ' - ' . tsmText::_ ('com_tsmart_PRODUCT_ASK_QUESTION')));

		$this->assignRef ('product', $product);

		if (empty($product)) {
			self::showLastCategory ($tpl);
			return;
		}

		$product_model->addImages ($product, 1);

		// Get the category ID
		$tsmart_category_id = vRequest::getInt ('tsmart_category_id');
		if ($tsmart_category_id == 0 && !empty($product)) {
			if (array_key_exists ('0', $product->categories)) {
				$tsmart_category_id = $product->categories[0];
			}
		}

		shopFunctionsF::setLastVisitedCategoryId ($tsmart_category_id);

		if ($category_model) {
			$category = $category_model->getCategory ($tsmart_category_id);
			$this->assignRef ('category', $category);
			$pathway->addItem (tsmText::_($category->category_name), JRoute::_ ('index.php?option=com_tsmart&view=category&tsmart_category_id=' . $tsmart_category_id, FALSE));
		}

		$pathway->addItem ($product->product_name, JRoute::_ ('index.php?option=com_tsmart&view=productdetails&tsmart_category_id=' . $tsmart_category_id . '&tsmart_product_id=' . $product->tsmart_product_id, FALSE));

		// for askquestion
		$pathway->addItem (tsmText::_ ('com_tsmart_PRODUCT_ASK_QUESTION'));

		$this->user = JFactory::getUser ();

		if ($product->metadesc) {
			$document->setDescription ($product->metadesc);
		}
		if ($product->metakey) {
			$document->setMetaData ('keywords', $product->metakey);
		}

		//We never want that ask a question is indexed
		$document->setMetaData('robots','NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET');

		if ($mainframe->getCfg ('MetaTitle') == '1') {
			$document->setMetaData ('title', $product->product_s_desc); //Maybe better product_name
		}
		if ($mainframe->getCfg ('MetaAuthor') == '1') {
			$document->setMetaData ('author', $product->metaauthor);
		}

		parent::display ($tpl);
	}

	function renderMailLayout () {

		$this->setLayout ('mail_html_question');
		$this->comment = vRequest::getString ('comment');

		$this->user = JFactory::getUser ();
		if (empty($this->user->id)) {
			$fromMail = vRequest::getEmail ('email'); //is sanitized then
			$fromName = vRequest::getVar ('name', ''); //is sanitized then
			//$fromMail = str_replace (array('\'', '"', ',', '%', '*', '/', '\\', '?', '^', '`', '{', '}', '|', '~'), array(''), $fromMail);
			$fromName = str_replace (array('\'', '"', ',', '%', '*', '/', '\\', '?', '^', '`', '{', '}', '|', '~'), array(''), $fromName);
			$this->user->email = $fromMail;
			$this->user->name = $fromName;
		}

		$tsmart_product_id = vRequest::getInt ('tsmart_product_id', 0);

		$productModel = tmsModel::getModel ('product');
		if(empty($this->product)){
			$this->product =  $productModel->getProduct ($tsmart_product_id);
		}
		$productModel->addImages($this->product);

		$this->subject = tsmText::_ ('com_tsmart_QUESTION_ABOUT') . $this->product->product_name;

		$vendorModel = tmsModel::getModel ('vendor');

		$this->vendor = $vendorModel->getVendor ($this->product->tsmart_vendor_id);
		//$this->vendor->vendor_store_name = $fromName;

		$vendorModel->addImages ($this->vendor);

		$this->vendorEmail = $vendorModel->getVendorEmail($this->vendor->tsmart_vendor_id);;

		// in this particular case, overwrite the value for fix the recipient name
		$this->vendor->vendor_name = $this->user->get('name');

		if (tsmConfig::get ('order_mail_html')) {
			$tpl = 'mail_html_question';
		} else {
			$tpl = 'mail_raw_question';
		}
		$this->setLayout ($tpl);
		$this->isMail = true;
		parent::display ();
	}

	public function showLastCategory ($tpl) {
		$this->prepareContinueLink();
		parent::display ($tpl);
	}

}

// pure php no closing tag