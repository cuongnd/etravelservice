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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_edit_room_in_hotel_add_on.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_edit_room_in_hotel_add_on form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Rooming') ?></h3>
            </div>
            <table class="adminlist table table-striped rooming_hotel_add_on" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
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
                    <th>
                        <?php echo JText::_('Action') ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr class="item-rooming" >
                        <td><span class="order"></span></td>
                        <td><span class="full-name"></span></td>
                        <td><span class="title"></span></td>
                        <td><span class="room-type"></span></td>
                        <td><span class="room-note"></span></td>
                        <td><span class="room-created-on"></span></td>
                        <td><a class="remove-passenger-from-rooming-list" href="javascript:void(0)"><span class="icon-remove"></span></a></td>
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
                    <?php echo VmHtml::_('buil_rooming_list.rooming_list',array(),'rooming_list_hotel_add_on','',$this->departure,$this->passenger_config,$this->debug);?>
                </div>
            </div>
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

