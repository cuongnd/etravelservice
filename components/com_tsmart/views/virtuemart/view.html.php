<?php
/**
 *
 * Description
 *
 * @package	tsmart
 * @subpackage
 * @author
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2011 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 9058 2015-11-10 18:30:54Z Milbo $
 */

# Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

# Load the view framework
if(!class_exists('VmView'))require(VMPATH_SITE.DS.'helpers'.DS.'vmview.php');

/**
 * Default HTML View class for the tsmart Component
 * @todo Find out how to use the front-end models instead of the backend models
 */
class TsmartViewtsmart extends VmView {

	public function display($tpl = null) {

		$vendorId = vRequest::getInt('vendorid', 1);

		$vendorModel = tmsModel::getModel('vendor');

		$vendorIdUser = vmAccess::isSuperVendor();
		$vendorModel->setId($vendorId);
		$this->vendor = $vendorModel->getVendor();

		if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
		if (tsmConfig::get ('enable_content_plugin', 0)) {
			shopFunctionsF::triggerContentPlugin($this->vendor, 'vendor','vendor_store_desc');
			shopFunctionsF::triggerContentPlugin($this->vendor, 'vendor','vendor_terms_of_service');
		}

		$app = JFactory::getApplication();
		$menus = $app->getMenu();
		$menu = $menus->getActive();

		if(!empty($menu->id)){
			ShopFunctionsF::setLastVisitedItemId($menu->id);
		} else if($itemId = vRequest::getInt('Itemid',false)){
			ShopFunctionsF::setLastVisitedItemId($itemId);
		}

		$document = JFactory::getDocument();

		if(!tsmConfig::get('shop_is_offline',0)){

			if( ShopFunctionsF::isFEmanager('product.edit') ){
				$add_product_link = JURI::root() . 'index.php?option=com_tsmart&tmpl=component&view=product&task=edit&tsmart_product_id=0&manage=1' ;
				$add_product_link = $this->linkIcon($add_product_link, 'com_tsmart_PRODUCT_FORM_NEW_PRODUCT', 'edit', false, false);
			} else {
				$add_product_link = "";
			}
			$this->assignRef('add_product_link', $add_product_link);

			$categoryModel = tmsModel::getModel('category');
			$productModel = tmsModel::getModel('product');
			$ratingModel = tmsModel::getModel('ratings');
			$productModel->withRating = $this->showRating = $ratingModel->showRating();

			$this->products = array();
			$categoryId = vRequest::getInt('catid', 0);

			$categoryChildren = $categoryModel->getChildCategoryList($vendorId, $categoryId);

			$categoryModel->addImages($categoryChildren,1);

			$this->assignRef('categories',	$categoryChildren);

			if(!class_exists('CurrencyDisplay'))require(VMPATH_ADMIN.DS.'helpers'.DS.'currencydisplay.php');
			$this->currency = CurrencyDisplay::getInstance( );
			
			$products_per_row = tsmConfig::get('homepage_products_per_row',3);
			
			$featured_products_rows = tsmConfig::get('featured_products_rows',1);
			$featured_products_count = $products_per_row * $featured_products_rows;

			if (!empty($featured_products_count) and tsmConfig::get('show_featured', 1)) {
				$this->products['featured'] = $productModel->getProductListing('featured', $featured_products_count);
				$productModel->addImages($this->products['featured'],1);
			}
			
			$latest_products_rows = tsmConfig::get('latest_products_rows');
			$latest_products_count = $products_per_row * $latest_products_rows;

			if (!empty($latest_products_count) and tsmConfig::get('show_latest', 1)) {
				$this->products['latest']= $productModel->getProductListing('latest', $latest_products_count);
				$productModel->addImages($this->products['latest'],1);
			}

			$topTen_products_rows = tsmConfig::get('topTen_products_rows');
			$topTen_products_count = $products_per_row * $topTen_products_rows;
			
			if (!empty($topTen_products_count) and tsmConfig::get('show_topTen', 1)) {
				$this->products['topten']= $productModel->getProductListing('topten', $topTen_products_count);
				$productModel->addImages($this->products['topten'],1);
			}
			
			$recent_products_rows = tsmConfig::get('recent_products_rows');
			$recent_products_count = $products_per_row * $recent_products_rows;

			
			if (!empty($recent_products_count) and tsmConfig::get('show_recent', 1) ) {
				$recent_products = $productModel->getProductListing('recent');
				if(!empty($recent_products)){
					$this->products['recent']= $productModel->getProductListing('recent', $recent_products_count);
					$productModel->addImages($this->products['recent'],1);
				}
			}

			if ($this->products) {

				$display_stock = tsmConfig::get('display_stock',1);
				$showCustoms = tsmConfig::get('show_pcustoms',1);
				if($display_stock or $showCustoms){

					if(!$showCustoms){
						foreach($this->products as $pType => $productSeries){
							foreach($productSeries as $i => $productItem){
								$this->products[$pType][$i]->stock = $productModel->getStockIndicator($productItem);
							}
						}
					} else {
						if (!class_exists ('vmCustomPlugin')) {
							require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');
						}
						foreach($this->products as $pType => $productSeries) {
							shopFunctionsF::sortLoadProductCustomsStockInd($this->products[$pType],$productModel);
						}
					}
				}
			}

			$this->showBasePrice = (vmAccess::manager() or vmAccess::isSuperVendor());

			$layout = tsmConfig::get('vmlayout','default');
			$this->setLayout($layout);

			$productsLayout = tsmConfig::get('productsublayout','products');
			if(empty($productsLayout)) $productsLayout = 'products';
			$this->productsLayout = empty($menu->query['productsublayout'])? $productsLayout:$menu->query['productsublayout'];

			// Add feed links
			if ($this->products  && (tsmConfig::get('feed_featured_published', 0)==1 or tsmConfig::get('feed_topten_published', 0)==1 or tsmConfig::get('feed_latest_published', 0)==1)) {
				$link = '&format=feed&limitstart=';
				$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
				$document->addHeadLink(JRoute::_($link . '&type=rss', FALSE), 'alternate', 'rel', $attribs);
				$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
				$document->addHeadLink(JRoute::_($link . '&type=atom', FALSE), 'alternate', 'rel', $attribs);
			}
			vmJsApi::jPrice();
		} else {
			$this->setLayout('off_line');
		}

		$error = vRequest::getInt('error',0);

		//Todo this may not work everytime as expected, because the error must be set in the redirect links.
		if(!empty($error)){
			$document->setTitle(tsmText::_('com_tsmart_PRODUCT_NOT_FOUND').tsmText::sprintf('com_tsmart_HOME',$this->vendor->vendor_store_name));
		} else {

			if(empty($this->vendor->customtitle)){

				if ($menu){
					$menuTitle = $menu->params->get('page_title');
					if(empty($menuTitle)) {
						$menuTitle = tsmText::sprintf('com_tsmart_HOME',$this->vendor->vendor_store_name);
					}
					$document->setTitle($menuTitle);
				} else {
					$title = tsmText::sprintf('com_tsmart_HOME',$this->vendor->vendor_store_name);
					$document->setTitle($title);
				}
			} else {
				$document->setTitle($this->vendor->customtitle);
			}


			if(!empty($this->vendor->metadesc)) $document->setMetaData('description',$this->vendor->metadesc);
			if(!empty($this->vendor->metakey)) $document->setMetaData('keywords',$this->vendor->metakey);
			if(!empty($this->vendor->metarobot)) $document->setMetaData('robots',$this->vendor->metarobot);
			if(!empty($this->vendor->metaauthor)) $document->setMetaData('author',$this->vendor->metaauthor);

		}

		if(!class_exists('VmTemplate')) require(VMPATH_SITE.DS.'helpers'.DS.'vmtemplate.php');
		vmTemplate::setTemplate();

		parent::display($tpl);

	}
}
# pure php no closing tag