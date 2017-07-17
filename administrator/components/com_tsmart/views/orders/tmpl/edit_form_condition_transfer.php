<?php
/**
 *
 * Main product information
 *
 * @package    tsmart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2015 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_information.php 8982 2015-09-14 09:45:02Z Milbo $
 */
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_condition.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$currency=$this->currency_helper->get_currency_detail_by_currency_id($this->payment_rule->tsmart_currency_id);
if($this->payment_rule->deposit_type==1){
    $deposit=$this->payment_rule->deposit_amount.'%';
}else{
    $deposit=$this->payment_rule->deposit_amount.' '.$currency->currency_code;
}
$list_payment_method = $this->paymentmethod_helper->get_list_payment_method();
$list_payment_method_id = $this->paymentmethod_helper->get_list_payment_method_id_by_payment_id($this->payment_rule->tsmart_paymentsetting_id);
// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_condition form-vertical">
    <div class="row-fluid">
        <div class="span12">
            <h3 class="title text-center"><?php echo JText::_('Condition and terms') ?></h3>
        </div>
    </div>
    <div class="payment-body">
        <div class="row-fluid">
            <div class="span4">
                <?php echo VmHTML::row_control('text_view',JText::_('Default currency'), $currency->currency_name); ?>
                <?php echo VmHTML::row_control('text_view',JText::_('Credit card fee'), $this->payment_rule->credit_card_fee.'%'); ?>
                <?php echo VmHTML::row_control('text_view',JText::_('Deposit requirement'), $deposit); ?>
                <?php echo VmHTML::row_control('text_view',JText::_('Balance 1'), JText::sprintf('%s,%s days before the trip date',$this->payment_rule->balance_percent_1.'%',$this->payment_rule->balance_day_1)); ?>
            </div>
            <div class="span4">
                <?php echo VmHTML::row_control('text_view',JText::_('Balance 2'), JText::sprintf('%s,%s days before the trip date',$this->payment_rule->balance_percent_2.'%',$this->payment_rule->balance_day_2)); ?>
                <?php echo VmHTML::row_control('text_view',JText::_('Balance 3'), JText::sprintf('%s,%s days before the trip date',$this->payment_rule->balance_percent_3.'%',$this->payment_rule->balance_day_3)); ?>
                <?php echo VmHTML::row_control('text_view',JText::_('Hold seat'), $this->payment_rule->hold_seat!=0?JText::_('Accept'):"No accept"); ?>
                <?php echo VmHTML::row_control('text_view',JText::_('Rule note'), $this->payment_rule->rule_note); ?>
            </div>
            <div class="span4">
                <?php echo VmHTML::row_control('text_view',JText::_('Early bird discount'),'' ); ?>
                <?php echo VmHTML::row_control('text_view',JText::_('Last minute discount'), ''); ?>
                <?php echo VmHTML::row_basic('list_checkbox', 'Payment option', 'list_payment_method_id', $list_payment_method, $list_payment_method_id, '', 'tsmart_paymentmethod_id', 'payment_name', false); ?>

            </div>
        </div>

    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

