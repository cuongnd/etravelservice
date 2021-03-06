<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage
 * @author Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2014 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: productdetails.php 8963 2015-09-01 15:56:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport ('joomla.application.component.controller');

/**
 * tsmart Component Controller
 *
 * @package tsmart
 * @author Max Milbers
 */
class TsmartControllerProductdetails extends JControllerLegacy {

	public function __construct () {

		parent::__construct ();
		$this->registerTask ('recommend', 'MailForm');
		$this->registerTask ('askquestion', 'MailForm');
	}

	function display($cachable = false, $urlparams = false) {

		$format = vRequest::getCmd ('format', 'html');
		$tmpl = vRequest::getCmd('tmpl',false);

		$viewName = 'Productdetails';
		if ($format == 'pdf') {
			$viewName = 'Pdf';
		} else	//We override the format here, because we need actually the same data.
			if ($format == 'raw' and $tmpl == 'component') {
			$format = 'html';
		}

		$view = $this->getView ($viewName, $format);

		$view->display ();
	}

	/**
	 * Send the ask question email.
	 *
	 * @author Kohl Patrick, Christopher Roussel
	 * @author Max Milbers
	 */
	public function mailAskquestion () {

		JSession::checkToken () or jexit ('Invalid Token');

		$app = JFactory::getApplication ();
		if(!tsmConfig::get('ask_question',false)){
			$app->redirect (JRoute::_ ('index.php?option=com_tsmart&tmpl=component&view=productdetails&task=askquestion&tsmart_product_id=' . vRequest::getInt ('tsmart_product_id', 0)), 'Function disabled');
		}

		$view = $this->getView ('askquestion', 'html');
		if (!class_exists ('shopFunctionsF')) {
			require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
		}

		$vars = array();
		$min = tsmConfig::get ('asks_minimum_comment_length', 50) + 1;
		$max = tsmConfig::get ('asks_maximum_comment_length', 2000) - 1;
		$commentSize = vRequest::getString ('comment');
		if (function_exists('mb_strlen')) {
			$commentSize =  mb_strlen($commentSize);
		} else {
			$commentSize =  strlen($commentSize);
		}

		$validMail = filter_var (vRequest::getVar ('email'), FILTER_VALIDATE_EMAIL);

		if ($commentSize < $min or $commentSize > $max or !$validMail) {
			$errmsg = tsmText::_ ('com_tsmart_COMMENT_NOT_VALID_JS');
			if ($commentSize < $min) {
				$errmsg = tsmText::_ ('com_tsmart_ASKQU_CS_MIN');

			} else {
				if ($commentSize > $max) {
					$errmsg = tsmText::_ ('com_tsmart_ASKQU_CS_MAX');

				} else {
					if (!$validMail) {
						$errmsg = tsmText::_ ('com_tsmart_ASKQU_INV_MAIL');

					}
				}
			}

			$this->setRedirect (JRoute::_ ('index.php?option=com_tsmart&tmpl=component&view=productdetails&task=askquestion&tsmart_product_id=' . vRequest::getInt ('tsmart_product_id', 0)), $errmsg);
			return;
		}

		if(JFactory::getUser()->guest == 1 and tsmConfig::get ('ask_captcha')){
			$recaptcha = vRequest::getVar ('recaptcha_response_field');
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$res = $dispatcher->trigger('onCheckAnswer',$recaptcha);
			$session = JFactory::getSession();
			if(!$res[0]){
				$askquestionform = array('name' => vRequest::getVar ('name'), 'email' => vRequest::getVar ('email'), 'comment' => vRequest::getString ('comment'));
				$session->set('askquestion', $askquestionform, 'vm');
				$errmsg = tsmText::_('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL');
				$this->setRedirect (JRoute::_ ('index.php?option=com_tsmart&tmpl=component&view=productdetails&task=askquestion&tsmart_product_id=' . vRequest::getInt ('tsmart_product_id', 0)), $errmsg);
				return;
			} else {
				$session->set('askquestion', 0, 'vm');
			}
		}

		$user = JFactory::getUser ();
		if (empty($user->id)) {
			$fromMail = vRequest::getVar ('email'); //is sanitized then
			$fromName = vRequest::getVar ('name', ''); //is sanitized then
			$fromMail = str_replace (array('\'', '"', ',', '%', '*', '/', '\\', '?', '^', '`', '{', '}', '|', '~'), array(''), $fromMail);
			$fromName = str_replace (array('\'', '"', ',', '%', '*', '/', '\\', '?', '^', '`', '{', '}', '|', '~'), array(''), $fromName);
		} else {
			$fromMail = $user->email;
			$fromName = $user->name;
		}
		$vars['user'] = array('name' => $fromName, 'email' => $fromMail);

		$tsmart_product_id = vRequest::getInt ('tsmart_product_id', 0);
		$productModel = tmsModel::getModel ('product');

		$vars['product'] = $productModel->getProduct ($tsmart_product_id);

		$vendorModel = tmsModel::getModel ('vendor');
		$VendorEmail = $vendorModel->getVendorEmail ($vars['product']->tsmart_vendor_id);

		JPluginHelper::importPlugin ('system');
		JPluginHelper::importPlugin ('vmextended');
		JPluginHelper::importPlugin ('userfield');
		$dispatcher = JDispatcher::getInstance ();
		$dispatcher->trigger ('plgVmOnAskQuestion', array(&$VendorEmail, &$vars, &$view));

		$vars['vendor'] = array('vendor_store_name' => $fromName);

		if (shopFunctionsF::renderMail ('askquestion', $VendorEmail, $vars, 'productdetails',true)) {
			$string = 'com_tsmart_MAIL_SEND_SUCCESSFULLY';
		} else {
			$string = 'com_tsmart_MAIL_NOT_SEND_SUCCESSFULLY';
		}
		$app->enqueueMessage (tsmText::_ ($string));


		$view->setLayout ('mail_confirmed');
		$view->display ();
	}

