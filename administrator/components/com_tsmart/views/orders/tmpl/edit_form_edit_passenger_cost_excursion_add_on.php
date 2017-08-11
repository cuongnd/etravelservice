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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_edit_passenger_cost_edit_excursion_addon.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_edit_passenger_cost_edit_excursion_addon form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Passenger cost') ?></h3>
            </div>
            <table class="adminlist table table-striped edit_passenger_cost_excursion_addon_edit_passenger" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th width="2%"><?php echo JText::_('ID') ?></th>
                    <th width="20%">
                        <?php echo $this->sort('passenger_name', 'Passenger name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('excursion_fee', 'Excursion fee'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('surcharge', 'Surcharge'); ?>
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
                        <?php echo $this->sort('cancel', 'Cancel'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('refund', 'Refund'); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr class="passenger">
                        <td><span class="tsmart_passenger_id"></span></td>
                        <td class="name">
                            <span class="full-name"></span>
                            <input type="hidden" class="tsmart_passenger_id" value=""/>
                        </td>
                        <td><input  class="passenger_cost excursion_fee" disabled  type="text" value=""></td>
                        <td><input class="passenger_cost excursion_surcharge" type="text" value=""></td>
                        <td><input class="passenger_cost excursion_discount" type="text" value=""></td>
                        <td><input class="passenger_cost total_cost" disabled type="text" value=""></td>
                        <td><input placeholder="N/A" class="passenger_cost excursion_payment"  type="text" value=""></td>
                        <td><input  class="passenger_cost excursion_cancel_fee"  type="text" value="0"></td>
                        <td><input  class="passenger_cost excursion_refund"  disabled type="text" value=""></td>
                    </tr>

                </tbody>
            </table>
            <div class="row-fluid">
                <div class="span12">
                    <div class="text-center">
                        <?php echo JText::sprintf('Grand total cost: <span class="total-cost-for-all">%s</span>,Total cancel:<span class="total-cancel">%s</span>, Total refund:<span class="total-refund">%s</span>',0,0,0)?>
                    </div>
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

