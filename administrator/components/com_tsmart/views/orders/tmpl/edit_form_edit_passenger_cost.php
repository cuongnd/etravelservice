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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_edit_passenger_cost.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_edit_passenger_cost form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Passenger cost') ?></h3>
            </div>
            <table class="adminlist table table-striped edit_passenger_cost" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th width="20%">
                        <?php echo $this->sort('passenger_name', 'Passenger name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('tour_fee', 'Tour fee'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('supplement_fee', 'single room'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('extra_fee', 'Extra fee'); ?>
                    </th>

                    <th>
                        <?php echo $this->sort('discount', 'Discount'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('total_cost', 'Total cost'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('payment', 'Payment'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('balance', 'Balance'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('cancel', 'Cancel'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('refund', 'Refund'); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $render_tr=function($row){
                    $i=0;
                    ob_start();
                    ?>
                    <tr class="passenger">
                        <td class="name"><?php echo TSMUtility::get_full_name($row) ?></td>
                        <td><input class="passenger_cost tour_fee"  type="text" value="<?php echo $row->tour_fee ?>"></td>
                        <td><input class="passenger_cost single_room_fee" type="text" value="<?php echo $row->single_room_fee ?>"></td>
                        <td><input class="passenger_cost extra_fee" type="text" value="<?php echo $row->extra_fee ?>"></td>
                        <td><input class="passenger_cost discount_fee" type="text" value="<?php echo $row->discount_fee ?>"></td>
                        <td><input class="passenger_cost total_cost" readonly type="text" value="<?php echo $row->total_cost?>"></td>
                        <td><input class="passenger_cost payment"  type="text" value="<?php echo $row->payment ?>"></td>
                        <td><input class="passenger_cost balance"  readonly type="text" value="<?php echo $row->balance ?>"></td>
                        <td><input class="passenger_cost cancel_fee"  type="text" value="<?php echo $row->cancel_fee ?>"></td>
                        <td><input class="passenger_cost refund"  readonly type="text" value="<?php echo $row->refund ?>"></td>
                    </tr>
                    <?php
                    $html=ob_get_clean();
                    return $html;
                };
                if($this->order_data->modified_by){
                    for ($i = 0, $n = count($this->list_passenger); $i < $n; $i++) {
                        $row = $this->list_passenger[$i];
                        $passenger_index = $row->passenger_index;
                        $row->tour_fee = $row->tour_cost;
                        $row->extra_fee =$row->extra_fee;

                        $single_room_fee = $row->single_room_fee;
                        $single_room_fee = $single_room_fee != "" && is_numeric($single_room_fee) && $single_room_fee > 0 ? $single_room_fee : 0;
                        $row->single_room_fee = $single_room_fee;

                        $row->total_cost = $row->tour_cost + $row->extra_fee + $row->single_room_fee;

                        $discount_fee = $row->discount_fee;
                        $discount_fee = $discount_fee != "" && is_numeric($discount_fee) && $discount_fee > 0 ? $discount_fee : 0;
                        $row->discount_fee = $discount_fee;

                        $payment = $row->payment;
                        $payment = $payment != "" && is_numeric($payment) && $payment > 0 ? $payment : 0;
                        $row->payment = $payment;

                        $cancel_fee = $row->cancel_fee;
                        $cancel_fee = $cancel_fee != "" && is_numeric($cancel_fee) && $cancel_fee > 0 ? $cancel_fee : 0;
                        $row->cancel_fee = $cancel_fee;
                        echo $render_tr($row);
                    }

                }else {
                    $single_room_fee = array();
                    foreach ($this->build_room as $item) {
                        $tour_cost_and_room_price = $item->tour_cost_and_room_price;
                        foreach ($tour_cost_and_room_price as $item_passenger) {
                            $total_cost = 0;
                            $passenger_index = $item_passenger->passenger_index;
                            $total_cost += $item_passenger->room_price;
                            $single_room_fee[$passenger_index] = $total_cost;
                        }

                    }
                    $list_extra_fee = array();
                    foreach ($this->build_room as $item) {
                        $tour_cost_and_room_price = $item->tour_cost_and_room_price;
                        foreach ($tour_cost_and_room_price as $item_passenger) {
                            $total_cost = 0;
                            $passenger_index = $item_passenger->passenger_index;
                            $total_cost += $item_passenger->extra_bed_price;
                            $list_extra_fee[$passenger_index] = $total_cost;
                        }

                    }


                    for ($i = 0, $n = count($this->list_passenger); $i < $n; $i++) {
                        $row = $this->list_passenger[$i];
                        $passenger_index = $row->passenger_index;
                        $row->booking = $this->item->tsmart_order_id;
                        $row->service_name = $this->tour->product_name;
                        $row->service_start_date = JHtml::_('date', $this->departure->departure_date, tsmConfig::$date_format);
                        $row->service_end_date = JHtml::_('date', $this->departure->departure_date_end, tsmConfig::$date_format);
                        $tour_cost = $row->tour_cost;
                        $passenger_per_extra_fee = (float)$list_extra_fee[$i];
                        $tour_cost += $passenger_per_extra_fee;

                        $row->tour_fee = $row->tour_cost;
                        $row->extra_fee = $passenger_per_extra_fee;

                        $row->balance = "N/A";
                        $single_room_fee = $single_room_fee[$i];
                        $single_room_fee = $single_room_fee != "" && is_numeric($single_room_fee) && $single_room_fee > 0 ? $single_room_fee : 0;
                        $row->single_room_fee = $single_room_fee;

                        $row->total_cost = $row->tour_cost + $row->extra_fee + $row->single_room_fee;

                        $discount_fee = $row->discount_fee;
                        $discount_fee = $discount_fee != "" && is_numeric($discount_fee) && $discount_fee > 0 ? $discount_fee : 0;
                        $row->discount_fee = $discount_fee;

                        $payment = $row->payment;
                        $payment = $payment != "" && is_numeric($payment) && $payment > 0 ? $payment : 0;
                        $row->payment = $payment;

                        $cancel_fee = $row->cancel_fee;
                        $cancel_fee = $cancel_fee != "" && is_numeric($cancel_fee) && $cancel_fee > 0 ? $cancel_fee : 0;
                        $row->cancel_fee = $cancel_fee;
                        echo $render_tr($row);
                    }
                }
                ?>
                </tbody>
            </table>
            <div class="row-fluid">
                <div class="span12">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary save"><?php echo JText::_('Save') ?></button>
                        <button type="button" class="btn btn-primary cancel"><?php echo JText::_('Cancel') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

