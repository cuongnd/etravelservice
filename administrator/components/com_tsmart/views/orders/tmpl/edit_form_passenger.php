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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_passenger.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_passenger form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Passenger') ?></h3>
            </div>
            <table class="adminlist table table-striped orders_show_form_passenger" cellspacing="0" cellpadding="0">
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
                        <?php echo $this->sort('created_on', 'Book date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Total cost'); ?>
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
                        <?php echo JText::_('Refund') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Status') ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $render_tr=function($row,$index){
                    $checked = JHtml::_('grid.id', $index, $row->tsmart_passenger_id);
                    ob_start();
                    ?>
                    <tr class="passenger">
                        <td><?php echo $checked ?></td>
                        <td>
                            <?php echo TSMUtility::get_full_name($row) ?>
                            <input type="hidden" value="<?php echo $row->tsmart_passenger_id?>" name="tsmart_passenger_id[]">
                        </td>
                        <td class="book_date"><?php echo $row->created_on  ?></td>
                        <td><span class="cost total_cost"><?php echo $row->total_cost  ?></span></td>
                        <td><span class="cost payment"><?php echo $row->payment  ?></span></td>
                        <td><span class="cost balance"><?php echo $row->balance  ?></span></td>
                        <td><span class="cost cancel_fee"><?php echo $row->cancel_fee  ?></span></td>
                        <td><span class="cost refund"><?php echo $row->refund  ?></span></td>
                        <td>
                            <?php echo VmHTML::change_passenger_status(array(), 'change_passenger_status_'.$row->tsmart_passenger_id, $row->passenger_status, 'class="change_passenger_status "'); ?>
                        </td>
                    </tr>
                    <?php
                    $html=ob_get_clean();
                    return $html;
                };
                for($i=0;$total=count($this->list_passenger_not_in_temporary),$i<$total;$i++){
                    $passenger_in_room=$this->list_passenger_not_in_temporary[$i];
                    $passenger_in_room->total_cost = $passenger_in_room->tour_cost+$passenger_in_room->room_fee+$passenger_in_room->extra_fee-$passenger_in_room->discount;
                    $passenger_in_room->balance = $passenger_in_room->total_cost-$passenger_in_room->payment;
                    $passenger_in_room->refund = $passenger_in_room->payment-$passenger_in_room->cancel_fee;
                    $passenger_in_room->passenger_status =$passenger_in_room->tour_tsmart_passenger_state_id;
                    echo $render_tr($passenger_in_room,$i);

                }

                ?>
                </tbody>
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

