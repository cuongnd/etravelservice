<?php
/**
*
* Review controller
*
* @package	tsmart
* @subpackage
* @author Max Milberes
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: ratings.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists ('TsmController')){
	require(VMPATH_ADMIN . DS . 'helpers' . DS . 'tsmController.php');
}


/**
 * Review Controller
 *
 * @package    tsmart
 */
class TsmartControllerRatings extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		parent::__construct();

		$task = vRequest::getVar('task');

	}

	function edit($layout=0){
		$this->listreviews();
	}

	/**
	 * Generic edit task
	 */
	function edit_review(){

		vRequest::setVar('controller', $this->_cname);
		vRequest::setVar('view', $this->_cname);
		vRequest::setVar('layout', 'edit_review');

		if(empty($view)){
			$document = JFactory::getDocument();
			$viewType = $document->getType();
			$view = $this->getView($this->_cname, $viewType);
		}

		parent::display();
	}

	/**
	 * lits the reviews
	 */
	public function listreviews(){

		/* Create the view object */
		$view = $this->getView('ratings', 'html');

		$view->setLayout('list_reviews');

		$view->display();
	}

	/**
	 * we must overwrite it here, because the task publish can be meant for two different list layouts.
	 */
	function publish($cidname=0,$table=0,$redirect = 0){

		vRequest::vmCheckToken();
		$layout = vRequest::getString('layout','default');

		if($layout=='list_reviews'){

			$tsmart_product_id = vRequest::getInt('tsmart_product_id');
			if(is_array($tsmart_product_id) && count($tsmart_product_id) > 0){
				$tsmart_product_id = (int)$tsmart_product_id[0];
			} else {
				$tsmart_product_id = (int)$tsmart_product_id;
			}
			$redPath = '';
			if (!empty($tsmart_product_id)) {
				$redPath = '&task=listreviews&tsmart_product_id=' . $tsmart_product_id;
			}

			parent::publish('tsmart_rating_review_id','rating_reviews',$this->redirectPath.$redPath);
		} else {
			parent::publish();
		}

	}

	function unpublish($cidname=0,$table=0,$redirect = 0){

		vRequest::vmCheckToken();
		$layout = vRequest::getString('layout','default');

		if($layout=='list_reviews'){

			$tsmart_product_id = vRequest::getInt('tsmart_product_id');
			if(is_array($tsmart_product_id) && count($tsmart_product_id) > 0){
				$tsmart_product_id = (int)$tsmart_product_id[0];
			} else {
				$tsmart_product_id = (int)$tsmart_product_id;
			}
			$redPath = '';
			if (!empty($tsmart_product_id)) {
				$redPath = '&task=listreviews&tsmart_product_id=' . $tsmart_product_id;
			}

			parent::unpublish('tsmart_rating_review_id','rating_reviews',$this->redirectPath.$redPath);
		} else {
			parent::unpublish();
		}

	}

	/**
	 * Save task for review
	 *
	 * @author Max Milbers
	 */
	function saveReview(){

		$this->storeReview(FALSE);
	}

	/**
	 * Save task for review
	 *
	 * @author Max Milbers
	 */
	function applyReview(){

		$this->storeReview(TRUE);
	}


	function storeReview($apply){

		vRequest::vmCheckToken();

		if (empty($data)){
			$data = vRequest::getPost();
		}

		$model = VmModel::getModel($this->_cname);
		$id = $model->saveRating($data);

		$msg = 'failed';
		if (!empty($id)) {
			$msg = tsmText::sprintf ('com_tsmart_STRING_SAVED', $this->mainLangKey);
		}

		$redir = $this->redirectPath;
		if($apply){
			$redir = 'index.php?option=com_tsmart&view=ratings&task=edit_review&tsmart_rating_review_id='.$id;
		} else {
			$tsmart_product_id = vRequest::getInt('tsmart_product_id');
			if(is_array($tsmart_product_id) && count($tsmart_product_id) > 0){
				$tsmart_product_id = (int)$tsmart_product_id[0];
			} else {
				$tsmart_product_id = (int)$tsmart_product_id;
			}
			$redir = 'index.php?option=com_tsmart&view=ratings&task=listreviews&tsmart_product_id='.$tsmart_product_id;
		}

		$this->setRedirect($redir, $msg);
	}
	/**
	 * Save task for review
	 *
	 * @author Max Milbers
	 */
	function cancelEditReview(){

		$tsmart_product_id = vRequest::getInt('tsmart_product_id');
		if(is_array($tsmart_product_id) && count($tsmart_product_id) > 0){
			$tsmart_product_id = (int)$tsmart_product_id[0];
		} else {
			$tsmart_product_id = (int)$tsmart_product_id;
		}
		$msg = tsmText::sprintf('com_tsmart_STRING_CANCELLED',$this->mainLangKey); //'com_tsmart_OPERATION_CANCELED'
		$this->setRedirect('index.php?option=com_tsmart&view=ratings&task=listreviews&tsmart_product_id='.$tsmart_product_id, $msg);
	}

}
// pure php no closing tag