	/**
	 * Send the Recommend to a friend email.
	 *
	 * @author Kohl Patrick
	 * @author Max Milbers
	 */
	public function mailRecommend () {

		JSession::checkToken () or jexit ('Invalid Token');

		$app = JFactory::getApplication ();
		if(!tsmConfig::get('show_emailfriend',false)){
			$app->redirect (JRoute::_ ('index.php?option=com_tsmart&tmpl=component&view=productdetails&task=askquestion&tsmart_product_id=' . vRequest::getInt ('tsmart_product_id', 0)), 'Function disabled');
		}

		if(JFactory::getUser()->guest == 1 and tsmConfig::get ('ask_captcha')){
			$recaptcha = vRequest::getVar ('recaptcha_response_field');
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$res = $dispatcher->trigger('onCheckAnswer',$recaptcha);
			$session = JFactory::getSession();
			if(!$res[0]){
				$mailrecommend = array('email' => vRequest::getVar ('email'), 'comment' => vRequest::getString ('comment'));
				$session->set('mailrecommend', $mailrecommend, 'vm');
				$errmsg = tsmText::_('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL');
				$this->setRedirect (JRoute::_ ('index.php?option=com_tsmart&tmpl=component&view=productdetails&task=recommend&tsmart_product_id=' . vRequest::getInt ('tsmart_product_id', 0)), $errmsg);
				return;
			} else {
				$session->set('mailrecommend', 0, 'vm');
			}
		}


		$vars = array();

		$toMail = vRequest::getVar ('email'); //is sanitized then
		$toMail = str_replace (array('\'', '"', ',', '%', '*', '/', '\\', '?', '^', '`', '{', '}', '|', '~'), array(''), $toMail);

		if (shopFunctionsF::renderMail ('recommend', $toMail, $vars, 'productdetails', TRUE)) {
			$string = 'com_tsmart_MAIL_SEND_SUCCESSFULLY';
		} else {
			$string = 'com_tsmart_MAIL_NOT_SEND_SUCCESSFULLY';
		}
		$app->enqueueMessage (tsmText::_ ($string));

		$view = $this->getView ('recommend', 'html');

		$view->setLayout ('mail_confirmed');
		$view->display ();
	}

	/**
	 *  Ask Question form
	 * Recommend form for Mail
	 */
	public function MailForm () {

		if (vRequest::getCmd ('task') == 'recommend') {
			$view = $this->getView ('recommend', 'html');
		} else {
			$view = $this->getView ('askquestion', 'html');
		}

		// Set the layout
		$view->setLayout ('form');

		// Display it all
		$view->display ();
	}

	/**
	 * Add or edit a review
	 */
	public function review () {
		$msg="";

		$model = tmsModel::getModel ('ratings');
		$tsmart_product_id = vRequest::getInt('tsmart_product_id',0);

		$allowReview = $model->allowReview($tsmart_product_id);
		$allowRating = $model->allowRating($tsmart_product_id);
		if($allowReview || $allowRating){
			$return = $model->saveRating ();
			if ($return !== FALSE) {
				$msg = tsmText::sprintf ('com_tsmart_STRING_SAVED', tsmText::_ ('com_tsmart_REVIEW'));

				if (!class_exists ('ShopFunctionsF')) {
					require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
				}
				$data = vRequest::getPost();
				shopFunctionsF::sendRatingEmailToVendor($data);
			}
		}

		$this->setRedirect (JRoute::_ ('index.php?option=com_tsmart&view=productdetails&tsmart_product_id=' . $tsmart_product_id, FALSE), $msg);

	}

