<?php
/**
 *
 * Manufacturer View
 *
 * @package	tsmart
 * @subpackage Manufacturer
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
 * HTML View class for maintaining the list of manufacturers
 *
 * @package	tsmart
 * @subpackage Manufacturer
 * @author Patrick Kohl
 */
class TsmartViewManufacturer extends tsmViewAdmin {

	function display($tpl = null) {

		// Load the helper(s)


		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');


		// get necessary models
		$model = tmsModel::getModel('manufacturer');

		$categoryModel = tmsModel::getModel('manufacturercategories');

		$this->SetViewTitle();

		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {

			$this->manufacturer = $model->getManufacturer();

			$isNew = ($this->manufacturer->tsmart_manufacturer_id < 1);

			$model->addImages($this->manufacturer);

			/* Process the images */
			$mediaModel = tmsModel::getModel('media');
			$mediaModel -> setId($this->manufacturer->tsmart_media_id);
			$image = $mediaModel->getFile('manufacturer','image');

			$this->manufacturerCategories = $categoryModel->getManufacturerCategories(false,true);

			$this->addStandardEditViewCommands($this->manufacturer->tsmart_manufacturer_id);

			if(!class_exists('tsmartModelVendor')) require(VMPATH_ADMIN.DS.'models'.DS.'vendor.php');
			$this->tsmart_vendor_id = tsmartModelVendor::getLoggedVendor();

		}
		else {

			$mainframe = JFactory::getApplication();

			$categoryFilter = $categoryModel->getCategoryFilter();

			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model,'mf_name');

			$this->manufacturers = $model->getManufacturers();
			$this->pagination = $model->getPagination();

			$tsmart_manufacturercategories_id	= $mainframe->getUserStateFromRequest( 'com_tsmart.tsmart_manufacturercategories_id', 'tsmart_manufacturercategories_id', 0, 'int' );
			$this->lists['tsmart_manufacturercategories_id'] =  JHtml::_('select.genericlist',   $categoryFilter, 'tsmart_manufacturercategories_id', 'class="inputbox" onchange="this.form.submit()"', 'value', 'text', $tsmart_manufacturercategories_id );

		}


		parent::display($tpl);
	}

}
// pure php no closing tag
