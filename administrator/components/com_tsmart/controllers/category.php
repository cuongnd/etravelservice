<?php
/**
*
* Category controller
*
* @package	tsmart
* @subpackage Category
* @author RickG, jseros
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: category.php 8970 2015-09-06 23:19:17Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('TsmController')) require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmcontroller.php');

/**
 * Category Controller
 *
 * @package    tsmart
 * @subpackage Category
 * @author jseros, Max Milbers
 */
class TsmartControllerCategory extends TsmController {


	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		//ACL
		if (!vmAccess::manager('category.edit')) {
			JFactory::getApplication()->redirect( 'index.php?option=com_tsmart', tsmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}
		
		$data = vRequest::getRequest();

		$data['category_name'] = vRequest::getHtml('category_name','');
		$data['category_description'] = vRequest::getHtml('category_description','');

		parent::save($data);
	}


	/**
	* Save the category order
	*
	* @author jseros
	*/
	public function orderUp()
	{
		//ACL
		if (!vmAccess::manager('category.edit')) {
			JFactory::getApplication()->redirect( 'index.php?option=com_tsmart', tsmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}

		// Check token
		vRequest::vmCheckToken();

		//capturing tsmart_category_id
		$id = 0;
		$cid	= vRequest::getInt( 'cid', array() );

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( 'index.php?option=com_tsmart&view=category', tsmText::_('com_tsmart_NO_ITEMS_SELECTED') );
			return false;
		}

		//getting the model
		$model = tmsModel::getModel('category');

		$msg = '';
		if ($model->orderCategory($id, -1)) {
			$msg = tsmText::_('com_tsmart_ITEM_MOVED_UP');
		}

		$this->setRedirect( 'index.php?option=com_tsmart&view=category', $msg );
	}


	/**
	* Save the category order
	*
	* @author jseros
	*/
	public function orderDown()
	{
		//ACL
		if (!vmAccess::manager('category.edit')) {
			JFactory::getApplication()->redirect( 'index.php?option=com_tsmart', tsmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}
		
		// Check token
		vRequest::vmCheckToken();

		//capturing tsmart_category_id
		$id = 0;
		$cid	= vRequest::getInt( 'cid', array() );

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( 'index.php?option=com_tsmart&view=category', tsmText::_('com_tsmart_NO_ITEMS_SELECTED') );
			return false;
		}

		//getting the model
		$model = tmsModel::getModel('category');

		$msg = '';
		if ($model->orderCategory($id, 1)) {
			$msg = tsmText::_('com_tsmart_ITEM_MOVED_DOWN');
		}

		$this->setRedirect( 'index.php?option=com_tsmart&view=category', $msg );
	}


	/**
	* Save the categories order
	*/
	public function saveOrder()
	{
		//ACL
		if (!vmAccess::manager('category.edit')) {
			JFactory::getApplication()->redirect( 'index.php?option=com_tsmart', tsmText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}
		
		// Check for request forgeries
		vRequest::vmCheckToken();

		$cid	= vRequest::getInt( 'cid', array() );	//is sanitized

		$model = tmsModel::getModel('category');

		$order	= vRequest::getInt('order', array() );

		$msg = '';
		if ($model->setOrder($cid,$order)) {
			$msg = tsmText::_('com_tsmart_NEW_ORDERING_SAVED');
		}

		$this->setRedirect('index.php?option=com_tsmart&view=category', $msg );
	}

}
