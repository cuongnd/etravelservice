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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_order_bookinginfomation_hotel_addon.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<!--edit_form_rooming_edit_hotel_addon-->
<div class="view_orders_edit_order_bookinginfomation_hotel_addon form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Rooming') ?></h3>
            </div>
            <table class="adminlist table table-striped rooming-list" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th width="5%" class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_order_id', 'Id'); ?>
                        </label>

                    </th>
                    <th width="20%">
                        <?php echo $this->sort('customer_name', 'Passenger'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('title', 'title'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('room_type', 'Room type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('room_note', 'Room note'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('creation', 'Creation'); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr class="item-rooming">
                        <td><span class="order"></span></td>
                        <td><span class="full-name"></span></td>
                        <td><span class="title"></span></td>
                        <td><span class="room-type"></span></td>
                        <td><span class="room-note"></span></td>
                        <td><span class="room-created-on"></span></td>
                    </tr>

                </tbody>
            </table>
            <h4><?php echo JText::_('List passenger unset room') ?></h4>
            <div class="row">
                <div class="span12">
                    <ul class="list_passenger_not_in_temporary_and_not_in_room">
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="span12">
                    <div class="pull-right room-control"><a class="edit-room" href="javascript:void(0)"><?php  echo JText::_('Edit room') ?></a></div>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

