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
                <h3 class="text-uppercase"><?php echo JText::_('Passenger') ?></h3>
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
                        <?php echo $this->sort('location', 'Service name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('creation', 'Service date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Total cost'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('modified_on', 'Payment'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Balance'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Refund') ?>
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

                $list_passenger_per_room_cost=array();
                foreach($this->build_room as $item){
                    $tour_cost_and_room_price=$item->tour_cost_and_room_price;
                    foreach($tour_cost_and_room_price as $item_passenger){
                        $total_cost=0;
                        $passenger_index=$item_passenger->passenger_index;
                        $total_cost+=$item_passenger->tour_cost;
                        $total_cost+=$item_passenger->room_price;
                        $total_cost+=$item_passenger->extra_bed_price;
                        $list_passenger_per_room_cost[$passenger_index]=$total_cost;
                    }

                }
                for ($i = 0, $n = count($this->list_passenger); $i < $n; $i++) {
                    $row = $this->list_passenger[$i];
                    $passenger_index=$row->passenger_index;
                    $row->booking=$this->item->tsmart_order_id;
                    $row->service_name=$this->tour->product_name;
                    $row->service_start_date=JHtml::_('date', $this->departure->departure_date, tsmConfig::$date_format);
                    $row->service_end_date=JHtml::_('date', $this->departure->departure_date_end, tsmConfig::$date_format);
                    $tour_cost=$row->tour_cost;
                    $passenger_per_room_cost=(float)$list_passenger_per_room_cost[$i];
                    $tour_cost+=$passenger_per_room_cost;
                    $row->total_cost=$tour_cost;
                    $row->payment="N/A";
                    $row->balance="N/A";
                    $row->refund="N/A";
                    $row->status="CFM";
                    echo $render_tr($row);
                }

                ?>
            </table>
            <div class="row">
                <div class="span12">
                    <div class="pull-right passenger-control"><a class="add-passenger" href="javascript:void(0)"><?php  echo JText::_('Add passenger') ?></a> || <a class="edit-booking-cost" href="javascript:void(0)"><?php  echo JText::_('edit booking cost') ?></a></div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

