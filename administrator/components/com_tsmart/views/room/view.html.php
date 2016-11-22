<<<<<<< master
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
class TsmartViewroom extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();

		$config = JFactory::getConfig();
		$this->SetViewTitle();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew();
		JToolBarHelper::deleteList();

		$this->addStandardDefaultViewLists($model,0,'ASC');

		$this->items = $model->getItemList(vRequest::getCmd('search', false));
		$this->pagination = $model->getPagination();
		parent::display($tpl);
	}

}
// pure php no closing tag
=======
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
class TsmartViewroom extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();
		$input=JFactory::getApplication()->input;
		$config = JFactory::getConfig();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew();
		JToolBarHelper::deleteList();
		$this->SetViewTitle();
		$this->addStandardDefaultViewLists($model,0,'ASC');

		$this->items = $model->getItemList(vRequest::getCmd('search', false));
		$this->pagination = $model->getPagination();
		$task=$input->getString('task','');
		if($task=='edit'||$task=='add')
		{
			$this->item=$model->getItem();
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmmeal.php';
			$this->list_meal=tmsmeal::get_list_meal();
			require_once JPATH_ROOT.'/administrator/components/com_tsmart/helpers/tsmbed.php';
			$this->list_bed=tmsbed::get_list_bed();
		}
		parent::display($tpl);
	}

}
// pure php no closing tag
>>>>>>> local
