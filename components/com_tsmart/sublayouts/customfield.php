<?php
/**
 *
 * renders a customfield
 *
 * @package    tsmart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2015 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @version $Id: addtocartbtn.php 8024 2014-06-12 15:08:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

class tsmartCustomFieldRenderer {


	
	static function renderCustomfieldsFE(&$product,&$customfields,$tsmart_category_id){


		static $calculator = false;
		if(!$calculator){
			if (!class_exists ('calculationHelper')) {
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'calculationh.php');
			}
			$calculator = calculationHelper::getInstance ();
		}

		$selectList = array();

		$dynChilds = 1;

		static $currency = false;
		if(!$currency){
			if (!class_exists ('CurrencyDisplay'))
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
			$currency = CurrencyDisplay::getInstance ();
		}

		foreach($customfields as $k => $customfield){


			if(!isset($customfield->display))$customfield->display = '';

			$calculator->_product = $product;

			if (!class_exists ('vmCustomPlugin')) {
				require(VMPATH_PLUGINLIBS . DS . 'vmcustomplugin.php');
			}

			if ($customfield->field_type == "E") {

				JPluginHelper::importPlugin ('vmcustom');
				$dispatcher = JDispatcher::getInstance ();
				$ret = $dispatcher->trigger ('plgVmOnDisplayProductFEVM3', array(&$product, &$customfields[$k]));
				continue;
			}

			$fieldname = 'field['.$product->tsmart_product_id.'][' . $customfield->tsmart_customfield_id . '][customfield_value]';
			$customProductDataName = 'customProductData['.$product->tsmart_product_id.']['.$customfield->tsmart_custom_id.']';

			//This is a kind of fallback, setting default of custom if there is no value of the productcustom
			$customfield->customfield_value = empty($customfield->customfield_value) ? $customfield->custom_value : $customfield->customfield_value;

			$type = $customfield->field_type;

			$idTag = 'customProductData_'.(int)$product->tsmart_product_id.'_'.$customfield->tsmart_customfield_id;
			$idTag = VmHtml::ensureUniqueId($idTag);

			$emptyOption = new stdClass();
			$emptyOption->text = tsmText::_ ('com_tsmart_ADDTOCART_CHOOSE_VARIANT');
			$emptyOption->value = 0;
			switch ($type) {

				case 'C':

					$html = '';

					$dropdowns = array();

					if(isset($customfield->options->{$product->tsmart_product_id})){
						$productSelection = $customfield->options->{$product->tsmart_product_id};
					} else {
						$productSelection = false;
					}
					$stockhandle = tsmConfig::get('stockhandle', 'none');

					$q = 'SELECT `tsmart_product_id` FROM #__tsmart_products WHERE product_parent_id = "'.$customfield->tsmart_product_id.'" and ( published = "0" ';
					if($stockhandle == 'disableit_children'){
						$q .= ' OR (`product_in_stock` - `product_ordered`) <= "0"';
					}
					$q .= ');';
					$db = JFactory::getDbo();
					$db->setQuery($q);
					$ignore = $db->loadColumn();
					//vmdebug('my q '.$q,$ignore);

					foreach($customfield->options as $product_id=>$variants){

						if($ignore and in_array($product_id,$ignore)){
							//vmdebug('$customfield->options Product to ignore, continue ',$product_id);
							continue;
						}

						foreach($variants as $k => $variant){

							if(!isset($dropdowns[$k]) or !is_array($dropdowns[$k])) $dropdowns[$k] = array();
							if(!in_array($variant,$dropdowns[$k])  ){
								if($k==0 or !$productSelection){
									$dropdowns[$k][] = $variant;
								} else if($k>0 and $productSelection[$k-1] == $variants[$k-1]){
									$break=false;
									for($h=1;$h<=$k;$h++){
										if($productSelection[$h-1] != $variants[$h-1]){
											//$ignore[] = $variant;
											$break=true;
										}
									}
									if(!$break){
										$dropdowns[$k][] = $variant;
									}

								} else {
									//	break;
								}
							}
						}
					}

					$tags = array();

					foreach($customfield->selectoptions as $k => $soption){

						$options = array();
						$selected = false;
						if(isset($dropdowns[$k])){
							foreach($dropdowns[$k] as $i=> $elem){

								$elem = trim((string)$elem);
								$text = $elem;

								if($soption->clabel!='' and in_array($soption->voption,tsmartModelCustomfields::$dimensions) ){
									$rd = $soption->clabel;
									if(is_numeric($rd) and is_numeric($elem)){
										$text = number_format(round((float)$elem,(int)$rd),$rd);
									}
									//vmdebug('($dropdowns[$k] in DIMENSION value = '.$elem.' r='.$rd.' '.$text);
								} else if  ($soption->voption === 'clabels' and $soption->clabel!='') {
									$text = tsmText::_($elem);
								}

								if(empty($elem)){
									$text = tsmText::_('com_tsmart_LIST_EMPTY_OPTION');
								}
								$options[] = array('value'=>$elem,'text'=>$text);

								if($productSelection and $productSelection[$k] == $elem){
									$selected = $elem;
								}
							}
						}


						if(empty($selected)){
							$product->orderable=false;
						}
						$idTagK = $idTag.'cvard'.$k;
						if($customfield->showlabels){
							if( in_array($soption->voption,tsmartModelCustomfields::$dimensions) ){
								$soption->slabel = tsmText::_('com_tsmart_'.strtoupper($soption->voption));
							} else if(!empty($soption->clabel) and !in_array($soption->voption,tsmartModelCustomfields::$dimensions) ){
								$soption->slabel = tsmText::_($soption->clabel);
							}
							if(isset($soption->slabel)){
								$html .= '<span class="vm-cmv-label" >'.$soption->slabel.'</span>';
							}

						}

						$attribs = array('class'=>'vm-chzn-select cvselection no-vm-bind','data-dynamic-update'=>'1','style'=>'min-width:70px;');
						if('productdetails' != vRequest::getCmd('view') or !tsmConfig::get ('jdynupdate', TRUE)){
							$attribs['reload'] = '1';
						}

						$html .= JHtml::_ ('select.genericlist', $options, $fieldname, $attribs , "value", "text", $selected,$idTagK);
						$tags[] = $idTagK;
					}

					$Itemid = vRequest::getInt('Itemid',''); // '&Itemid=127';
					if(!empty($Itemid)){
						$Itemid = '&Itemid='.$Itemid;
					}

					//create array for js
					$jsArray = array();

					$url = '';
					foreach($customfield->options as $product_id=>$variants){

						if($ignore and in_array($product_id,$ignore)){continue;}

						$url = JRoute::_('index.php?option=com_tsmart&view=productdetails&tsmart_category_id=' . $tsmart_category_id . '&tsmart_product_id='.$product_id.$Itemid,false);
						$jsArray[] = '["'.$url.'","'.implode('","',$variants).'"]';
					}

					vmJsApi::addJScript('cvfind',false,false);

					$jsVariants = implode(',',$jsArray);
					$j = "
						jQuery('#".implode(',#',$tags)."').off('change',tsmart.cvFind);
						jQuery('#".implode(',#',$tags)."').on('change', { variants:[".$jsVariants."] },tsmart.cvFind);
					";
					$hash = md5(implode('',$tags));
					vmJsApi::addJScript('cvselvars'.$hash,$j,false);

					//Now we need just the JS to reload the correct product
					$customfield->display = $html;

					break;

				case 'A':

					$html = '';

					$productModel = tmsModel::getModel ('product');

					//Note by Jeremy Magne (Daycounts) 2013-08-31
					//Previously the the product model is loaded but we need to ensure the correct product id is set because the getUncategorizedChildren does not get the product id as parameter.
					//In case the product model was previously loaded, by a related product for example, this would generate wrong uncategorized children list
					$productModel->setId($customfield->tsmart_product_id);

					$uncatChildren = $productModel->getUncategorizedChildren ($customfield->withParent);

					$options = array();

					if(!$customfield->withParent){
						$options[0] = $emptyOption;
						$options[0]->value = JRoute::_ ('index.php?option=com_tsmart&view=productdetails&tsmart_category_id=' . $tsmart_category_id . '&tsmart_product_id=' . $customfield->tsmart_product_id,FALSE);
						//$options[0] = array('value' => JRoute::_ ('index.php?option=com_tsmart&view=productdetails&tsmart_category_id=' . $tsmart_category_id . '&tsmart_product_id=' . $customfield->tsmart_product_id,FALSE), 'text' => vmText::_ ('com_tsmart_ADDTOCART_CHOOSE_VARIANT'));
					}

					$selected = vRequest::getInt ('tsmart_product_id',0);
					$selectedFound = false;

					$parentStock = 0;
					if($uncatChildren){
						foreach ($uncatChildren as $k => $child) {
							/*if(!isset($child[$customfield->customfield_value])){
								vmdebug('The child has no value at index '.$customfield->customfield_value,$customfield,$child);
							} else {*/

								$productChild = $productModel->getProduct((int)$child,false);

								if(!$productChild) continue;
								if(!isset($productChild->{$customfield->customfield_value})){
									vmdebug('The child has no value at index '.$customfield->customfield_value,$customfield,$child);
									continue;
								}
								$available = $productChild->product_in_stock - $productChild->product_ordered;
								if(tsmConfig::get('stockhandle','none')=='disableit_children' and $available <= 0){
									continue;
								}
								$parentStock += $available;
								$priceStr = '';
								if($customfield->wPrice){
									//$product = $productModel->getProductSingle((int)$child['tsmart_product_id'],false);
									$productPrices = $calculator->getProductPrices ($productChild);
									$priceStr =  ' (' . $currency->priceDisplay ($productPrices['salesPrice']) . ')';
								}
								$options[] = array('value' => JRoute::_ ('index.php?option=com_tsmart&view=productdetails&tsmart_category_id=' . $tsmart_category_id . '&tsmart_product_id=' . $productChild->tsmart_product_id,false), 'text' => $productChild->{$customfield->customfield_value}.$priceStr);

								if($selected==$child){
									$selectedFound = true;
									vmdebug($customfield->tsmart_product_id.' $selectedFound by vRequest '.$selected);
								}
								//vmdebug('$child productId ',$child['tsmart_product_id'],$customfield->customfield_value,$child);
							//}
						}
					}

					if(!$selectedFound){
						$pos = array_search($customfield->tsmart_product_id, $product->allIds);
						if(isset($product->allIds[$pos-1])){
							$selected = $product->allIds[$pos-1];
							//vmdebug($customfield->tsmart_product_id.' Set selected to - 1 allIds['.($pos-1).'] = '.$selected.' and count '.$dynChilds);
							//break;
						} elseif(isset($product->allIds[$pos])){
							$selected = $product->allIds[$pos];
							//vmdebug($customfield->tsmart_product_id.' Set selected to allIds['.$pos.'] = '.$selected.' and count '.$dynChilds);
						} else {
							$selected = $customfield->tsmart_product_id;
							//vmdebug($customfield->tsmart_product_id.' Set selected to $customfield->tsmart_product_id ',$selected,$product->allIds);
						}
					}

					$url = 'index.php?option=com_tsmart&view=productdetails&tsmart_category_id='.
					$tsmart_category_id .'&tsmart_product_id='. $selected;
					$attribs['option.key.toHtml'] = false;
					$attribs['id'] = $idTag;
					$attribs['list.attr'] = 'onchange="window.top.location.href=this.options[this.selectedIndex].value" size="1" class="vm-chzn-select no-vm-bind" data-dynamic-update="1" ';
					$attribs['list.translate'] = false;
					$attribs['option.key'] = 'value';
					$attribs['option.text'] = 'text';
					$attribs['list.select'] = JRoute::_ ($url,false);
					$html .= JHtml::_ ('select.genericlist', $options, $fieldname, $attribs);

					vmJsApi::chosenDropDowns();

					if($customfield->parentOrderable==0){
						if($product->tsmart_product_id==$customfield->tsmart_product_id){
							$product->orderable = false;
							$product->product_in_stock = $parentStock;
						}
					}

					$dynChilds++;
					$customfield->display = $html;

					break;

				/*Date variant*/
				case 'D':
					if(empty($customfield->custom_value)) $customfield->custom_value = 'LC2';
					//Customer selects date
					if($customfield->is_input){
						$customfield->display =  '<span class="product_custom_date">' . vmJsApi::jDate ($customfield->customfield_value,$customProductDataName) . '</span>'; //vmJsApi::jDate($field->custom_value, 'field['.$row.'][custom_value]','field_'.$row.'_customvalue').$priceInput;
					}
					//Customer just sees a date
					else {
						$customfield->display =  '<span class="product_custom_date">' . vmJsApi::date ($customfield->customfield_value, $customfield->custom_value, TRUE) . '</span>';
					}

					break;
				/* text area or editor No vmText, only displayed in BE */
				case 'X':
				case 'Y':
					$customfield->display =  $customfield->customfield_value;

					break;
				/* string or integer */
				case 'B':
				case 'S':
				case 'M':

					//vmdebug('Example for params ',$customfield);
					if(isset($customfield->selectType)){
						if(empty($customfield->selectType)){
							$selectType = 'select.genericlist';
							if(!empty($customfield->is_input)){
								vmJsApi::chosenDropDowns();
								$class = 'class="vm-chzn-select"';
							}
						} else {
							$selectType = 'select.radiolist';
							$class = '';
						}
					} else {
						if($type== 'M'){
							$selectType = 'select.radiolist';
							$class = '';
						} else {
							$selectType = 'select.genericlist';
							if(!empty($customfield->is_input)){
								vmJsApi::chosenDropDowns();
								$class = 'class="vm-chzn-select"';
							}
						}
					}

					if($customfield->is_list and $customfield->is_list!=2){

						if(!empty($customfield->is_input)){


							$options = array();

							if($customfield->addEmpty){
								$options[0] = $emptyOption;
							}

							$values = explode (';', $customfield->custom_value);

							foreach ($values as $key => $val) {
								if($val == 0 and $customfield->addEmpty){
									continue;
								}
								if($type == 'M'){
									$tmp = array('value' => $val, 'text' => tsmartModelCustomfields::displayCustomMedia ($val,'product',$customfield->width,$customfield->height));
									$options[] = (object)$tmp;
								} else {
									$options[] = array('value' => $val, 'text' => tsmText::_($val));
								}
							}

							$currentValue = $customfield->customfield_value;

							$customfield->display = JHtml::_ ($selectType, $options, $customProductDataName.'[' . $customfield->tsmart_customfield_id . ']', $class, 'value', 'text', $currentValue,$idTag);
						} else {
							if($type == 'M'){
								$customfield->display =  tsmartModelCustomfields::displayCustomMedia ($customfield->customfield_value,'product',$customfield->width,$customfield->height);
							} else {
								$customfield->display =  tsmText::_ ($customfield->customfield_value);
							}
						}
					} else {

						if(!empty($customfield->is_input)){

							if(!isset($selectList[$customfield->tsmart_custom_id])) {
								$selectList[$customfield->tsmart_custom_id] = $k;
								if($customfield->addEmpty){
									if(empty($customfields[$selectList[$customfield->tsmart_custom_id]]->options)){
										$customfields[$selectList[$customfield->tsmart_custom_id]]->options[0] = $emptyOption;
										$customfields[$selectList[$customfield->tsmart_custom_id]]->options[0]->tsmart_customfield_id = $emptyOption->value;
										//$customfields[$selectList[$customfield->tsmart_custom_id]]->options['nix'] = array('tsmart_customfield_id' => 'none', 'text' => vmText::_ ('com_tsmart_ADDTOCART_CHOOSE_VARIANT'));
									}
								}

								$tmpField = clone($customfield);
								$tmpField->options = null;
								$customfield->options[$customfield->tsmart_customfield_id] = $tmpField;

								$customfield->customProductDataName = $customProductDataName;

							} else {
								$customfields[$selectList[$customfield->tsmart_custom_id]]->options[$customfield->tsmart_customfield_id] = $customfield;
								unset($customfields[$k]);

							}

							$default = reset($customfields[$selectList[$customfield->tsmart_custom_id]]->options);
							foreach ($customfields[$selectList[$customfield->tsmart_custom_id]]->options as &$productCustom) {
								if(!isset($productCustom->customfield_price)) $productCustom->customfield_price = 0.0;
								$price = tsmartModelCustomfields::_getCustomPrice($productCustom->customfield_price, $currency, $calculator);
								if($type == 'M'){
									if(!isset($productCustom->customfield_value)) $productCustom->customfield_value = '';
									$productCustom->text = tsmartModelCustomfields::displayCustomMedia ($productCustom->customfield_value,'product',$customfield->width,$customfield->height).' '.$price;
								} else {
									$trValue = tsmText::_($productCustom->customfield_value);
									if($productCustom->customfield_value!=$trValue and strpos($trValue,'%1')!==false){
										$productCustom->text = tsmText::sprintf($productCustom->customfield_value,$price);
									} else {
										$productCustom->text = $trValue.' '.$price;
									}
								}
							}


							$customfields[$selectList[$customfield->tsmart_custom_id]]->display = JHtml::_ ($selectType, $customfields[$selectList[$customfield->tsmart_custom_id]]->options,
							$customfields[$selectList[$customfield->tsmart_custom_id]]->customProductDataName,
							$class, 'tsmart_customfield_id', 'text', $default->customfield_value,$idTag);	//*/
						} else {
							if($type == 'M'){
								$customfield->display = tsmartModelCustomfields::displayCustomMedia ($customfield->customfield_value,'product',$customfield->width,$customfield->height);
							} else {
								$customfield->display = tsmText::_ ($customfield->customfield_value);
							}
						}
					}

					break;

				// Property
				case 'P':
					//$customfield->display = vmText::_ ('com_tsmart_'.strtoupper($customfield->customfield_value));
					$attr = $customfield->customfield_value;
					$lkey = 'com_tsmart_'.strtoupper($customfield->customfield_value).'_FE';
					$trValue = tsmText::_ ($lkey);
					$options[] = array('value' => 'product_length', 'text' => tsmText::_ ('com_tsmart_PRODUCT_LENGTH'));
					$options[] = array('value' => 'product_width', 'text' => tsmText::_ ('com_tsmart_PRODUCT_WIDTH'));
					$options[] = array('value' => 'product_height', 'text' => tsmText::_ ('com_tsmart_PRODUCT_HEIGHT'));
					$options[] = array('value' => 'product_weight', 'text' => tsmText::_ ('com_tsmart_PRODUCT_WEIGHT'));

					$dim = '';

					if($attr == 'product_length' or $attr == 'product_width' or $attr == 'product_height'){
						$dim = $product->product_lwh_uom;
					} else if($attr == 'product_weight') {
						$dim = $product->product_weight_uom;
					}
					if(!isset($product->$attr)){
						logInfo('customfield.php: case P, property '.$attr.' does not exists. tsmart_custom_id: '.$customfield->tsmart_custom_id);
						break;
					}
					$val= $product->$attr;
					if($customfield->round!=''){
						$val = round($val,$customfield->round);
					}
					if($lkey!=$trValue and strpos($trValue,'%1')!==false) {
						$customfield->display = tsmText::sprintf( $customfield->customfield_value, $val , $dim );
					} else if($lkey!=$trValue) {
						$customfield->display = $trValue.' '.$val;
					} else {
						$customfield->display = tsmText::_ ('com_tsmart_'.strtoupper($customfield->customfield_value)).' '.$val.$dim;
					}

					break;
				case 'Z':
					if(empty($customfield->customfield_value)) break;
					$html = '';
					$q = 'SELECT * FROM `#__tsmart_categories_' . tsmConfig::$vmlang . '` as l INNER JOIN `#__tsmart_categories` AS c using (`tsmart_category_id`) WHERE `published`=1 AND l.`tsmart_category_id`= "' . (int)$customfield->customfield_value . '" ';
					$db = JFactory::getDBO();
					$db->setQuery ($q);
					if ($category = $db->loadObject ()) {

						if(empty($category->tsmart_category_id)) break;

						$q = 'SELECT `tsmart_media_id` FROM `#__tsmart_category_medias`WHERE `tsmart_category_id`= "' . $category->tsmart_category_id . '" ';
						$db->setQuery ($q);
						$thumb = '';
						if ($media_id = $db->loadResult ()) {
							$thumb = tsmartModelCustomfields::displayCustomMedia ($media_id,'category',$customfield->width,$customfield->height);
						}
						$customfield->display = JHtml::link (JRoute::_ ('index.php?option=com_tsmart&view=category&tsmart_category_id=' . $category->tsmart_category_id), $thumb . ' ' . $category->category_name, array('title' => $category->category_name,'target'=>'_blank'));
					}

					break;
				case 'R':
					if(empty($customfield->customfield_value)){
						$customfield->display = 'customfield related product has no value';
						break;
					}
					$pModel = tmsModel::getModel('product');
					$related = $pModel->getProduct((int)$customfield->customfield_value,TRUE,$customfield->wPrice,TRUE,1);

					if(!$related) break;

					$thumb = '';
					if($customfield->wImage){
						if (!empty($related->tsmart_media_id[0])) {
							$thumb = tsmartModelCustomfields::displayCustomMedia ($related->tsmart_media_id[0],'product',$customfield->width,$customfield->height).' ';
						} else {
							$thumb = tsmartModelCustomfields::displayCustomMedia (0,'product',$customfield->width,$customfield->height).' ';
						}
					}

					$customfield->display = shopFunctionsF::renderVmSubLayout('related',array('customfield'=>$customfield,'related'=>$related, 'thumb'=>$thumb));

					break;
			}

			$viewData['customfields'][$k] = $customfield;
			//vmdebug('my customfields '.$type,$viewData['customfields'][$k]->display);
		}

	}

	static function renderCustomfieldsCart($product, $html, $trigger){
		if(isset($product->param)){
			vmTrace('param found, seek and destroy');
			return false;
		}
		$row = 0;
		if (!class_exists ('shopFunctionsF'))
			require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');

		$variantmods = isset($product -> customProductData)?$product -> customProductData:$product -> product_attribute;

		if(empty($variantmods)){
			$productDB = tmsModel::getModel('product')->getProduct($product->tsmart_product_id);
			if($productDB){
				$product->customfields = $productDB->customfields;
			}
		}
		if(!is_array($variantmods)){
			$variantmods = json_decode($variantmods,true);
		}

		$productCustoms = array();
		foreach( (array)$product->customfields as $prodcustom){

			//We just add the customfields to be shown in the cart to the variantmods
			if(is_object($prodcustom)){
				if($prodcustom->is_cart_attribute and !$prodcustom->is_input){
					if(!isset($variantmods[$prodcustom->tsmart_custom_id]) or !is_array($variantmods[$prodcustom->tsmart_custom_id])){
						$variantmods[$prodcustom->tsmart_custom_id] = array();
					}
					$variantmods[$prodcustom->tsmart_custom_id][$prodcustom->tsmart_customfield_id] = false;

				} else if(!empty($variantmods) and !empty($variantmods[$prodcustom->tsmart_custom_id])){

				}
				$productCustoms[$prodcustom->tsmart_customfield_id] = $prodcustom;
			}
		}

		foreach ( (array)$variantmods as $custom_id => $customfield_ids) {

			if(!is_array($customfield_ids)){
				$customfield_ids = array( $customfield_ids =>false);
			}

			foreach($customfield_ids as $customfield_id=>$params){

				if(empty($productCustoms) or !isset($productCustoms[$customfield_id])){
					vmdebug('displayProductCustomfieldSelected continue');
					continue;
				}
				$productCustom = $productCustoms[$customfield_id];
				//vmdebug('displayProductCustomfieldSelected ',$customfield_id,$productCustom);
				//The stored result in vm2.0.14 looks like this {"48":{"textinput":{"comment":"test"}}}
				//and now {"32":[{"invala":"100"}]}
				if (!empty($productCustom)) {
					$otag = ' <span class="product-field-type-' . $productCustom->field_type . '">';
					$tmp = '';
					if ($productCustom->field_type == "E") {

						if (!class_exists ('vmCustomPlugin'))
							require(VMPATH_PLUGINLIBS . DS . 'vmcustomplugin.php');
						JPluginHelper::importPlugin ('vmcustom');
						$dispatcher = JDispatcher::getInstance ();
						$dispatcher->trigger ($trigger.'VM3', array(&$product, &$productCustom, &$tmp));
					}
					else {
						$value = '';

						if (($productCustom->field_type == 'G')) {
							$db = JFactory::getDBO ();
							$db->setQuery ('SELECT  `product_name` FROM `#__tsmart_products_' . tsmConfig::$vmlang . '` WHERE tsmart_product_id=' . (int)$productCustom->customfield_value);
							$child = $db->loadObject ();
							$value = $child->product_name;
						}
						elseif (($productCustom->field_type == 'M')) {
							$customFieldModel = tmsModel::getModel('customfields');
							$value = $customFieldModel->displayCustomMedia ($productCustom->customfield_value,'product',$productCustom->width,$productCustom->height,tsmartModelCustomfields::$useAbsUrls);
						}
						elseif (($productCustom->field_type == 'S')) {

							if($productCustom->is_list and $productCustom->is_input){
								if($productCustom->is_list==2){
									$value = tsmText::_($productCustom->customfield_value);
								} else {
									$value = tsmText::_($params);
								}

							} else {
								$value = tsmText::_($productCustom->customfield_value);
							}
						}
						elseif (($productCustom->field_type == 'A')) {
							if(!property_exists($product,$productCustom->customfield_value)){
								$productDB = tmsModel::getModel('product')->getProduct($product->tsmart_product_id);
								if($productDB){
									$attr = $productCustom->customfield_value;
									$product->$attr = $productDB->$attr;
								}
							}
							$value = tsmText::_( $product->{$productCustom->customfield_value} );
						}
						elseif (($productCustom->field_type == 'C')) {

							foreach($productCustom->options->{$product->tsmart_product_id} as $k=>$option){
								$value .= '<span> ';
								if(!empty($productCustom->selectoptions[$k]->clabel) and in_array($productCustom->selectoptions[$k]->voption,tsmartModelCustomfields::$dimensions)){
									$value .= tsmText::_('com_tsmart_'.$productCustom->selectoptions[$k]->voption);
									$rd = $productCustom->selectoptions[$k]->clabel;
									if(is_numeric($rd) and is_numeric($option)){
										$value .= ' '.number_format(round((float)$option,(int)$rd),$rd);
									}
								} else {
									if(!empty($productCustom->selectoptions[$k]->clabel)) $value .= tsmText::_($productCustom->selectoptions[$k]->clabel);
									$value .= ' '.tsmText::_($option).' ';
								}
								$value .= '</span><br>';
							}
							$value = trim($value);
							if(!empty($value)){
								$html .= $otag.$value.'</span><br />';
							}

							continue;
						}
						else {
							$value = tsmText::_($productCustom->customfield_value);
						}
						$trTitle = tsmText::_($productCustom->custom_title);
						$tmp = '';

						if($productCustom->custom_title!=$trTitle and strpos($trTitle,'%1')!==false){
							$tmp .= tsmText::sprintf($productCustom->custom_title,$value);
						} else {
							$tmp .= $trTitle.' '.$value;
						}
					}
					if(!empty($tmp)){
						$html .= $otag.$tmp.'</span><br />';
					}


				}
				else {
					foreach ((array)$customfield_id as $key => $value) {
						$html .= '<br/ >Couldnt find customfield' . ($key ? '<span>' . $key . ' </span>' : '') . $value;
					}
					vmdebug ('customFieldDisplay, $productCustom is EMPTY '.$customfield_id);
				}
			}

		}

		return $html . '</div>';
	}
}

