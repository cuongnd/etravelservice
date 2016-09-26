<?php
/**
*
* Description
*
* @package	tsmart
* @subpackage	ratings
* @author
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 8955 2015-08-19 12:58:20Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for ratings (and customer reviews)
 *
 */
class TsmartViewRatings extends tsmViewAdmin {
	public $max_rating;

	function display($tpl = null) {

		$mainframe = Jfactory::getApplication();
		$option = vRequest::getCmd('option');

		//Load helpers


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		/* Get the review IDs to retrieve (input variable may be cid, cid[] or tsmart_rating_review_id */
		$cids = vRequest::getInt('cid', vRequest::getVar('tsmart_rating_review_id',0));
		if ($cids && !is_array($cids)) $cids = array($cids);

		// Figure out maximum rating scale (default is 5 stars)
		$this->max_rating = tsmConfig::get('vm_maximum_rating_scale',5);

		$model = tmsModel::getModel();
		$this->SetViewTitle('REVIEW_RATE' );


		/* Get the task */
		$task = vRequest::getCmd('task');
		switch ($task) {
			case 'edit':
				/* Get the data
				$rating = $model->getRating($cids);
				$this->addStandardEditViewCommands();

				break;*/
			case 'listreviews':
				/* Get the data */
				$this->addStandardDefaultViewLists($model);
				$tsmart_product_id = vRequest::getInt('tsmart_product_id');
				if(is_array($tsmart_product_id) && count($tsmart_product_id) > 0){
					$tsmart_product_id = (int)$tsmart_product_id[0];
				} else {
					$tsmart_product_id = (int)$tsmart_product_id;
				}
				$this->reviewslist = $model->getReviews($tsmart_product_id, vmAccess::getVendorId());

				$lists = array();
				$lists['filter_order'] = $mainframe->getUserStateFromRequest($option.'filter_order', 'filter_order', '', 'cmd');
				$lists['filter_order_Dir'] = $mainframe->getUserStateFromRequest($option.'filter_order_Dir', 'filter_order_Dir', '', 'word');

				$this->pagination = $model->getPagination();

				$this->addStandardDefaultViewCommands(false,true);
				break;
			case 'edit_review':

				JToolBarHelper::divider();

				// Get the data
				$this->rating = $model->getReview($cids);
				if(!empty($this->rating)){
					$this->SetViewTitle('REVIEW_RATE',$this->rating->product_name." (". $this->rating->customer.")" );

					JToolBarHelper::custom('saveReview', 'save', 'save',  tsmText::_('com_tsmart_SAVE'), false);
					JToolBarHelper::custom('applyReview', 'apply', 'apply',  tsmText::_('com_tsmart_APPLY'), false);

				} else {
					$this->SetViewTitle('REVIEW_RATE','ERROR' );
				}

				JToolBarHelper::custom('cancelEditReview', 'cancel', 'cancel',  tsmText::_('com_tsmart_CANCEL'), false);

				break;
			default:

				$this->addStandardDefaultViewCommands(false, true);
				$this->addStandardDefaultViewLists($model);

				$this->ratingslist = $model->getRatings();
				$this->pagination = $model->getPagination();

				break;
		}
		parent::display($tpl);
	}

}
// pure php no closing tag
