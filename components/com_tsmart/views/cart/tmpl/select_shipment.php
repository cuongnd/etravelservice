<?php
/**
 *
 * Template for the shipment selection
 *
 * @package	tsmart
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
 * @version $Id: cart.php 2400 2010-05-11 19:30:47Z milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


if (tsmConfig::get('oncheckout_show_steps', 1)) {
	echo '<div class="checkoutStep" id="checkoutStep2">' . tsmText::_('com_tsmart_USER_FORM_CART_STEP2') . '</div>';
}

if ($this->layoutName!='default') {
$headerLevel = 1;
if($this->cart->getInCheckOut()){
	$buttonclass = 'button vm-button-correct';
} else {
	$buttonclass = 'default';
}
?>
<form method="post" id="shipmentForm" name="chooseShipmentRate" action="<?php echo JRoute::_('index.php'); ?>" class="form-validate">
	<?php
	} else {
		$headerLevel = 3;
		$buttonclass = 'vm-button-correct';
	}

	if($this->cart->tsmart_shipmentmethod_id){
		echo '<h'.$headerLevel.' class="vm-shipment-header-selected">'.tsmText::_('com_tsmart_CART_SELECTED_SHIPMENT_SELECT').'</h'.$headerLevel.'>';
	} else {
		echo '<h'.$headerLevel.' class="vm-shipment-header-select">'.tsmText::_('com_tsmart_CART_SELECT_SHIPMENT').'</h'.$headerLevel.'>';
	}


	?>

	<div class="buttonBar-right">
		<?php $dynUpdate = '';
		if( tsmConfig::get('oncheckout_ajax',false)) {
		$dynUpdate=' data-dynamic-update="1" ';
		} ?>
		<button name="updatecart" class="<?php echo $buttonclass ?>" type="submit" <?php echo $dynUpdate ?> ><?php echo tsmText::_('com_tsmart_SAVE'); ?></button>

		<?php   if ($this->layoutName!='default') { ?>
			<button class="<?php echo $buttonclass ?>" type="reset" onClick="window.location.href='<?php echo JRoute::_('index.php?option=com_tsmart&view=cart&task=cancel'); ?>'" ><?php echo tsmText::_('com_tsmart_CANCEL'); ?></button>
		<?php  } ?>
	</div>

	<?php
	if ($this->found_shipment_method ) {

		echo '<fieldset class="vm-payment-shipment-select vm-shipment-select">';
		// if only one Shipment , should be checked by default
		foreach ($this->shipments_shipment_rates as $shipment_shipment_rates) {
			if (is_array($shipment_shipment_rates)) {
				foreach ($shipment_shipment_rates as $shipment_shipment_rate) {
					echo '<div class="vm-shipment-plugin-single">'.$shipment_shipment_rate.'</div>';
				}
			}
		}
		echo '</fieldset>';
	} else {
		echo '<h'.$headerLevel.'>'.$this->shipment_not_found_text.'</h'.$headerLevel.'>';
	}


	if ($this->layoutName!='default') {
	?> <input type="hidden" name="option" value="com_tsmart" />
	<input type="hidden" name="view" value="cart" />
	<input type="hidden" name="task" value="updatecart" />
	<input type="hidden" name="controller" value="cart" />
</form>
<?php
}
?>

