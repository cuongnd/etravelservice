<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_virtuemart/assets/less/view_payment_edit.less');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', "payment");
?>
	<div class="view-payment-edit">
		<form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


			<div class="col50">
				<fieldset>
					<legend><?php echo vmText::_('Current payment'); ?></legend>
					<div class="row-fluid">
						<div class="span7">
							<?php echo VmHTML::row_control('input', 'Rule name', 'title', $this->item->title, 'class="required"'); ?>
							<?php echo VmHTML::row_control('select','Currency', 'virtuemart_currency_id', $this->list_currency ,$this->item->virtuemart_currency_id,'','virtuemart_currency_id', 'currency_name',false) ; ?>
							<?php echo VmHTML::row_control('input', 'Vat', 'vat', $this->item->vat, 'class="required" placeholder="Write Vat(%)"'); ?>
							<?php echo VmHTML::row_control('input', 'Credit card fee', 'credit_card_fee', $this->item->credit_card_fee, 'class="required" placeholder="write credit card fee (%)"'); ?>
							<?php echo VmHTML::row_control('list_option', 'Deposit Amount type', 'deposit_amount_type', array('%', 'number'), $this->item->deeposit_amount_type, 'class="required"'); ?>
							<?php echo VmHTML::row_control('input', 'Deposit Amount', 'amount', $this->item->amount, 'class="required" placeholder="write number"'); ?>
							<?php echo VmHTML::row_control('select','Confirm mode', 'mode', $this->list_mode_payment ,$this->item->mode,'','', '',false) ; ?>
							<?php echo VmHTML::row_control('input', 'Deposit', 'deposit_of_day', $this->item->deposit_of_day, 'class="required" placeholder="write number of day"'); ?>
							<?php echo VmHTML::row_control('input', 'Balance', 'balance_of_day', $this->item->balance_of_day, 'class="required" placeholder="write number of day"'); ?>
							<?php echo VmHTML::row_control('input', 'Cancellation', 'cancellation_of_day', $this->item->cancellation_of_day, 'class="required" placeholder="write number of day" '); ?>
							<?php echo VmHTML::row_basic('list_checkbox', 'Payment option', 'list_payment_method_id', $this->list_payment_method, $this->item->list_payment_method_id, '', 'virtuemart_paymentmethod_id', 'payment_name', false); ?>
							<?php echo VmHTML::row_control('booleanlist', 'COM_VIRTUEMART_PUBLISHED', 'published', $this->item->published); ?>

						</div>
						<div class="span5">
							<?php echo VmHTML::row_basic('list_checkbox', 'select tour', 'list_tour_id', $this->list_tour, $this->item->list_tour_id, '', 'virtuemart_product_id', 'product_name', false); ?>

						</div>

					</div>
				</fieldset>

			</div>
			<?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>

			<input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
			<input type="hidden" name="virtuemart_payment_id"
				   value="<?php echo $this->item->virtuemart_payment_id; ?>"/>
			<?php echo $this->addStandardHiddenToForm(); ?>
		</form>

	</div>
<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>