<?php
/**
 * Admin form for the checkout configuration settings
 *
 * @package	tsmart
 * @subpackage Config
 * @author Oscar van Eijk
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2011 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_checkout.php 9035 2015-11-03 10:37:57Z Milbo $
 */
defined('_JEXEC') or die('Restricted access');

?>
<fieldset>
	<legend><?php echo tsmText::_('com_tsmart_ADMIN_CFG_CHECKOUT_SETTINGS'); ?></legend>
	<table class="admintable">
		<?php
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_ADDTOCART_POPUP','addtocart_popup',tsmConfig::get('addtocart_popup',1));
		echo VmHTML::row('checkbox','com_tsmart_CFG_POPUP_REL','popup_rel',tsmConfig::get('popup_rel',1));
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_CHECKOUT_OPC','oncheckout_opc',tsmConfig::get('oncheckout_opc',1));
		echo VmHTML::row('checkbox','com_tsmart_CFG_OPC_AJAX','oncheckout_ajax',tsmConfig::get('oncheckout_ajax',1));
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_ONCHECKOUT_SHOW_STEPS','oncheckout_show_steps',tsmConfig::get('oncheckout_show_steps',1));
		echo VmHTML::row('genericlist','com_tsmart_ADMIN_CFG_AUTOMATIC_SHIPMENT',$this->listShipment,'set_automatic_shipment','','tsmart_shipmentmethod_id','shipment_name',tsmConfig::get('set_automatic_shipment',0));
		echo VmHTML::row('genericlist','com_tsmart_ADMIN_CFG_AUTOMATIC_PAYMENT',$this->listPayment,'set_automatic_payment','','tsmart_paymentmethod_id','payment_name',tsmConfig::get('set_automatic_payment',0));
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_CFG_AGREE_TERMS_ONORDER','agree_to_tos_onorder',tsmConfig::get('agree_to_tos_onorder',1));
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_ONCHECKOUT_SHOW_REGISTER','oncheckout_show_register',tsmConfig::get('oncheckout_show_register',1));
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_ONCHECKOUT_ONLY_REGISTERED','oncheckout_only_registered',tsmConfig::get('oncheckout_only_registered',0));
		echo VmHTML::row('checkbox','com_tsmart_ADMIN_ONCHECKOUT_SHOW_PRODUCTIMAGES','oncheckout_show_images',tsmConfig::get('oncheckout_show_images',1));

		echo VmHTML::row('checkbox','com_tsmart_ADMIN_ONCHECKOUT_CHANGE_SHOPPER','oncheckout_change_shopper',tsmConfig::get('oncheckout_change_shopper',1));

		echo VmHTML::row('genericlist','com_tsmart_CFG_DELDATE_INV',$this->osDel_Options,'del_date_type','class="inputbox"', 'order_status_code', 'order_status_name', tsmConfig::get('del_date_type',array('m')), 'del_date_type',true);
		?>

	</table>
</fieldset>