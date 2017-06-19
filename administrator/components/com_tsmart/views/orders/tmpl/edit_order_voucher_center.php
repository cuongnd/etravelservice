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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_order_bookinginfomation.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="passengerconfirmation form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Voucher center') ?></h3>
            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_order_id', 'Id'); ?>
                        </label>

                    </th>
                    <th>
                        <?php echo $this->sort('customer_name', 'Passenger name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('created_on', 'Booking'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('location', 'voucher_type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('creation', 'Issue date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Intergram'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('modified_on', 'Service name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'send log'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Action') ?>
                    </th>
                </tr>
                </thead>
                <?php
                $render_tr=function($row){
                    $i=0;
                    $checked = JHtml::_('grid.id', $i, $row->tsmart_passenger_id);
                    ob_start();
                    ?>
                    <tr>
                        <td><?php echo $checked ?></td>
                        <td><?php echo TSMUtility::get_full_name($row) ?></td>
                        <td><?php echo $row->booking  ?></td>
                        <td><?php echo $row->service_name  ?></td>
                        <td>
                            <?php echo $row->service_start_date  ?>
                            <br/>
                            <?php echo $row->service_end_date  ?>
                        </td>
                        <td><span class="cost"><?php echo $row->total_cost  ?></span></td>
                        <td><span class="cost"><?php echo $row->payment  ?></span></td>
                        <td><span class="cost"><?php echo $row->balance  ?></span></td>
                        <td><span class="cost"><?php echo $row->refund  ?></span></td>
                        <td><?php echo $row->status  ?></td>
                    </tr>
                    <?php
                    $html=ob_get_clean();
                    return $html;
                };
                foreach($this->list_passenger_in_room as $passenger_in_room){
                    $passenger_in_room->service_name = $this->tour->product_name;
                    $passenger_in_room->booking = $this->item->tsmart_order_id;
                    $passenger_in_room->service_start_date = JHtml::_('date', $this->departure->departure_date, tsmConfig::$date_format);
                    $passenger_in_room->service_end_date = JHtml::_('date', $this->departure->departure_date_end, tsmConfig::$date_format);

                    $passenger_in_room->total_cost = $passenger_in_room->tour_cost + $passenger_in_room->extra_fee + $passenger_in_room->room_fee-$passenger_in_room->discount;


                    $passenger_in_room->balance=$passenger_in_room->total_cost-$passenger_in_room->payment;
                    $passenger_in_room->refund=$passenger_in_room->payment-$passenger_in_room->cancel_fee;
                    echo $render_tr($passenger_in_room);
                }
                foreach($this->list_passenger_in_pre_transfer as $passenger_in_transfer){
                    $passenger_in_transfer->booking = $this->item->tsmart_order_id.'-'.$passenger_in_transfer->tsmart_order_transfer_addon_id;
                    $passenger_in_transfer->service_name = $passenger_in_transfer->transfer_addon_name."(pre transfer)";;
                    $passenger_in_transfer->service_start_date = JHtml::_('date', $passenger_in_transfer->checkin_date, tsmConfig::$date_format);
                    $passenger_in_transfer->service_end_date = "N/A";

                    $passenger_in_transfer->total_cost = $passenger_in_transfer->pre_transfer_fee;


                    $passenger_in_transfer->payment = "N/A";
                    $passenger_in_transfer->balance="N/A";
                    $passenger_in_transfer->refund="N/A";
                    echo $render_tr($passenger_in_transfer);
                }
                foreach($this->list_passenger_in_post_transfer as $passenger_in_transfer){
                    $passenger_in_transfer->booking = $this->item->tsmart_order_id.'-'.$passenger_in_transfer->tsmart_order_transfer_addon_id;
                    $passenger_in_transfer->service_name = $passenger_in_transfer->transfer_addon_name."(post transfer)";
                    $passenger_in_transfer->service_start_date = JHtml::_('date', $passenger_in_transfer->checkin_date, tsmConfig::$date_format);
                    $passenger_in_transfer->service_end_date = "N/A";

                    $passenger_in_transfer->total_cost = $passenger_in_transfer->post_transfer_fee;


                    $passenger_in_transfer->payment = "N/A";
                    $passenger_in_transfer->balance="N/A";
                    $passenger_in_transfer->refund="N/A";
                    echo $render_tr($passenger_in_transfer);
                }
                foreach($this->list_passenger_in_pre_night_hotel as $passenger_in_night_hotel){
                    $passenger_in_night_hotel->booking = $this->item->tsmart_order_id.'-'.$passenger_in_night_hotel->tsmart_order_hotel_addon_id;
                    $passenger_in_night_hotel->service_name = $passenger_in_night_hotel->hotel_name."(pre night)";;
                    $passenger_in_night_hotel->service_start_date = JHtml::_('date', $passenger_in_night_hotel->checkin_date, tsmConfig::$date_format);
                    $passenger_in_night_hotel->service_end_date = JHtml::_('date', $passenger_in_night_hotel->checkout_date, tsmConfig::$date_format);

                    $passenger_in_night_hotel->total_cost = $passenger_in_night_hotel->pre_night_hotel_fee;


                    $passenger_in_night_hotel->payment = "N/A";
                    $passenger_in_night_hotel->balance="N/A";
                    $passenger_in_night_hotel->refund="N/A";
                    echo $render_tr($passenger_in_night_hotel);
                }
                foreach($this->list_passenger_in_post_night_hotel as $passenger_in_night_hotel){
                    $passenger_in_night_hotel->booking = $this->item->tsmart_order_id.'-'.$passenger_in_night_hotel->tsmart_order_hotel_addon_id;
                    $passenger_in_night_hotel->service_name = $passenger_in_night_hotel->hotel_name."(post night)";
                    $passenger_in_night_hotel->service_start_date = JHtml::_('date', $passenger_in_night_hotel->checkin_date, tsmConfig::$date_format);
                    $passenger_in_night_hotel->service_end_date = JHtml::_('date', $passenger_in_night_hotel->checkout_date, tsmConfig::$date_format);

                    $passenger_in_night_hotel->total_cost = $passenger_in_night_hotel->post_night_hotel_fee;


                    $passenger_in_night_hotel->payment = "N/A";
                    $passenger_in_night_hotel->balance="N/A";
                    $passenger_in_night_hotel->refund="N/A";
                    echo $render_tr($passenger_in_night_hotel);
                }
                foreach($this->list_passenger_in_excursion as $passenger_in_excursion){
                    $passenger_in_excursion->booking = $this->item->tsmart_order_id.'-'.$passenger_in_excursion->tsmart_order_excursion_addon_id;
                    $passenger_in_excursion->service_name = $passenger_in_excursion->excursion_addon_name;
                    $passenger_in_excursion->service_start_date = "N/A";
                    $passenger_in_excursion->service_end_date = "N/A";

                    $passenger_in_excursion->total_cost = $passenger_in_excursion->excusion_fee;


                    $passenger_in_excursion->payment = "N/A";
                    $passenger_in_excursion->balance="N/A";
                    $passenger_in_excursion->refund="N/A";
                    echo $render_tr($passenger_in_excursion);
                }

                ?>
            </table>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

