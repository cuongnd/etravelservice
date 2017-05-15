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
            <div class="vm-page-nav text-center text-uppercase">
                <h3 class="text-uppercase"><?php echo JText::_('Booking information') ?></h3>
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
                        <?php echo $this->sort('customer_name', 'Service name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('created_on', 'Type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('location', 'Service date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('creation', 'Passenger'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Supplier'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Resource'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('modified_on', 'Total value'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Payment'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Refund'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Balance') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Status') ?>
                    </th>
                </tr>
                </thead>
                <?php
                $render_tr=function($row){
                    $i=0;
                    $checked = JHtml::_('grid.id', $i, $row->tsmart_order_id);
                    ob_start();
                    ?>
                    <tr>
                        <td><?php echo $checked ?></td>
                        <td><?php echo $row->service_name  ?></td>
                        <td><?php echo $row->type  ?></td>
                        <td>
                            <?php echo $row->service_start_date  ?>
                            <br/>
                            <?php echo $row->service_end_date  ?>
                        </td>
                        <td><?php echo $row->passengers  ?></td>
                        <td><?php echo $row->supplier  ?></td>
                        <td><?php echo $row->resource  ?></td>
                        <td><span class="cost"><?php echo $row->total_value  ?></span></td>
                        <td><span class="cost"><?php echo $row->payment  ?></span></td>
                        <td><span class="cost"><?php echo $row->refund  ?></span></td>
                        <td><span class="cost"><?php echo $row->balance  ?></span></td>
                        <td><?php echo $row->status  ?></td>
                    </tr>
                    <?php
                    $html=ob_get_clean();
                    return $html;
                };
                $total_cost=0;
                foreach($this->build_room as $item){
                    $tour_cost_and_room_price=$item->tour_cost_and_room_price;
                    foreach($tour_cost_and_room_price as $item_passenger){
                        $total_cost+=(float)$item_passenger->tour_cost;
                        $total_cost+=(float)$item_passenger->room_price;
                        $total_cost+=(float)$item_passenger->extra_bed_price;
                    }

                }
                $row = new stdClass();
                $row->service_name=$this->tour->product_name;
                $row->type="Package";
                $row->service_start_date=JHtml::_('date', $this->departure->departure_date, tsmConfig::$date_format);
                $row->service_end_date=JHtml::_('date', $this->departure->departure_date_end, tsmConfig::$date_format);
                $row->passengers=count($this->list_passenger);
                $row->supplier="avt";
                $row->resource="client";
                $row->total_value=$total_cost;
                $row->payment="N/A";
                $row->refund="N/A";
                $row->balance="N/A";
                $row->status="CFM";
                echo $render_tr($row);
                foreach($this->transfer as $key=>$list_transfer){
                    foreach($list_transfer as $transfer){
                        $list_passenger_price=$transfer->list_passenger_price;
                        if(!count($list_passenger_price))
                        {
                            continue;
                        }
                        $total_cost_transfer=0;
                        foreach($list_passenger_price as $passenger_price)
                        {
                            $total_cost_transfer+=$passenger_price->cost;
                        }
                        $tsmart_transfer_addon_id=$transfer->tsmart_transfer_addon_id;
                        $transfer_addon=$this->transferaddon_helper->get_transfer_addon_by_transfer_addon_id($tsmart_transfer_addon_id);
                        $item = new stdClass();
                        $item->service_name=$transfer_addon->transfer_addon_name;
                        $item->type="addon-$key";
                        $item->service_start_date="N/A";
                        $item->service_end_date="N/A";
                        $item->passengers=count($list_passenger_price);
                        $item->supplier="avt";
                        $item->resource="client";
                        $item->total_value=$total_cost_transfer;
                        $item->payment="N/A";
                        $item->refund="N/A";
                        $item->balance="N/A";
                        $item->status="CFM";


                        echo $render_tr($item);
                    }
                }
                for ($i = 0, $n = count($this->list_excursion_addon); $i < $n; $i++) {
                    $row = $this->list_excursion_addon[$i];
                    $passengers=$row->passengers;
                    $tsmart_excursion_addon_id=$row->tsmart_excursion_addon_id;
                    $excursion_addon=$this->excursionaddon_helper->get_excursion_addon_by_excursion_addon_id($tsmart_excursion_addon_id);
                    $list_passenger_price=$row->list_passenger_price;
                    $total=0;
                    foreach($list_passenger_price as  $item) {
                        $total+= $item->cost;
                    }
                    $item = new stdClass();
                    $item->service_name=$excursion_addon->excursion_addon_name;
                    $item->type="addon";
                    $item->service_start_date="N/A";
                    $item->service_end_date="N/A";
                    $item->passengers=count($passengers);
                    $item->supplier="avt";
                    $item->resource="client";
                    $item->total_value=$total;
                    $item->payment="N/A";
                    $item->refund="N/A";
                    $item->balance="N/A";
                    $item->status="CFM";


                    echo $render_tr($item);
                }
                for ($i = 0, $n = count($this->night_hotel); $i < $n; $i++) {
                    $row = $this->night_hotel[$i];
                    $list_room_type=$row->list_room_type;
                    $tsmart_hotel_addon_id=$row->tsmart_hotel_addon_id;
                    $hoteladdon=$this->hoteladdon_helper->get_hoteladdon_by_hotel_addon_id($tsmart_hotel_addon_id);
                    $tsmart_hotel_id=$hoteladdon->tsmart_hotel_id;
                    $hotel=$this->hoteladdon_helper->get_detail_hotel_by_hotel_id($tsmart_hotel_id);

                    $list_passenger_price=$row->list_passenger_price;
                    $total=0;
                    foreach($list_passenger_price as  $item) {
                        $total+= $item->cost;
                    }
                    foreach($list_room_type as  $room) {
                        $list_passenger_per_room=reset($room->list_passenger_per_room);
                        $item = new stdClass();
                        $item->service_name=$hotel->hotel_name;
                        $item->type="addon";
                        $item->service_start_date=JHtml::_('date', $row->check_in_date, tsmConfig::$date_format);
                        $item->service_end_date=JHtml::_('date', $row->check_out_date, tsmConfig::$date_format);
                        $item->passengers=count($list_passenger_per_room);
                        $item->supplier="avt";
                        $item->resource="client";
                        $item->total_value=$total;
                        $item->payment="N/A";
                        $item->refund="N/A";
                        $item->balance="N/A";
                        $item->status="CFM";




                        echo $render_tr($item);
                    }
                }
                ?>
            </table>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

