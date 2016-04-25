<?php
/**
*
* Currency View
*
* @package	VirtueMart
* @subpackage Currency
* @author RickG
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 8724 2015-02-18 14:03:29Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmviewadmin.php');

/**
 * HTML View class for maintaining the list of currencies
 *
 * @package	VirtueMart
 * @subpackage Currency
 * @author RickG, Max Milbers
 */
class VirtuemartViewDeparture extends VmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = VmModel::getModel();

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
			$this->departure = $model->getdeparture();



			$this->SetViewTitle('',$this->departure->departure_name);
			$this->addStandardEditViewCommands();

		} else {
			$model_product = VmModel::getModel('product');
			$model_tour_class = VmModel::getModel('tourclass');
            require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmdeparture.php';
			$this->list_tour = vmDeparture::get_list_tour_jont_group();

			$this->list_tour_class = $model_tour_class->getItemList();
			$this->SetViewTitle();
			$this->addStandardDefaultViewCommandsDeparture();
			$this->addStandardDefaultViewLists($model,0,'ASC');

			$this->list_departure = $model->getItemList(vRequest::getCmd('search'));
			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
