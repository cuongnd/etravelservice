<?php
/**
*
* Currency View
*
* @package	tsmart
* @subpackage Currency
* @author RickG
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 8724 2015-02-18 14:03:29Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for maintaining the list of currencies
 *
 * @package	tsmart
 * @subpackage Currency
 * @author RickG, Max Milbers
 */
class TsmartViewhotel extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();
        $this->setLayout('default');
		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::deleteList();
        $this->SetViewTitle();
        $this->addStandardDefaultViewLists($model,0,'ASC');
        $this->items = $model->getItemList(vRequest::getCmd('search', false));
        $this->pagination = $model->getPagination();
        $cid	= vRequest::getInt( 'cid' );
        $model->setId($cid);
        $this->item = $model->getItem();
        $this->SetViewTitle('',$this->item->title);



		parent::display($tpl);
	}

}
// pure php no closing tag
