<?php
/**
*
* Country View
*
* @package	tsmart
* @subpackage Country
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
 * HTML View class for maintaining the list of countries
 *
 * @package	tsmart
 * @subpackage Country
 * @author RickG
 */
class TsmartViewCountry extends tsmViewAdmin {

    function display($tpl = null) {

		VmConfig::loadJLang('com_tsmart_countries');

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel('country');
		$zoneModel = tmsModel::getModel('worldzones');
		$this->SetViewTitle();

		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {
			$this->country = $model->getData();
			$this->wzsList = $zoneModel->getWorldZonesSelectList();
			$this->addStandardEditViewCommands();
		}
		else {

			$this->addStandardDefaultViewCommandsEditInline(true,false);

			//First the view lists, it sets the state of the model
			$this->addStandardDefaultViewLists($model,0,'ASC');

			$filter_country = vRequest::getCmd('filter_country', false);
			$this->items = $model->getItemList();
			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
    }

}
// pure php no closing tag