	/**
	 * Json task for recalculation of prices
	 *
	 * @author Max Milbers
	 * @author Patrick Kohl
	 */
	public function recalculate () {

		$tsmart_product_idArray = vRequest::getInt ('tsmart_product_id', array()); //is sanitized then
		if(is_array($tsmart_product_idArray) and !empty($tsmart_product_idArray[0])){
			$tsmart_product_id = $tsmart_product_idArray[0];
		} else {
			$tsmart_product_id = $tsmart_product_idArray;
		}

		$quantity = 0;
		$quantityArray = vRequest::getInt ('quantity', array()); //is sanitized then
		if(is_array($quantityArray)){
			if(!empty($quantityArray[0])){
				$quantity = $quantityArray[0];
			}
		} else {
			$quantity = (int)$quantityArray;
		}

		if (empty($quantity)) {
			$quantity = 1;
		}

		$product_model = tmsModel::getModel ('product');

		if(!empty($tsmart_product_id)){
			$prices = $product_model->getPrice ($tsmart_product_id, $quantity);
		} else {
			jexit ();
		}
		$priceFormated = array();
		if (!class_exists ('CurrencyDisplay')) {
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
		}
		$currency = CurrencyDisplay::getInstance ();

		$priceFieldsRoots = array('basePrice','variantModification','basePriceVariant',
			'basePriceWithTax','discountedPriceWithoutTax',
			'salesPrice','priceWithoutTax',
			'salesPriceWithDiscount','discountAmount','taxAmount','unitPrice');

		foreach ($priceFieldsRoots as $name) {
			if(isset($prices[$name])){
				$priceFormated[$name] = $currency->createPriceDiv ($name, '', $prices, TRUE);
			}
		}

		// Get the document object.
		$document = JFactory::getDocument ();
		// stAn: setName works in JDocumentHTML and not JDocumentRAW
		if (method_exists($document, 'setName')){
			$document->setName ('recalculate');
		}

		// Also return all messages (in HTML format!):
		// Since we are in a JSON document, we have to temporarily switch the type to HTML
		// to make sure the html renderer is actually used
		$previoustype = $document->getType();
		$document->setType('html');
		$msgrenderer = $document->loadRenderer('message');
		$priceFormated['messages'] = $msgrenderer->render('Message');
		$document->setType($previoustype);

		JResponse::setHeader ('Cache-Control', 'no-cache, must-revalidate');
		JResponse::setHeader ('Expires', 'Mon, 6 Jul 2000 10:00:00 GMT');
		// Set the MIME type for JSON output.
		$document->setMimeEncoding ('application/json');
		//JResponse::setHeader ('Content-Disposition', 'attachment;filename="recalculate.json"', TRUE);
		JResponse::sendHeaders ();
		echo json_encode ($priceFormated);
		jexit ();
	}

	public function getJsonChild () {

		$view = $this->getView ('productdetails', 'json');
		$view->display (NULL);
	}

	/**
	 * Notify customer
	 *
	 * @author Seyi Awofadeju
	 */
	public function notifycustomer () {

		$data = vRequest::getPost();

		$model = tmsModel::getModel ('waitinglist');
		if (!$model->adduser ($data)) {
			$this->setRedirect (JRoute::_ ('index.php?option=com_tsmart&view=productdetails&layout=notify&tsmart_product_id=' . $data['tsmart_product_id'], FALSE), $msg);
		} else {
			$msg = tsmText::sprintf ('com_tsmart_STRING_SAVED', tsmText::_ ('com_tsmart_CART_NOTIFY'));
			$this->setRedirect (JRoute::_ ('index.php?option=com_tsmart&view=productdetails&tsmart_product_id=' . $data['tsmart_product_id'], FALSE), $msg);
		}

	}

	/**
	 * Send an email to all shoppers who bought a product
	 */
	public function sentProductEmailToShoppers () {

		$model = tmsModel::getModel ('product');
	    $model->sentProductEmailToShoppers ();
	}

	/**
	 * View email layout on browser
	 */
	function viewRecommendMail(){

		$view = $this->getView('recommend', 'html');
		$viewLayout = vRequest::getCmd('layout', 'mail_html');
		$view->setLayout($viewLayout);
		$view->display();
	}

	function viewAskQuestionMail(){

		$view = $this->getView('askquestion', 'html');
		$viewLayout = vRequest::getCmd('layout', 'mail_confirmed');
		$view->setLayout($viewLayout);
		$view->display();
	}

}
// pure php no closing tag
