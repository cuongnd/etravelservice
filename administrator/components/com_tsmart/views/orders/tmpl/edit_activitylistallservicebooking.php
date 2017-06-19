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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_edit_activitylistallservicebooking.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_edit_activitylistallservicebooking form-horizontal">
    <div class="list-control-activity">
        <div class="row-fluid ">
            <div class="span12">
                <div class="pull-left">
                    <button type="button" class="btn btn-primary btn-voucher-center"><span class="icon-plus"></span><?php echo JText::_('Voucher center') ?></button>
                    <button type="button" class="btn btn-primary btn-edit-background"><span class="icon-plus"></span><?php echo JText::_('Edit background') ?></button>
                    <button type="button" class="btn btn-primary btn-edit-service"><span class="icon-plus"></span><?php echo JText::_('Edit service') ?></button>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-book-tour"><span class="icon-plus"></span><?php echo JText::_('Book tour') ?></button>
                    <button type="button" class="btn btn-primary btn-book-add-on"><span class="icon-plus"></span><?php echo JText::_('Book add on') ?></button>
                    <button type="button" class="btn btn-primary btn-add-hoc-item"><span class="icon-plus"></span><?php echo JText::_('ad-hoc item') ?></button>
                    <button type="button" class="btn btn-primary btn-add-flight"><span class="icon-plus"></span><?php echo JText::_('add flight') ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center text-uppercase">
                <h3 class="text-uppercase"><?php echo JText::_('Booking management') ?></h3>
            </div>
            <table class="adminlist table table-striped activitylistallservicebooking" cellspacing="0" cellpadding="0">
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
                    $checked = JHtml::_('grid.id', $i, $row->id);
                    ob_start();
                    ?>
                    <tr class="<?php echo $row->is_main_tour?" main-tour ":"" ?>">
                        <td><?php echo $checked ?></td>
                        <td><a class="edit_form <?php echo $row->is_main_tour?" main-tour":$row->key ?>" data-layout="<?php echo $row->layout ?>" href="javascript:void(0)"><?php echo $row->service_name  ?></a></td>
                        <td><?php echo $row->type  ?></td>
                        <td class="service_date">
                            <?php echo $row->service_start_date  ?>
                            <br/>
                            <?php echo $row->service_end_date  ?>
                        </td>
                        <td class="total-passenger"><?php echo $row->passengers>1?JText::sprintf("%s pers",$row->passengers):JText::sprintf("%s per",$row->passengers)  ?></td>
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
                $row = new stdClass();
                $row->id=$this->item->tsmart_order_id;
                $row->service_name=$this->tour->product_name;
                $row->type="Main Tour";
                $row->service_start_date=JHtml::_('date', $this->departure->departure_date, tsmConfig::$date_format);
                $row->service_end_date=JHtml::_('date', $this->departure->departure_date_end, tsmConfig::$date_format);
                $total_passenger_confirm=0;
                foreach($this->list_passenger as $passenger){
                    if($this->passenger_helper->is_confirm($passenger)){
                        $total_passenger_confirm++;
                    }
                }
                $row->passengers=$total_passenger_confirm;
                $row->supplier="avt";
                $row->layout="order_edit";
                $row->key="";
                $row->is_main_tour=true;
                $row->resource="client";
                $row->total_value=$this->item->order_total;
                $row->payment=$this->item->receipt;
                $row->refund="N/A";
                $row->balance=$this->item->order_total-$this->item->receipt;
                $row->status=$this->item->order_status_name;
                echo $render_tr($row);
                foreach($this->list_pre_night_hotel as $pre_night_hotel){
                    $row->id=$pre_night_hotel->tsmart_order_hotel_addon_id;
                    $row->service_name=$pre_night_hotel->hotel_name;
                    $row->type="pre night";
                    $row->key="night_hotel";
                    $row->service_start_date=JHtml::_('date', $pre_night_hotel->checkin_date, tsmConfig::$date_format);
                    $row->service_end_date=JHtml::_('date', $pre_night_hotel->checkout_date, tsmConfig::$date_format);
                    $total_passenger_confirm=0;
                    foreach($this->list_passenger as $passenger){
                        if($this->passenger_helper->is_confirm($passenger)){
                            $total_passenger_confirm++;
                        }
                    }
                    $row->passengers=$pre_night_hotel->total_confirm;
                    $row->supplier="avt";
                    $row->layout="order_edit";
                    $row->is_main_tour=false;
                    $row->resource="client";
                    $row->total_value=$pre_night_hotel->total_cost;
                    $row->payment="N/A";
                    $row->refund="N/A";
                    $row->balance="N/A";
                    $row->status="CFM";
                    echo $render_tr($row);
                }
                foreach($this->list_post_night_hotel as $post_night_hotel){
                    $row->id=$post_night_hotel->tsmart_order_hotel_addon_id;
                    $row->service_name=$post_night_hotel->hotel_name;
                    $row->type="post night";
                    $row->key="night_hotel";
                    $row->key="";
                    $row->service_start_date=JHtml::_('date', $post_night_hotel->checkin_date, tsmConfig::$date_format);
                    $row->service_end_date=JHtml::_('date', $post_night_hotel->checkout_date, tsmConfig::$date_format);
                    $total_passenger_confirm=0;
                    $row->passengers=$post_night_hotel->total_confirm;
                    $row->supplier="avt";
                    $row->layout="order_edit";
                    $row->is_main_tour=false;
                    $row->resource="client";
                    $row->total_value=$post_night_hotel->total_cost;
                    $row->payment="N/A";
                    $row->refund="N/A";
                    $row->balance="N/A";
                    $row->status="CFM";
                    echo $render_tr($row);
                }
                foreach($this->list_pre_transfer as $pre_transfer){
                    $row->id=$pre_transfer->tsmart_order_transfer_addon_id;
                    $row->service_name=$pre_transfer->transfer_addon_name;
                    $row->type="pre transfer";
                    $row->key="transfer";
                    $row->service_start_date=JHtml::_('date', $pre_transfer->checkin_date, tsmConfig::$date_format);
                    $row->service_end_date="N/A";
                    $row->passengers=$pre_transfer->total_confirm;
                    $row->supplier="avt";
                    $row->layout="order_edit";
                    $row->is_main_tour=false;
                    $row->resource="client";
                    $row->total_value=$pre_transfer->total_cost;
                    $row->payment="N/A";
                    $row->refund="N/A";
                    $row->balance="N/A";
                    $row->status="CFM";
                    echo $render_tr($row);
                }
                foreach($this->list_post_transfer as $post_transfer){
                    $row->id=$post_transfer->tsmart_order_transfer_addon_id;
                    $row->service_name=$post_transfer->transfer_addon_name;
                    $row->type="post transfer";
                    $row->key="transfer";
                    $row->service_start_date=JHtml::_('date', $post_transfer->checkin_date, tsmConfig::$date_format);
                    $row->service_end_date="N/A";
                    $row->passengers=$post_transfer->total_confirm;
                    $row->supplier="avt";
                    $row->layout="order_edit";
                    $row->is_main_tour=false;
                    $row->resource="client";
                    $row->total_value=$post_transfer->total_cost;
                    $row->payment="N/A";
                    $row->refund="N/A";
                    $row->balance="N/A";
                    $row->status="CFM";
                    echo $render_tr($row);
                }
                foreach($this->list_excursion as $excursion){
                    $row->id=$excursion->tsmart_order_excursion_addon_id;
                    $row->service_name=$excursion->excursion_addon_name;
                    $row->type="excursion";
                    $row->key="excursion";
                    $row->service_start_date="N/A";
                    $row->service_end_date="N/A";
                    $row->passengers=$excursion->total_confirm;
                    $row->supplier="avt";
                    $row->layout="order_edit";
                    $row->is_main_tour=false;
                    $row->resource="client";
                    $row->total_value=$excursion->total_cost;
                    $row->payment="N/A";
                    $row->refund="N/A";
                    $row->balance="N/A";
                    $row->status="CFM";
                    echo $render_tr($row);
                }

                ?>
            </table>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

