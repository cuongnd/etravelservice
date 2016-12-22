<?php

defined('_JEXEC') or die('Restricted access');
/**
 *
 * Layout for the shopping cart
 *
 * @package	tsmart
 * @subpackage Cart
 * @author Max Milbers, Valerie Isaksen
 *
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 */
// Plain text formating
// echo sprintf("[%s]\n",      $s); // affichage d'une chaîne standard
// echo sprintf("[%10s]\n",    $s); // justification à droite avec des espaces
// echo sprintf("[%-10s]\n",   $s); // justification à gauche avec des espaces
// echo sprintf("[%010s]\n",   $s); // l'espacement nul fonctionne aussi sur les cha�nes
// echo sprintf("[%'#10s]\n",  $s); // utilisation du caractère personnalis� de s�paration '#'
// echo sprintf("[%10.10s]\n", $t); // justification � gauche mais avec une coupure � 10 caract�res
// $s = 'monkey';
// [monkey]
// [    monkey]
// [monkey    ]
// [0000monkey]
// [####monkey]
// [many monke]
// Check to ensure this file is included in Joomla!
// jimport( 'joomla.application.component.view');
// $viewEscape = new JView();
// $viewEscape->setEscape('htmlspecialchars');
// TODO Temp fix !!!!! *********************************>>>
//$skuPrint = echo sprintf( "%64.64s",strtoupper (vmText::_('com_tsmart_SKU') ) ) ;
// Head of table
echo strip_tags(tsmText::sprintf('com_tsmart_ORDER_PRINT_TOTAL', $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_total,$this->currency))) . "\n";
echo sprintf("%'-64.64s", '') . "\n";
echo tsmText::_('com_tsmart_ORDER_ITEM') . "\n";
foreach ($this->orderDetails['items'] as $item) {
    echo "\n";
    echo $item->product_quantity . ' X ' . $item->order_item_name . ' (' . strtoupper(tsmText::_('com_tsmart_SKU')) . $item->order_item_sku . ')' . "\n";
    //if (!empty($item->product_attribute)) {
	if (!class_exists('tsmartModelCustomfields'))
	    require(VMPATH_ADMIN . DS . 'models' . DS . 'customfields.php');
	$product_attribute = tsmartModelCustomfields::CustomsFieldOrderDisplay($item, 'FE');
	echo "\n" . $product_attribute . "\n";
    //}
    if (!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price) {
	echo $item->product_basePriceWithTax . "\n";
    }

    echo tsmText::_('com_tsmart_ORDER_PRINT_TOTAL') . $item->product_final_price;
    if (tsmConfig::get('show_tax')) {
	echo ' (' . tsmText::_('com_tsmart_ORDER_PRINT_PRODUCT_TAX') . ':' . $this->currency->priceDisplay($item->product_tax,$this->currency) . ')' . "\n";
    }
    echo "\n";
}
echo sprintf("%'-64.64s", '');
echo "\n";

// Coupon
if (!empty($this->orderDetails['details']['BT']->coupon_code)) {
    echo tsmText::_('com_tsmart_COUPON_DISCOUNT') . ':' . $this->orderDetails['details']['BT']->coupon_code . ' ' . tsmText::_('com_tsmart_PRICE') . ':' . $this->currency->priceDisplay($this->orderDetails['details']['BT']->coupon_discount,$this->currency);
    echo "\n";
}



foreach ($this->orderDetails['calc_rules'] as $rule) {
    if ($rule->calc_kind == 'DBTaxRulesBill') {
	echo $rule->calc_rule_name . $this->currency->priceDisplay($rule->calc_amount, $this->currency) . "\n";
    } elseif ($rule->calc_kind == 'taxRulesBill') {
	echo $rule->calc_rule_name . ' ' . $this->currency->priceDisplay($rule->calc_amount,$this->currency) . "\n";
    } elseif ($rule->calc_kind == 'DATaxRulesBill') {
	echo $rule->calc_rule_name . ' ' . $this->currency->priceDisplay($rule->calc_amount,$this->currency) . "\n";
    }
}


echo strtoupper(tsmText::_('com_tsmart_ORDER_PRINT_SHIPPING')) . ' (' . strip_tags(str_replace("<br />", "\n", $this->orderDetails['shipmentName'])) . ' ) ' . "\n";
echo tsmText::_('com_tsmart_ORDER_PRINT_TOTAL') . ' : ' . $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_shipment,$this->currency);
if (tsmConfig::get('show_tax')) {
    echo ' (' . tsmText::_('com_tsmart_ORDER_PRINT_TAX') . ' : ' . $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_shipment_tax,$this->currency) . ')';
}
echo "\n";
echo strtoupper(tsmText::_('com_tsmart_ORDER_PRINT_PAYMENT')) . ' (' . strip_tags(str_replace("<br />", "\n", $this->orderDetails['paymentName'])) . ' ) ' . "\n";
echo tsmText::_('com_tsmart_ORDER_PRINT_TOTAL') . ':' . $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_payment,$this->currency);
if (tsmConfig::get('show_tax')) {
    echo ' (' . tsmText::_('com_tsmart_ORDER_PRINT_TAX') . ' : ' . $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_payment_tax,$this->currency) . ')';
}
echo "\n";

echo sprintf("%'-64.64s", '') . "\n";
// total order
echo tsmText::_('com_tsmart_MAIL_SUBTOTAL_DISCOUNT_AMOUNT') . ' : ' . $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_billDiscountAmount,$this->currency) . "\n";

echo strtoupper(tsmText::_('com_tsmart_ORDER_PRINT_TOTAL')) . ' : ' . $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_total,$this->currency) . "\n";
if (tsmConfig::get('show_tax')) {
    echo ' (' . tsmText::_('com_tsmart_ORDER_PRINT_TAX') . ' : ' . $this->currency->priceDisplay($this->orderDetails['details']['BT']->order_billTaxAmount,$this->currency) . ')' . "\n";
}
echo "\n";

