<?php
/**
*
* Layout for the add to cart popup
*
* @package	tsmart
* @subpackage Cart
* @author Max Milbers
*
* @link http://www.tsmart.net
* @copyright Copyright (c) 2013 tsmart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* tsmart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

echo '<a class="continue_link" href="' . $this->continue_link . '" >' . tsmText::_('com_tsmart_CONTINUE_SHOPPING') . '</a>';
echo '<a class="showcart floatright" href="' . $this->cart_link . '">' . tsmText::_('com_tsmart_CART_SHOW') . '</a>';
if($this->products){
	foreach($this->products as $product){
		if($product->quantity>0){
			echo '<h4>'.tsmText::sprintf('com_tsmart_CART_PRODUCT_ADDED',$product->product_name,$product->quantity).'</h4>';
		} else {
			if(!empty($product->errorMsg)){
				echo '<div>'.$product->errorMsg.'</div>';
			}
		}

	}
}


if(tsmConfig::get('popup_rel',1)){
	//tsmConfig::$echoDebug=true;
	if ($this->products and is_array($this->products) and count($this->products)>0 ) {

		$product = reset($this->products);

		$customFieldsModel = tmsModel::getModel('customfields');
		$product->customfields = $customFieldsModel->getCustomEmbeddedProductCustomFields($product->allIds,'R');

		$customFieldsModel->displayProductCustomfieldFE($product,$product->customfields);
		if(!empty($product->customfields)){
			?>
			<div class="product-related-products">
			<h4><?php echo tsmText::_('com_tsmart_RELATED_PRODUCTS'); ?></h4>
			<?php
		}
		foreach($product->customfields as $rFields){

				if(!empty($rFields->display)){
				?><div class="product-field product-field-type-<?php echo $rFields->field_type ?>">
				<div class="product-field-display"><?php echo $rFields->display ?></div>
				</div>
			<?php }
		} ?>
		</div>
	<?php
	}
}

?><br style="clear:both">
