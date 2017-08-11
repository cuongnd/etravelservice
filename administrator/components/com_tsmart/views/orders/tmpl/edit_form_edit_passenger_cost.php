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

                        <td class="name">
                            <?php echo TSMUtility::get_full_name($row) ?>
                            <input type="hidden" class="tsmart_passenger_id" value=""/>
                        </td>
                        <td><input class="passenger_cost tour_fee"  type="text" disabled value="<?php echo $row->tour_fee ?>"></td>
                        <td><input class="passenger_cost single_room_fee" type="text" disabled value="<?php echo $row->single_room_fee ?>"></td>
                        <td><input class="passenger_cost extra_fee" type="text" disabled value="<?php echo $row->extra_fee ?>"></td>
                        <td><input class="passenger_cost discount_fee" type="text" value="<?php echo $row->discount_fee ?>"></td>
                        <td><input class="passenger_cost total_cost" disabled type="text" value="<?php echo $row->total_cost?>"></td>
                        <td><input class="passenger_cost payment"  type="text" value="<?php echo $row->payment ?>"></td>
                        <td><input class="passenger_cost balance"  disabled type="text" value="<?php echo $row->balance ?>"></td>
                        <td><input class="passenger_cost cancel_fee"  type="text" value="<?php echo $row->cancel_fee ?>"></td>
                        <td><input class="passenger_cost refund"  disabled type="text" value="<?php echo $row->refund ?>"></td>
                    </tr>
                    <?php
                    $html=ob_get_clean();
                    return $html;
                };
                $total_tour_fee=0;
                for($i=0;$total=count($this->list_passenger_not_in_temporary),$i<$total;$i++){
                    $passenger_in_room=$this->list_passenger_not_in_temporary[$i];
                    $passenger_in_room->tour_fee = $passenger_in_room->tour_cost ;
                    $passenger_in_room->single_room_fee = $passenger_in_room->room_fee ;
                    $passenger_in_room->discount_fee = $passenger_in_room->discount ;
                    $passenger_in_room->total_cost = $passenger_in_room->tour_cost+$passenger_in_room->room_fee+$passenger_in_room->extra_fee-$passenger_in_room->discount;

                    $passenger_in_room->balance = $passenger_in_room->total_cost-$passenger_in_room->payment;
                    $passenger_in_room->refund = $passenger_in_room->payment-$passenger_in_room->cancel_fee;
                    $passenger_in_room->passenger_status =$passenger_in_room->tour_tsmart_passenger_state_id;
                    echo $render_tr($passenger_in_room);

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

