<?php
/**
 *
 * Description
 *
 * @package	tsmart
 * @subpackage
 * @author
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2015 tsmart Team. All rights reserved.
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
 * HTML View class for the tsmart Component
 *
 * @package		tsmart
 * @author
 */
class TsmartViewInventory extends tsmViewAdmin {

	function display($tpl = null) {


		//Load helpers

		if (!class_exists('CurrencyDisplay'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');

		if (!class_exists('VmHTML'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');

		// Get the data
		$model = tmsModel::getModel('product');

		// Create filter
		$this->addStandardDefaultViewLists($model);

		$this->inventorylist = $model->getProductListing(false,false,false,false);

		$this->pagination = $model->getPagination();

		// Apply currency
		$currencydisplay = CurrencyDisplay::getInstance();

		foreach ($this->inventorylist as $tsmart_product_id => $product) {

			//TODO oculd be interesting to show the price for each product, and all stored ones $product->product_in_stock
			$price = isset($product->allPrices[$product->selectedPrice]['product_price'])? $product->allPrices[$product->selectedPrice]['product_price']:0;

			$product->product_instock_value = $currencydisplay->priceDisplay($price,'',$product->product_in_stock,false);
			$product->product_price_display = $currencydisplay->priceDisplay($price,'',1,false);

			$product->weigth_unit_display= ShopFunctions::renderWeightUnit($product->product_weight_uom);
		}

		$options = array();
		$options[] = JHtml::_('select.option', '', tsmText::_('com_tsmart_DISPLAY_STOCK').':');
		$options[] = JHtml::_('select.option', 'stocklow', tsmText::_('com_tsmart_STOCK_LEVEL_LOW'));
		$options[] = JHtml::_('select.option', 'stockout', tsmText::_('com_tsmart_STOCK_LEVEL_OUT'));
		$this->lists['stockfilter'] = JHtml::_('select.genericlist', $options, 'search_type', 'onChange="document.adminForm.submit(); return false;"', 'value', 'text', vRequest::getVar('search_type'));
		$this->lists['filter_product'] = vRequest::getVar('filter_product');

		/* Toolbar */
		$this->SetViewTitle('PRODUCT_INVENTORY');
		JToolBarHelper::publish();
		JToolBarHelper::unpublish();

		parent::display($tpl);
	}

}
// pure php no closing tag
