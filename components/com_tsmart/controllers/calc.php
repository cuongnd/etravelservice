<?php
/**
*
* Calc controller
*
* @package	tsmart
* @subpackage Calc
* @author Max Milbers, jseros
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: calc.php 8615 2014-12-04 13:56:26Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');

/**
 * Calculator Controller
 *
 * @package    tsmart
 * @subpackage Calculation tool
 * @author Max Milbers
 */
class TsmartControllerCalc extends TsmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	public function __construct() {
		parent::__construct();

	}



	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$data = vRequest::getRequest();

		$data['calc_name'] = vRequest::getHtml('calc_name','');
		$data['calc_descr'] = vRequest::getHtml('calc_descr','');
		if(isset($data['params'])){
			$data['params'] = vRequest::getHtml('params','');
		}
		parent::save($data);
	}


	/**
	* Save the calc order
	*
	* @author jseros
	*/
	public function orderUp()
	{
		// Check token
		vRequest::vmCheckToken();

		$id = 0;
		$cid	= vRequest::getInt( 'cid', array() );

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( 'index.php?option=com_tsmart&view=calc', tsmText::_('com_tsmart_NO_ITEMS_SELECTED') );
			return false;
		}

		$model = tmsModel::getModel('calc');

		if ($model->orderCalc($id, -1)) {
			$msg = tsmText::_('com_tsmart_ITEM_MOVED_UP');
		}

		$this->setRedirect( 'index.php?option=com_tsmart&view=calc', $msg );
	}


	/**
	* Save the calc order
	*
	* @author jseros
	*/
	public function orderDown()
	{
		// Check token
		vRequest::vmCheckToken();

		$id = 0;
		$cid	= vRequest::getInt( 'cid', array() );

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( 'index.php?option=com_tsmart&view=calc', tsmText::_('com_tsmart_NO_ITEMS_SELECTED') );
			return false;
		}

		//getting the model
		$model = tmsModel::getModel('calc');
		$msg = '';
		if ($model->orderCalc($id, 1)) {
			$msg = tsmText::_('com_tsmart_ITEM_MOVED_DOWN');
		}

		$this->setRedirect( 'index.php?option=com_tsmart&view=calc', $msg );
	}


	/**
	* Save the categories order
	*/
	public function saveOrder()
	{
		// Check for request forgeries
		vRequest::vmCheckToken();

		$cid	= vRequest::getInt( 'cid', array() );

		$model = tmsModel::getModel('calc');

		$order	= vRequest::getInt('order');

		$msg = '';
		if ($model->setOrder($cid,$order)) {
			$msg = tsmText::_('com_tsmart_NEW_ORDERING_SAVED');
		}
		$this->setRedirect('index.php?option=com_tsmart&view=calc', $msg );
	}

}
// pure php no closing tag
