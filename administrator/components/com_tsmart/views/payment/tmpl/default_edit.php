<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_payment_edit.less');

?>
<div class="view-payment-edit">
    <form action="index.php" method="post" class="form-vertical" name="adminForm" id="adminForm">

        <div class="row-fluid">
            <div class="span12">
                <h3 class="title"><?php echo JText::_('Rule information') ?></h3>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <?php echo VmHTML::row_control('input', 'Rule name', 'payment_name', $this->item->payment_name, 'class="required"'); ?>
            </div>
            <div class="span6">
                <?php echo VmHTML::row_control('textarea', 'Rule note','payment_note', $this->item->payment_note,'',100,4); ?>
            </div>
        </div>
        <div class="payment-body">
            <div class="row-fluid">
                <div class="span12">
                    <h3 class="title"><?php echo JText::_('Edit terms') ?></h3>
                </div>
            </div>

            <div class="row-fluid">
                <div class="span6">
                    <?php echo VmHTML::row_control('select', 'Currency', 'tsmart_currency_id', $this->list_currency, $this->item->tsmart_currency_id, '', 'tsmart_currency_id', 'currency_name', false); ?>
                    <?php echo VmHTML::row_control('input_number', 'Credit card fee(if any)', 'credit_card_fee', $this->item->credit_card_fee); ?>
                    <?php echo VmHTML::row_control('list_option', 'Deposit Amount type', 'deposit_amount_type', array(percent=>'percent', number=>'number'), $this->item->deposit_amount_type, 'class="required"'); ?>
                    <?php echo VmHTML::row_control('input', 'Deposit Amount', 'amount', $this->item->amount, 'class="required" placeholder="write number"'); ?>
                    <?php echo VmHTML::row_control('balance_term', 'Balance 1', 'balance_of_day_1','percent_balance_of_day_1', $this->item->balance_of_day_1,$this->item->percent_balance_of_day_1); ?>
                    <?php echo VmHTML::row_control('balance_term', 'Balance 2', 'balance_of_day_2','percent_balance_of_day_2', $this->item->balance_of_day_2,$this->item->percent_balance_of_day_2); ?>


                </div>
                <div class="span6">
                    <?php echo VmHTML::row_control('select', 'Confirm mode', 'mode', $this->list_mode_payment, $this->item->mode, '', '', '', false); ?>
                    <?php echo VmHTML::row_control('hold_seat_option', 'Hold seat option', 'hold_seat','hold_seat_hours', $this->item->hold_seat,$this->item->hold_seat_hours); ?>
                    <?php echo VmHTML::row_control('input_number', 'Deposit term', 'deposit_of_day', $this->item->deposit_of_day); ?>
                    <?php echo VmHTML::row_control('balance_term', 'Balance 3', 'balance_of_day_3','percent_balance_of_day_3', $this->item->balance_of_day_3,$this->item->percent_balance_of_day_3); ?>
                    <?php echo VmHTML::row_control('input_number', 'Cancellation', 'cancellation_of_day', $this->item->cancellation_of_day); ?>

                </div>
            </div>

        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo VmHTML::row_basic('list_checkbox', 'Payment option', 'list_payment_method_id', $this->list_payment_method, $this->item->list_payment_method_id, '', 'tsmart_paymentmethod_id', 'payment_name', 4); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo VmHTML::row_basic('select_table_tour', 'select tour apply', 'list_tour_id',array(), $this->item->list_tour_id); ?>
            </div>
        </div>
        <?php echo VmHTML::inputHidden(array(show_in_parent_window => $this->show_in_parent_window)); ?>
        <?php echo VmHTML::inputHidden(array(tsmart_payment_id => $this->item->tsmart_payment_id)); ?>
        <input type="hidden" value="1" name="published">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="payment" name="controller">
        <input type="hidden" value="payment" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>
        <div class="toolbar pull-right">
            <button class="btn btn-small btn-success save" type="submit"><span class="icon-save icon-white"></span>Save</button>
            <button class="btn btn-small btn-success reset" ><span class="icon-new icon-white"></span>Reset</button>
            <button class="btn btn-small btn-success cancel" ><span class="icon-new icon-white"></span>cancel</button>
        </div>
    </form>

</div>
