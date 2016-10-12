<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
JHtml::_('jquery.framework');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');

$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');


$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_paymentsetting_default.js');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_paymentsetting_default.less');
AdminUIHelper::startAdminArea($this);
$js_content = '';
$app = JFactory::getApplication();

ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-paymentsetting-default').view_paymentsetting_default({});
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);



?>
<div class="view-paymentsetting-default">
    <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">
        <!--<table>
		<tr>
			<td width="100%">
				<?php /*echo $this->displayDefaultViewSearch ('com_tsmart_CURRENCY','search') ; */ ?>
			</td>
		</tr>
		</table>-->
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <div class="main-payment-setting" >
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo VmHTML::row_control('select',JText::_('Currency'), 'tsmart_currency_id', $this->currencies ,$this->item->tsmart_currency_id,'','tsmart_currency_id', 'currency_name',true) ; ?>
                        <?php echo VmHTML::row_control('select',JText::_('Confirmation mode') , 'config_mode', $this->list_config_mode ,$this->item->config_mode,'','tsmart_state_id', 'state_name',true) ; ?>
                        <?php echo VmHTML::row_control('input_number',JText::_('Deposit term'),'deposit_term',$this->item->deposit_term); ?>
                        <?php echo VmHTML::row_control('input_percent',JText::_('Credit card fee ( if any by % )'),'credit_card_fee',$this->item->credit_card_fee); ?>
                        <?php echo VmHTML::row_control('input','Rule Note',JText::_('rule_note'),$this->item->rule_note,'class="required"'); ?>

                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('select',JText::_('Hold seat'), 'hold_seat', $this->hold_seat_type ,$this->item->hold_seat,'') ; ?>
                        <?php echo VmHTML::row_control('select_percent_amount',JText::_('Deposit Amount'),'deposit_type', 'deposit_amount',$this->item->deposit_type ,$this->item->deposit_amount) ; ?>
                        <?php echo VmHTML::row_control('select_amount_percent','Balance 1 Terms','balance_day_1', 'balance_percent_1',$this->item->balance_day_1 ,$this->item->balance_percent_1,false) ; ?>
                        <?php echo VmHTML::row_control('select_amount_percent','Balance 2 Terms','balance_day_2', 'balance_percent_2',$this->item->balance_day_2 ,$this->item->balance_percent_2,false) ; ?>
                        <?php echo VmHTML::row_control('select_amount_percent','Balance 3 Terms','balance_day_3', 'balance_percent_3',$this->item->balance_day_3 ,$this->item->balance_percent_3,false) ; ?>
                        <?php echo VmHTML::row_basic('list_checkbox', 'Payment option', 'list_payment_method_id', $this->list_payment_method, $this->item->list_payment_method_id, '', 'tsmart_paymentmethod_id', 'payment_name', true,true,true,4); ?>

                    </div>
                </div>
                <div class="footer"></div>
            </div>

        </div>
        <input type="hidden" name="tsmart_paymentsetting_id"
               value="<?php echo $this->item->tsmart_paymentsetting_id; ?>"/>
        <?php echo $this->addStandardHiddenToForm(); ?>
        <?php echo JHtml::_('form.token'); ?>
    </form>

</div>


<?php AdminUIHelper::endAdminArea(); ?>

