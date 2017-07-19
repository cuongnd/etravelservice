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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_passenger_edit_excursion.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<!--edit_form_passenger_edit_excursion_addon-->
<div class="view_orders_edit_form_passenger_edit_excursion form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Passenger') ?></h3>
            </div>
            <table class="adminlist table table-striped orders_show_form_passenger_edit_excursion_addon" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th ><?php echo JText::_('Id') ?></th>
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
                <tr class="passenger">
                    <td><label><input class="tsmart_passenger_id" type="checkbox"><span class="tsmart_passenger_id"></span></label></td>
                    <td>
                        <span class="full-name"></span>
                        <input type="hidden" value="" class="tsmart_passenger_id">
                    </td>
                    <td class="book_date"></td>
                    <td><span class="cost total_cost"></span></td>
                    <td><span class="cost payment"></span></td>
                    <td><span class="cost balance"></span></td>
                    <td><span class="cost cancel_fee"></span></td>
                    <td><span class="cost refund"></span></td>
                    <td>
                        <?php echo VmHTML::change_passenger_status(array(), 'change_passenger_status', "", ''); ?>
                    </td>
                </tr>

                </tbody>
            </table>
            <div class="row">
                <div class="span12">
                    <div class="pull-right passenger-control">
                        <a class="edit-excursion-add-passenger" href="javascript:void(0)"><?php  echo JText::_('Add passenger') ?></a> | <a class="edit-passenger-cost" href="javascript:void(0)"><?php  echo JText::_('Edit cost') ?></a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

