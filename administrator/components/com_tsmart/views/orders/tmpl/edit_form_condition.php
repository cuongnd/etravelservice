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
<div class="booking-information form-horizontal">
    <div class="row-fluid ">
        <div class="span3">
            <fieldset class="booking-detail">
                <legend><?php echo JText::_('Booking detail') ?></legend>
                <?php echo VmHTML::row_control('text_view_no_input', 'Service name', $this->tour->product_name); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Service date', JHtml::_('date', $this->departure->departure_date, tsmConfig::$date_format)); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Start point', $this->cities_helper->get_path_city_state_country_by_city_id($this->tour->start_city)->full_city); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Ending point', $this->cities_helper->get_path_city_state_country_by_city_id($this->tour->end_city)->full_city); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Customer ID',$this->customer->name); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Passenger No', count($this->list_passenger)); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Booking make', "tour name"); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Branch office', "tour name"); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Assign to', $this->item->asign_name); ?>

            </fieldset>

        </div>
        <div class="span3">
            <fieldset class="quick-task">
                <legend><?php echo JText::_('Quick task') ?></legend>
                <?php echo VmHTML::row_control('quick_task', 'Cron job set rule', "tour name"); ?>
                <?php echo VmHTML::row_control('quick_task', 'Document upload', "tour name"); ?>
                <?php echo VmHTML::row_control('quick_task', 'Change status time', "tour name"); ?>


            </fieldset>
        </div>
        <div class="span3">
            <fieldset class="note" >
                <legend><?php echo JText::_('Note') ?><button type="button" class="btn btn-link add_note"><span class="icon-plus"></span></button></legend>
                <?php echo VmHTML::add_text('add_text_note', "tour name"); ?>
            </fieldset>
        </div>
        <div class="span3">
            <fieldset class="transaction">
                <legend><?php echo JText::_('Transaction') ?></legend>

                <?php echo VmHTML::row_control('text_view_no_input', 'Total cost value', $this->item->order_total); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Receive amount', $this->item->receipt); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Refund amount', ""); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Remain amount', ""); ?>
                <?php echo VmHTML::row_control('text_view_no_input', 'Unpaid invoice', ""); ?>

            </fieldset>
            <fieldset class="set-status">
                <legend><?php echo JText::_('Set status') ?></legend>
                <?php echo VmHTML::row_control('text_view_no_input', 'Holding in', "48 hours"); ?>
            </fieldset>
        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

