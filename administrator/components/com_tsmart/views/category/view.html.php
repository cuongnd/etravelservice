<?php

/**
 *
 * Category View
 *
 * @package	tsmart
 * @subpackage Category
 * @author RickG, jseros
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2011 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 9041 2015-11-05 11:59:38Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('tsmViewAdmin'))require(VMPATH_ADMIN.DS.'helpers'.DS.'tsmviewadmin.php');

/**
 * HTML View class for maintaining the list of categories
 *
 * @package	tsmart
 * @subpackage Category
 * @author RickG, jseros
 */
class TsmartViewCategory extends tsmViewAdmin {

	function display($tpl = null) {

		if(!class_exists('tsmartModelConfig'))require(VMPATH_ADMIN .'models/config.php');

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		$model = VmModel::getModel();
		$layoutName = $this->getLayout();

		$task = vRequest::getCmd('task',$layoutName);
		$this->assignRef('task', $task);

		$this->user = $user = JFactory::getUser();
		if ($layoutName == 'edit') {

			$category = $model->getCategory('',false);

			// Toolbar
			$text='';
			if (isset($category->category_name)) $name = $category->category_name; else $name ='';
			if(!empty($category->tsmart_category_id)){
				$text = '<a href="'.juri::root().'index.php?option=com_tsmart&view=category&tsmart_category_id='.$category->tsmart_category_id.'" target="_blank" >'. $name.'<span class="vm2-modallink"></span></a>';
			}

			$this->SetViewTitle('CATEGORY',$text);

			$model->addImages($category);

			if ( $category->tsmart_category_id > 1 ) {
				$relationInfo = $model->getRelationInfo( $category->tsmart_category_id );
				$this->assignRef('relationInfo', $relationInfo);
			} else {
				$category->tsmart_vendor_id = vmAccess::getVendorId();
			}

			$parent = $model->getParentCategory( $category->tsmart_category_id );
			$this->assignRef('parent', $parent);

			if(!class_exists('ShopFunctions'))require(VMPATH_ADMIN.DS.'helpers'.DS.'shopfunctions.php');
			$templateList = ShopFunctions::renderTemplateList(tsmText::_('com_tsmart_CATEGORY_TEMPLATE_DEFAULT'));

			$this->assignRef('jTemplateList', $templateList);


			$categoryLayoutList = tsmartModelConfig::getLayoutList('category');
			$this->assignRef('categoryLayouts', $categoryLayoutList);

			$productLayouts = tsmartModelConfig::getLayoutList('productdetails');
			$this->assignRef('productLayouts', $productLayouts);

			//Nice fix by Joe, the 4. param prevents setting an category itself as child
			$categorylist = ShopFunctions::categoryListTree(array($parent->tsmart_category_id), 0, 0, (array) $category->tsmart_category_id);

			if($this->showVendors()){
				$vendorList= ShopFunctions::renderVendorList($category->tsmart_vendor_id);
				$this->assignRef('vendorList', $vendorList);
			}

			$this->assignRef('category', $category);
			$this->assignRef('categorylist', $categorylist);

			$this->addStandardEditViewCommands($category->tsmart_category_id,$category);
		}
		else {
			$this->SetViewTitle('CATEGORY_S');

			$keyWord ='';

			$this->assignRef('catmodel',	$model);
			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model,'category_name');

			$topCategory=vRequest::getInt('top_category_id',0);
			$category_tree = ShopFunctions::categoryListTree(array($topCategory));
			$this->assignRef('category_tree', $category_tree);


			$categories = $model->getCategoryTree($topCategory,0,false,$this->lists['search']);
			$this->assignRef('categories', $categories);

			$pagination = $model->getPagination();
			$this->assignRef('catpagination', $pagination);

			//we need a function of the FE shopfunctions helper to cut the category descriptions
			if (!class_exists ('shopFunctionsF')) require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
		}

		parent::display($tpl);
	}

}

// pure php no closing tag
