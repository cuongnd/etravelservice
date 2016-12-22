<?php
/**
 *
 * Layout for the shopping cart
 *
 * @package    tsmart
 * @subpackage Cart
 * @author Max Milbers
 *
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

JHtml::_ ('behavior.formvalidation');

?>

<div id="cart-view" class="cart-view">
	<div class="vm-cart-header-container">
		<div class="width50 floatleft vm-cart-header">
			<h1><?php echo tsmText::_ ('com_tsmart_CART_TITLE'); ?></h1>
			<div class="payments-signin-button" ></div>
		</div>
		<?php if (tsmConfig::get ('oncheckout_show_steps', 1) && $this->checkout_task === 'confirm') {
			echo '<div class="checkoutStep" id="checkoutStep4">' . tsmText::_ ('com_tsmart_USER_FORM_CART_STEP4') . '</div>';
		} ?>
		<div class="width50 floatleft right vm-continue-shopping">
			<?php // Continue Shopping Button
			if (!empty($this->continue_link_html)) {
				echo $this->continue_link_html;
			} ?>
		</div>
		<div class="clear"></div>
	</div>

	<?php
	$uri = vmURI::getCleanUrl();
	$uri = str_replace('&tmpl=component','',$uri);
	echo shopFunctionsF::getLoginForm ($this->cart, FALSE,$uri);

	// This displays the form to change the current shopper
	if ($this->allowChangeShopper){
		echo $this->loadTemplate ('shopperform');
	}


	$taskRoute = '';
	?><form method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_ ('index.php?option=com_tsmart&view=cart' . $taskRoute, $this->useXHTML, $this->useSSL); ?>">
		<?php
		if(tsmConfig::get('multixcart')=='byselection'){
			if (!class_exists('ShopFunctions')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
			echo shopFunctions::renderVendorFullVendorList($this->cart->vendorId);
			?><input type="submit" name="updatecart" title="<?php echo tsmText::_('com_tsmart_SAVE'); ?>" value="<?php echo tsmText::_('com_tsmart_SAVE'); ?>"class="button"style="margin-left: 10px;"/><?php
		}
		echo $this->loadTemplate ('address');
		// This displays the pricelist MUST be done with tables, because it is also used for the emails
		echo $this->loadTemplate ('pricelist');

		if (!empty($this->checkoutAdvertise)) {
			?> <div id="checkout-advertise-box"> <?php
			foreach ($this->checkoutAdvertise as $checkoutAdvertise) {
				?>
				<div class="checkout-advertise">
					<?php echo $checkoutAdvertise; ?>
				</div>
			<?php
			}
			?></div><?php
		}

		echo $this->loadTemplate ('cartfields');

		?> <div class="checkout-button-top"> <?php
			echo $this->checkout_link_html;
			?></div>

		<?php // Continue and Checkout Button END ?>
		<input type='hidden' name='order_language' value='<?php echo $this->order_language; ?>'/>
		<input type='hidden' name='task' value='updatecart'/>
		<input type='hidden' name='option' value='com_tsmart'/>
		<input type='hidden' name='view' value='cart'/>
	</form>


<?php

if(tsmConfig::get('oncheckout_ajax',false)){
	vmJsApi::addJScript('updDynamicListeners',"
if (typeof tsmart.containerSelector === 'undefined') tsmart.containerSelector = '#cart-view';
if (typeof tsmart.container === 'undefined') tsmart.container = jQuery(tsmart.containerSelector);

jQuery(document).ready(function() {
	if (tsmart.container)
		tsmart.updDynFormListeners();
}); ");
}


vmJsApi::addJScript('vm.checkoutFormSubmit',"
tsmart.bCheckoutButton = function(e) {
	e.preventDefault();
	jQuery(this).vm2front('startVmLoading');
	jQuery(this).attr('disabled', 'true');
	jQuery(this).removeClass( 'vm-button-correct' );
	jQuery(this).addClass( 'vm-button' );
	jQuery(this).fadeIn( 400 );
	var name = jQuery(this).attr('name');
	var div = '<input name=\"'+name+'\" value=\"1\" type=\"hidden\">';

	jQuery('#checkoutForm').append(div);
	//tsmart.updForm();
	jQuery('#checkoutForm').submit();
}
jQuery(document).ready(function($) {
	jQuery(this).vm2front('stopVmLoading');
	var el = jQuery('#checkoutFormSubmit');
	el.unbind('click dblclick');
	el.on('click dblclick',tsmart.bCheckoutButton);
});
	");

if( !tsmConfig::get('oncheckout_ajax',false)) {
	vmJsApi::addJScript('vm.STisBT',"
		jQuery(document).ready(function($) {

			if ( $('#STsameAsBTjs').is(':checked') ) {
				$('#output-shipto-display').hide();
			} else {
				$('#output-shipto-display').show();
			}
			$('#STsameAsBTjs').click(function(event) {
				if($(this).is(':checked')){
					$('#STsameAsBT').val('1') ;
					$('#output-shipto-display').hide();
				} else {
					$('#STsameAsBT').val('0') ;
					$('#output-shipto-display').show();
				}
				var form = jQuery('#checkoutFormSubmit');
				form.submit();
			});
		});
	");
}

$this->addCheckRequiredJs();
echo vmJsApi::writeJS();

?>
</div>