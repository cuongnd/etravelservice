<?php
/**
 * Popup form to edit the formstatus
 *
 * @package	tsmart
 * @subpackage Orders
 * @author Oscar van Eijk
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: order_editstatus.php 8682 2015-02-04 23:21:06Z Milbo $
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

vmJsApi::addJScript( 'orderstatus', "

		function cancelOrderStatFormEdit(e) {
			jQuery('#orderStatForm').each(function(){
				this.reset();
			});
			jQuery('#order_items_status')
				.find('option:selected').prop('selected', true)
				.end().trigger('liszt:updated');
			jQuery('div#updateOrderStatus').hide();
			e.preventDefault();
		}

		");
?>

<form action="index.php" method="post" name="orderStatForm" id="orderStatForm">
<fieldset>
<table class="admintable table" >
	<tr>
		<td align="center" colspan="2">
		<h1><?php echo tsmText::_('com_tsmart_ORDER_UPDATE_STATUS') ?></h1>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo tsmText::_('com_tsmart_ORDER_PRINT_PO_STATUS') ?></td>
		<td><?php echo $this->orderStatSelect; ?>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo tsmText::_('com_tsmart_COMMENT') ?></td>
		<td><textarea rows="6" cols="35" name="comments"></textarea>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo tsmText::_('com_tsmart_ORDER_LIST_NOTIFY') ?></td>
		<td><?php echo VmHTML::checkbox('customer_notified', true); ?>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo tsmText::_('com_tsmart_ORDER_HISTORY_INCLUDE_COMMENT') ?></td>
		<td><br />
		<?php echo VmHTML::checkbox('include_comment', true); ?>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo tsmText::_('com_tsmart_ORDER_UPDATE_LINESTATUS') ?></td>
		<td><br />
		<?php echo VmHTML::checkbox('orders['.$this->orderID.'][update_lines]', true); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="key">
		<a href="#" class="orderStatFormSubmit" >
			<span class="icon-nofloat vmicon vmicon-16-save"></span>&nbsp;<?php echo tsmText::_('com_tsmart_SAVE'); ?></a>&nbsp;&nbsp;&nbsp;
		<a href="#" title="<?php echo tsmText::_('com_tsmart_CANCEL'); ?>" onClick="javascript:cancelOrderStatFormEdit(event);" class="show_element[updateOrderStatus]">
			<span class="icon-nofloat vmicon vmicon-16-remove"></span>&nbsp;<?php echo tsmText::_('com_tsmart_CANCEL'); ?></a>
		</td>
<!--
		<input type="submit" value="<?php echo tsmText::_('com_tsmart_SAVE');?>" style="font-size: 10px" />
		<input type="button"
			onclick="javascript: window.parent.document.getElementById( 'sbox-window' ).close();"
			value="<?php echo tsmText::_('com_tsmart_CANCEL');?>" style="font-size: 10px" /></td>
 -->
	</tr>
</table>
</fieldset>

<!-- Hidden Fields -->
<input type="hidden" name="task" value="updatestatus" />
<input type="hidden" name="last_task" value="updatestatus" />
<input type="hidden" name="option" value="com_tsmart" />
<input type="hidden" name="view" value="orders" />
<input type="hidden" name="coupon_code" value="<?php echo $this->orderbt->coupon_code; ?>" />
<input type="hidden" name="current_order_status" value="<?php echo $this->currentOrderStat; ?>" />
<input type="hidden" name="tsmart_order_id" value="<?php echo $this->orderID; ?>" />
<?php echo JHtml::_( 'form.token' ); ?>
</form>
