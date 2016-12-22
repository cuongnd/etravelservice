<?php
/**
*
* Handle the category view
*
* @package	tsmart
* @subpackage
* @author RolandD
* @link http://www.tsmart.net
* @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 9029 2015-10-28 12:51:49Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmView'))require(VMPATH_SITE.DS.'helpers'.DS.'vmview.php');

/**
* Handle the category view
*
* @package tsmart
* @author RolandD
* @todo set meta data
* @todo add full path to breadcrumb
*/
class TsmartViewCategory extends VmView {

	public function display($tpl = null) {

		$show_prices  = tsmConfig::get('show_prices',1);
		if($show_prices == '1'){
			if(!class_exists('calculationHelper')) require(VMPATH_ADMIN.DS.'helpers'.DS.'calculationh.php');
		}
		$this->assignRef('show_prices', $show_prices);

		if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');

		$document = JFactory::getDocument();

		$app = JFactory::getApplication();
		$pathway = $app->getPathway();

		if (!class_exists('VmImage'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php');

		// set search and keyword
		if ($keyword = vRequest::getString('keyword', false)){//uword('keyword', false, ' ,-,+,.,_')) {
			$pathway->addItem($keyword);
			//$title .=' ('.$keyword.')';
		}
		//$search = vRequest::uword('keyword', null);
		$this->searchcustom = '';
		$this->searchCustomValues = '';
		//if (!empty($keyword)) {
			$this->getSearchCustom();
			$search = $keyword;
		/*} else {
			$keyword ='';
			$search = NULL;
		}*/

		$this->assignRef('keyword', $keyword);
		$this->assignRef('search', $search);

		$menus	= $app->getMenu();
		$menu = $menus->getActive();

		if(!empty($menu->id)){
			ShopFunctionsF::setLastVisitedItemId($menu->id);
		} else if($itemId = vRequest::getInt('Itemid',false)){
			ShopFunctionsF::setLastVisitedItemId($itemId);
		}

		$tsmart_manufacturer_id = vRequest::getInt('tsmart_manufacturer_id', -1 );
		if($tsmart_manufacturer_id ===-1 and !empty($menu->query['tsmart_manufacturer_id'])){
			$tsmart_manufacturer_id = $menu->query['tsmart_manufacturer_id'];
			vRequest::setVar('tsmart_manufacturer_id',$tsmart_manufacturer_id);
		}

		$this->categoryId = vRequest::getInt('tsmart_category_id', -1);
		if($this->categoryId === -1 and !empty($menu->query['tsmart_category_id'])){
			$this->categoryId = $menu->query['tsmart_category_id'];
			vRequest::setVar('tsmart_category_id',$this->categoryId);
		} else if ( $this->categoryId === -1 and $tsmart_manufacturer_id === -1){
			$this->categoryId = ShopFunctionsF::getLastVisitedCategoryId();
		}

		$this->setCanonicalLink($tpl,$document,$this->categoryId,$tsmart_manufacturer_id);

		if (($this->categoryId === -1 or $this->categoryId === 0 ) and $tsmart_manufacturer_id){
			$this->categoryId = 0;
			$catType = 'manufacturer';
			$this->setCanonicalLink($tpl,$document,$tsmart_manufacturer_id,$catType);
		}

		$categoryModel = tmsModel::getModel('category');
		$productModel = tmsModel::getModel('product');

		if($this->categoryId===-1) $this->categoryId = 0;

		$vendorId = 1;
		$category = $categoryModel->getCategory($this->categoryId);

		if(!isset($menu->query['showproducts'])) $menu->query['showproducts'] = 1;
		$this->showproducts = vRequest::getInt('showproducts',$menu->query['showproducts']);

		if(!empty($category)){

			$vendorId = $category->tsmart_vendor_id;
			if($this->showproducts){
			//if(empty($category->category_layout) or $category->category_layout != 'categories') {
				// Load the products in the given category
				$this->products = $productModel->getItemList ();
				//$products = $productModel->getProductsInCategory($this->categoryId);
				$productModel->addImages($this->products, tsmConfig::get('prodimg_browse',1) );



			}

			//No redirect here, for category id = 0 means show ALL categories! note by Max Milbers
			if ((!empty($this->categoryId) and $this->categoryId!==-1 ) and (empty($category->slug) or !$category->published)) {

				if(empty($category->slug)){
					vmInfo(tsmText::_('com_tsmart_CAT_NOT_FOUND'));
				} else {
					if($category->tsmart_id!==0 and !$category->published){
						vmInfo('com_tsmart_CAT_NOT_PUBL',$category->category_name,$this->categoryId);
					}
				}

				//Fallback
				$categoryLink = '';
				if ($category->category_parent_id) {
					$categoryLink = '&view=category&tsmart_category_id=' .$category->category_parent_id;
				} else {
					$last_category_id = shopFunctionsF::getLastVisitedCategoryId();
					if (!$last_category_id or $this->categoryId == $last_category_id) {
						$last_category_id = vRequest::getInt('tsmart_category_id', false);
					}
					if ($last_category_id and $this->categoryId != $last_category_id) {
						$categoryLink = '&view=category&tsmart_category_id=' . $last_category_id;
					}
				}

			    if (tsmConfig::get('handle_404',1)) {
					$app->redirect(JRoute::_('index.php?option=com_tsmart' . $categoryLink . '&error=404', FALSE));
				} else {
					JError::raise(E_ERROR,'404','Not found');
				}

				return;
			}

			shopFunctionsF::setLastVisitedCategoryId($this->categoryId);
			shopFunctionsF::setLastVisitedManuId($tsmart_manufacturer_id);

			// Add the category name to the pathway
			if ($category->parents) {
				foreach ($category->parents as $c){
					$pathway->addItem(strip_tags(tsmText::_($c->category_name)),JRoute::_('index.php?option=com_tsmart&view=category&tsmart_category_id='.$c->tsmart_category_id, FALSE));
				}
			}

			$categoryModel->addImages($category,1);

			if(!isset($menu->query['showcategory'])) $menu->query['showcategory'] = 1;
			$this->showcategory = vRequest::getInt('showcategory',$menu->query['showcategory']);
			//$this->showcategory = vRequest::getInt('showcategory',true);
			if($this->showcategory){
			//if($category->category_layout == 'categories' or ($this->categoryId >0 and $tsmart_manufacturer_id <1)){
				$category->children = $categoryModel->getChildCategoryList( $vendorId, $this->categoryId, $categoryModel->getDefaultOrdering(), $categoryModel->_selectedOrderingDir );
				$categoryModel->addImages($category->children,1);
			} else {
				$category->children = false;
			}

			if (tsmConfig::get('enable_content_plugin', 0)) {
				shopFunctionsF::triggerContentPlugin($category, 'category','category_description');
			}

			if ($category->metadesc) {
				$document->setDescription( $category->metadesc );
			}
			if ($category->metakey) {
				$document->setMetaData('keywords', $category->metakey);
			}
			if ($category->metarobot) {
				$document->setMetaData('robots', $category->metarobot);
			}


			if ($app->getCfg('MetaAuthor') == '1') {
				$document->setMetaData('author', $category->metaauthor);
			}

			if(empty($category->category_template)){
				$category->category_template = tsmConfig::get('categorytemplate');
			}

			if(!empty($menu->query['categorylayout'])){
			//if(!empty($menu->query['categorylayout']) and $menu->query['tsmart_category_id']==$this->categoryId){
				$category->category_layout = $menu->query['categorylayout'];
			}

			$productsLayout = tsmConfig::get('productsublayout','products');
			if(empty($productsLayout)) $productsLayout = 'products';
			$this->productsLayout = empty($menu->query['productsublayout'])? $productsLayout:$menu->query['productsublayout'];

			shopFunctionsF::setVmTemplate($this,$category->category_template,0,$category->category_layout);
		} else {
			//Backward compatibility
			if(!isset($category)) {
				$category = new stdClass();
				$category->category_name = '';
				$category->category_description= '';
				$category->haschildren= false;
			}
		}

		$this->assignRef('category', $category);

	    // Set the titles
		if (!empty($category->customtitle)) {
        	$title = strip_tags($category->customtitle);
     	} elseif (!empty($category->category_name)) {
     		$title = strip_tags($category->category_name);
		} else {
			$title = $this->setTitleByJMenu($app);
		}

		$title = tsmText::_($title);

	  	if(vRequest::getInt('error')){
			$title .=' '.tsmText::_('com_tsmart_PRODUCT_NOT_FOUND');
		}
		if(!empty($keyword)){
			$title .=' ('.strip_tags(htmlspecialchars_decode($keyword)).')';
		}

		if ($tsmart_manufacturer_id>0 and !empty($this->products[0])) $title .=' '.$this->products[0]->mf_name ;
		$document->setTitle( $title );
		// Override Category name when viewing manufacturers products !IMPORTANT AFTER page title.
		if ($tsmart_manufacturer_id>0 and !empty($this->products[0]) and isset($category->category_name)) $category->category_name = $this->products[0]->mf_name ;

		if ($app->getCfg('MetaTitle') == '1') {
			$document->setMetaData('title',  $title);
		}

		parent::display($tpl);
	}

	public function setTitleByJMenu($app){
		$menus	= $app->getMenu();
		$menu = $menus->getActive();

		$title = 'tsmart Category View';
		if ($menu) $title = $menu->title;
		// $title = $this->params->get('page_title', '');
		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = tsmText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = tsmText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		return $title;
	}

	public function setCanonicalLink($tpl,$document,$categoryId,$manId){
		// Set Canonic link
		if (!empty($tpl)) {
			$format = $tpl;
		} else {
			$format = vRequest::getCmd('format', 'html');
		}
		if ($format == 'html') {

			// remove joomla canonical before adding it
			foreach ( $document->_links as $k => $array ) {
				if ( $array['relation'] == 'canonical' ) {
					unset($document->_links[$k]);
					break;
				}
			}

			$link = 'index.php?option=com_tsmart&view=category';
			if($categoryId!==-1){
				$link .= '&tsmart_category_id='.$categoryId;
			}
			if($manId!==-1 and !empty($manId)){
				$link .= '&tsmart_manufacturer_id='.$manId;
			}

			$document->addHeadLink( JRoute::_($link, FALSE) , 'canonical', 'rel', '' );

		}
	}

	/*
	 * generate custom fields list to display as search in FE
	 */
	public function getSearchCustom() {

		$emptyOption  = array('tsmart_custom_id' =>0, 'custom_title' => tsmText::_('com_tsmart_LIST_EMPTY_OPTION'));
		$this->_db =JFactory::getDBO();
		$this->_db->setQuery('SELECT `tsmart_custom_id`, `custom_title` FROM `#__tsmart_customs` WHERE `field_type` ="P"');
		$this->options = $this->_db->loadAssocList();
		$this->custom_parent_id = 0;
		if ($this->custom_parent_id = vRequest::getInt('custom_parent_id', 0)) {
			$this->_db->setQuery('SELECT `tsmart_custom_id`, `custom_title` FROM `#__tsmart_customs` WHERE custom_parent_id='.$this->custom_parent_id);
			$this->selected = $this->_db->loadObjectList();
			$this->searchCustomValues ='';
			foreach ($this->selected as $selected) {
				$this->_db->setQuery('SELECT `custom_value` as tsmart_custom_id,`custom_value` as custom_title FROM `#__tsmart_product_customfields` WHERE tsmart_custom_id='.$selected->tsmart_custom_id);
				 $valueOptions= $this->_db->loadAssocList();
				 $valueOptions = array_merge(array($emptyOption), $valueOptions);
				$this->searchCustomValues .= tsmText::_($selected->custom_title).' '.JHtml::_('select.genericlist', $valueOptions, 'customfields['.$selected->tsmart_custom_id.']', 'class="inputbox"', 'tsmart_custom_id', 'custom_title', 0);
			}
		}

		// add search for declared plugins
		JPluginHelper::importPlugin('vmcustom');
		$dispatcher = JDispatcher::getInstance();
		$plgDisplay = $dispatcher->trigger('plgVmSelectSearchableCustom',array( &$this->options,&$this->searchCustomValues,$this->custom_parent_id ) );

		if(!empty($this->options)){
			$this->options = array_merge(array($emptyOption), $this->options);
			// render List of available groups
			vmJsApi::chosenDropDowns();
			$this->searchCustomList = tsmText::_('com_tsmart_SET_PRODUCT_TYPE').' '.JHtml::_('select.genericlist',$this->options, 'custom_parent_id', 'class="inputbox vm-chzn-select"', 'tsmart_custom_id', 'custom_title', $this->custom_parent_id);
		} else {
			$this->searchCustomList = '';
		}

	}
}


//no closing tag