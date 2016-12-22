<?php defined('_JEXEC') or die('Restricted access');

$related = $viewData['related'];
$customfield = $viewData['customfield'];
$thumb = $viewData['thumb'];


//juri::root() For whatever reason, we used this here, maybe it was for the mails
echo JHtml::link (JRoute::_ ('index.php?option=com_tsmart&view=productdetails&tsmart_product_id=' . $related->tsmart_product_id . '&tsmart_category_id=' . $related->tsmart_category_id), $thumb   . $related->product_name, array('title' => $related->product_name,'target'=>'_blank'));
if($customfield->wPrice){
	$currency = calculationHelper::getInstance()->_currencyDisplay;
	echo $currency->createPriceDiv ('salesPrice', 'com_tsmart_PRODUCT_SALESPRICE', $related->prices);
}
if($customfield->wDescr){
	echo '<p class="product_s_desc">'.$related->product_s_desc.'</p>';
}