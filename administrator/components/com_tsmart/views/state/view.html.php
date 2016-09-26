<?php
/**
*
* State View
*
* @package	tsmart
* @subpackage State
* @author RickG, Max Milbers
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
 * HTML View class for maintaining the list of states
 *
 * @package	tsmart
 * @subpackage State
 * @author Max Milbers
 */
class TsmartViewState extends tsmViewAdmin {

	function display($tpl = null) {

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$this->SetViewTitle();
		$model = VmModel::getModel();

		$this->state = $model->getItem();

		$this->tsmart_country_id = vRequest::getInt('tsmart_country_id', $this->state->tsmart_country_id);

        $isNew = (count($this->state) < 1);

		if(empty($countryId) && $isNew){
			vmWarn('Country id is 0');
			return false;
		}

		$country = VmModel::getModel('country');
		$country->setId($this->tsmart_country_id);
		$this->country_name = $country->getData()->country_name;

		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {

			$zoneModel = VmModel::getModel('Worldzones');
			$this->worldZones = $zoneModel->getWorldZonesSelectList();
			$this->addStandardEditViewCommands();

		} else {
			//get list country
			require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/vmcountries.php';
			$list_country = tsmcountries::get_countries();
			$this->assignRef('list_country', $list_country);
			//end get list country


			$this->addStandardDefaultViewCommandsEditInline();
			$this->addStandardDefaultViewLists($model);

			$this->items = $model->getItemList();
			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
