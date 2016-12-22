<?php
/**
 *
 * Show the product prices
 *
 * @package    tsmart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2014 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_showprices.php 8024 2014-06-12 15:08:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
$product = $viewData['product'];
$currency = $viewData['currency'];
?>
<div class="product-price" id="productPrice<?php echo $product->tsmart_product_id ?>">
	<?php
	if (!empty($product->prices['salesPrice'])) {
		//echo '<div class="vm-cart-price">' . vmText::_ ('com_tsmart_CART_PRICE') . '</div>';
	}

	if ($product->prices['salesPrice']<=0 and tsmConfig::get ('askprice', 1) and isset($product->images[0]) and !$product->images[0]->file_is_downloadable) {
		$askquestion_url = JRoute::_('index.php?option=com_tsmart&view=productdetails&task=askquestion&tsmart_product_id=' . $product->tsmart_product_id . '&tsmart_category_id=' . $product->tsmart_category_id . '&tmpl=component', FALSE);
		?>
		<a class="ask-a-question bold" href="<?php echo $askquestion_url ?>" rel="nofollow" ><?php echo tsmText::_ ('com_tsmart_PRODUCT_ASKPRICE') ?></a>
		<?php
	} else {
	//if ($showBasePrice) {
		echo $currency->createPriceDiv ('basePrice', 'com_tsmart_PRODUCT_BASEPRICE', $product->prices);
		//if (round($product->prices['basePrice'],$currency->_priceConfig['basePriceVariant'][1]) != $product->prices['basePriceVariant']) {
			echo $currency->createPriceDiv ('basePriceVariant', 'com_tsmart_PRODUCT_BASEPRICE_VARIANT', $product->prices);
		//}

	//}
	echo $currency->createPriceDiv ('variantModification', 'com_tsmart_PRODUCT_VARIANT_MOD', $product->prices);
	if (round($product->prices['basePriceWithTax'],$currency->_priceConfig['salesPrice'][1]) != round($product->prices['salesPrice'],$currency->_priceConfig['salesPrice'][1])) {
		echo '<span class="price-crossed" >' . $currency->createPriceDiv ('basePriceWithTax', 'com_tsmart_PRODUCT_BASEPRICE_WITHTAX', $product->prices) . "</span>";
	}
	if (round($product->prices['salesPriceWithDiscount'],$currency->_priceConfig['salesPrice'][1]) != round($product->prices['salesPrice'],$currency->_priceConfig['salesPrice'][1])) {
		echo $currency->createPriceDiv ('salesPriceWithDiscount', 'com_tsmart_PRODUCT_SALESPRICE_WITH_DISCOUNT', $product->prices);
	}
	echo $currency->createPriceDiv ('salesPrice', 'com_tsmart_PRODUCT_SALESPRICE', $product->prices);
	if ($product->prices['discountedPriceWithoutTax'] != $product->prices['priceWithoutTax']) {
		echo $currency->createPriceDiv ('discountedPriceWithoutTax', 'com_tsmart_PRODUCT_SALESPRICE_WITHOUT_TAX', $product->prices);
	} else {
		echo $currency->createPriceDiv ('priceWithoutTax', 'com_tsmart_PRODUCT_SALESPRICE_WITHOUT_TAX', $product->prices);
	}
	echo $currency->createPriceDiv ('discountAmount', 'com_tsmart_PRODUCT_DISCOUNT_AMOUNT', $product->prices);
	echo $currency->createPriceDiv ('taxAmount', 'com_tsmart_PRODUCT_TAX_AMOUNT', $product->prices);
	$unitPriceDescription = tsmText::sprintf ('com_tsmart_PRODUCT_UNITPRICE', tsmText::_('com_tsmart_UNIT_SYMBOL_'.$product->product_unit));
	echo $currency->createPriceDiv ('unitPrice', $unitPriceDescription, $product->prices);
	}
	?>
</div>

