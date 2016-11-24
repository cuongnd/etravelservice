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
class TsmartViewtourfaq extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)

		$app=JFactory::getApplication();
		$this->tsmart_product_id=$app->input->get('tsmart_product_id',0,'int');
		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();


		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		$this->SetViewTitle();
		$this->addStandardDefaultViewCommandsEditInline();
		$this->addStandardDefaultViewLists($model,0,'ASC');

		//get state
		require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmstates.php';
		$list_state = tmartstates::get_states();
		$this->assignRef('list_state', $list_state);
		//end get state

		//get country
		require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmcountries.php';
		$list_country = tsmcountries::get_countries();
		$this->assignRef('list_country', $list_country);
		//end get country


		$this->items = $model->getItemList(vRequest::getCmd('search', false));
		$this->pagination = $model->getPagination();

		parent::display($tpl);
	}

}
// pure php no closing tag
