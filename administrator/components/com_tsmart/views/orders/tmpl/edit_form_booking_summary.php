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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_booking_summary.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;


$main_tour_current_discount=0;
$main_tour_current_commission=0;
$total_cost_for_all=0;
$total_cancel=0;
$total_refund=0;
for($i=0;$total=count($this->list_passenger_not_in_temporary),$i<$total;$i++){
    $passenger_in_room=$this->list_passenger_not_in_temporary[$i];
    $passenger_in_room->tour_fee = $passenger_in_room->tour_cost ;
    $passenger_in_room->single_room_fee = $passenger_in_room->room_fee ;
    $passenger_in_room->discount_fee = $passenger_in_room->discount ;
    $passenger_in_room->total_cost = (float)$passenger_in_room->tour_cost+(float)$passenger_in_room->room_fee+(float)$passenger_in_room->extra_fee+(float)$passenger_in_room->surcharge-(float)$passenger_in_room->discount;
    $total_cost_for_all+=(float)$passenger_in_room->total_cost;
    $passenger_in_room->balance = $passenger_in_room->total_cost-$passenger_in_room->payment;
    $total_cancel+=(float)$passenger_in_room->cancel_fee;
    $passenger_in_room->refund = $passenger_in_room->payment-$passenger_in_room->cancel_fee;
    $total_refund+=$passenger_in_room->refund;
    $passenger_in_room->passenger_status =$passenger_in_room->tour_tsmart_passenger_state_id;

}
$payment=$this->main_tour_order->main_tour_payment;
$payment=$payment?$payment:0;
$main_tour_net_total=$total_cost_for_all-((float)$main_tour_current_discount+(float)$main_tour_current_commission+(float)$total_cancel);
$balance=$main_tour_net_total-$payment;
?>
<div class="edit_form_booking_summary form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Booking summary') ?></h3>
            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th>
                        <?php echo JText::_('Gross total'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Discount'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Commission'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Net total'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Payment'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Balance'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Cancel'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Refund'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Status') ?>
                    </th>
                </tr>
                </thead>
                <tr>
                    <td><span class="cost gross_total"><?php echo $total_cost_for_all ?></span></td>
                    <td><span class="cost main_tour_current_discount"><?php echo $main_tour_current_discount ?></span></td>
                    <td><span class="cost main_tour_current_commission"><?php echo $main_tour_current_commission ?></span></td>
                    <td><span class="cost main_tour_net_total"><?php echo $main_tour_net_total ?></span></td>
                    <td><input type="text" class="cost main_tour_current_payment" value="<?php echo $payment ?>"></td>
                    <td><span class="cost balance"><?php echo $balance ?></span></td>
                    <td><span class="cost cancel"><?php echo $total_cancel ?></span></td>
                    <td><span class="cost refund"><?php echo $total_refund ?></span></td>
                    <td>
                        <?php echo VmHTML::change_order_status(array(), 'tsmart_main_tour_order_state_id',$this->main_tour_order->tsmart_main_tour_order_state_id, 'class="change_main_tour_order_status "'); ?>
                    </td>

                </tr>
            </table>

        </div>
    </div>




</div>
<!-- Product pricing -->


<div class="clear"></div>

