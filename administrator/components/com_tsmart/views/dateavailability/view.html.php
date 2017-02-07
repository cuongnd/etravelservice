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
class TsmartViewdateavailability extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)

		require_once JPATH_ROOT.DS.'administrator/components/com_tsmart/helpers/utility.php';
		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = tmsModel::getModel();

		$config = JFactory::getConfig();
		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {
			$cid	= vRequest::getInt( 'cid' );

			$task = vRequest::getCmd('task', 'add');

			if($task!='add' && !empty($cid) && !empty($cid[0])){
				$cid = (int)$cid[0];
			} else {
				$cid = 0;
			}

			$model->setId($cid);
			$this->dateavailability = $model->getdateavailability();



			$this->SetViewTitle('',$this->dateavailability->dateavailability_name);
			$this->addStandardEditViewCommands();

		} else {
			$model_product = tmsModel::getModel('product');
			$model_tour_class = tmsModel::getModel('tourclass');
            require_once JPATH_ROOT.DS.'administrator/components/com_tsmart/helpers/tsmdateavailability.php';
			$this->list_tour = tsmdateavailability::get_list_tour_private();

			$this->list_tour_class = $model_tour_class->getItemList();
			$this->SetViewTitle();
			$this->addStandardDefaultViewCommandsdateavailability();
			$this->addStandardDefaultViewLists($model,0,'ASC');

			$this->list_date_availability = $model->getItemList(vRequest::getCmd('search'));

			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
