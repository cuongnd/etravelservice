<?php
/**
 *
 * Handle the category view
 *
 * @package    tsmart
 * @subpackage
 * @author Valérie Isaksen
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2013 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 6504 2012-10-05 09:40:59Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists ('VmView')) {
	require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');
}

class TsmartViewtsmart extends VmView {

	public function display ($tpl = NULL) {

		$doc = JFactory::getDocument ();

		$show_prices = tsmConfig::get ('show_prices', 1);
		if ($show_prices == '1') {
			if (!class_exists ('calculationHelper')) {
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'calculationh.php');
			}
		}

		if (!class_exists('VmImage'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php');
		$productModel = tmsModel::getModel ('product');
		$feed_show_prices = tsmConfig::get ('feed_home_show_prices', 0);
		$feed_show_images = tsmConfig::get ('feed_home_show_images', 0);
		$feed_show_description = tsmConfig::get ('feed_home_show_description', 0);
		$feed_description_type = tsmConfig::get ('feed_home_description_type', 'product_s_desc');
		$feed_max_text_length = tsmConfig::get ('feed_home_max_text_length', 0);
		$products = array();
		$featured = array();
		$latest = array();
		$topten = array();

		if (tsmConfig::get ('feed_featured_published', 1)) {
			$featured_nb = tsmConfig::get('feed_featured_nb',3);
			$featured = $productModel->getProductListing ('featured', $featured_nb);
		}

		if (tsmConfig::get ('feed_latest_published', 1)) {
			$latest_nb = tsmConfig::get('feed_latest_nb',3);
			$latest = $productModel->getProductListing ('latest', $latest_nb);
		}

		if ( tsmConfig::get ('feed_topten_published', 1)) {
			$topTen_nb = tsmConfig::get('feed_topten_nb',3);
			$topten = $productModel->getProductListing ('topten',$topTen_nb);
		}

		$products = array_merge ($products, $featured, $latest, $topten);


		if ($feed_show_images == 1) {
			$productModel->addImages ($products, 1);
		}
		if ($products && $feed_show_prices == 1) {
			$currency = CurrencyDisplay::getInstance ();
		}


		foreach ($products as $product) {
			$title = $this->escape ($product->product_name);
			$description = "";
			if ($feed_show_images == 1) {
				$effect = " ";
				$return = true;
				$withDescr = false;
				$absUrl = true;
				$description = $product->images[0]->displayMediaThumb ('style="margin-right: 10px; margin-bottom: 10px; float: left;"', false, $effect, $return, $withDescr, $absUrl);
			}
			if ($feed_show_description == 1) {
				if ($feed_description_type == 'product_s_desc') {
					$description .= $product->product_s_desc;
				} else {
					if ($feed_max_text_length > 0) {
						$description .= shopFunctionsF::limitStringByWord ($product->product_desc, $feed_max_text_length);
					} else {
						$description .= $product->product_desc;
					}
				}
			}
			if ($feed_show_prices == 1  and  $show_prices == 1) {
				$description .= $currency->createPriceDiv ('variantModification', 'com_tsmart_PRODUCT_VARIANT_MOD', $product->prices);
				if (round ($product->prices['basePriceWithTax'], $currency->_priceConfig['salesPrice'][1]) != $product->prices['salesPrice']) {
					$description .= '<span class="price-crossed" >' . $currency->createPriceDiv ('basePriceWithTax', 'com_tsmart_PRODUCT_BASEPRICE_WITHTAX', $product->prices) . "</span>";
				}
				if (round ($product->prices['salesPriceWithDiscount'], $currency->_priceConfig['salesPrice'][1]) != $product->prices['salesPrice']) {
					$description .= $currency->createPriceDiv ('salesPriceWithDiscount', 'com_tsmart_PRODUCT_SALESPRICE_WITH_DISCOUNT', $product->prices);
				}
				$description .= $currency->createPriceDiv ('salesPrice', 'com_tsmart_PRODUCT_SALESPRICE', $product->prices);
				$description .= $currency->createPriceDiv ('priceWithoutTax', 'com_tsmart_PRODUCT_SALESPRICE_WITHOUT_TAX', $product->prices);
				$description .= $currency->createPriceDiv ('discountAmount', 'com_tsmart_PRODUCT_DISCOUNT_AMOUNT', $product->prices);
				$description .= $currency->createPriceDiv ('taxAmount', 'com_tsmart_PRODUCT_TAX_AMOUNT', $product->prices);
				$unitPriceDescription = tsmText::sprintf ('com_tsmart_PRODUCT_UNITPRICE', $product->product_unit);
				$description .= $currency->createPriceDiv ('unitPrice', $unitPriceDescription, $product->prices);

			}
			if ($feed_description_type == 'product_s_desc'  OR $feed_max_text_length > 0) {
				$description .= '<p class="feed-readmore"><a target="_blank" href ="' .JRoute::_($product->link). '">' . tsmText::_ ('com_tsmart_FEED_READMORE') . '</a></p>';
			}
			$item = new JFeedItem();
			$item->title = $title;
			$item->link = JRoute::_($product->link);
			$item->date = $product->created_on;
			$item->description = '<div class="feed-description">' . $description . '</div>';
			$item->category = $product->tsmart_category_id;
			$doc->addItem ($item);

		}
	}

}