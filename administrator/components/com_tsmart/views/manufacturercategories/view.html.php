<?php
/**
*
* Manufacturer Category View
*
* @package	tsmart
* @subpackage Manufacturer Category
* @author Patrick Kohl
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
 * HTML View class for maintaining the list of manufacturer categories
 *
 * @package	tsmart
 * @subpackage Manufacturer Categories
 * @author Patrick Kohl
 */
class TsmartViewManufacturercategories extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)
		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		// get necessary model
		$model = tmsModel::getModel();

		$this->SetViewTitle('MANUFACTURER_CATEGORY');

     	$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {

			$manufacturerCategory = $model->getData();
			$this->assignRef('manufacturerCategory',	$manufacturerCategory);

			$this->addStandardEditViewCommands($manufacturerCategory->tsmart_manufacturercategories_id);

        }
        else {
        	$this->addStandardDefaultViewCommands();
        	$this->addStandardDefaultViewLists($model);

			$manufacturerCategories = $model->getManufacturerCategories();
			$this->assignRef('manufacturerCategories',	$manufacturerCategories);

			$this->pagination = $model->getPagination();

		}
		parent::display($tpl);
	}

}
// pure php no closing tag
