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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_booking_summary.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="edit_form_booking_summary form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Booking summary') ?></h3>
            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th>
                        <?php echo $this->sort('sale_price', 'Sale price'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('net_price', 'Net price'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('discount', 'Discount'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('commission', 'Commission'); ?>
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
                    <th>
                        <?php echo $this->sort('profit', 'Profit'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Status') ?>
                    </th>
                </tr>
                </thead>
                <tr>

                    <td><?php echo $this->item->order_total ?></td>
                    <td><?php echo $this->item->total_price ?></td>
                    <td><?php echo $this->item->total_price ?></td>
                    <td><?php echo $this->item->total_price ?></td>
                    <td><?php echo $this->item->total_price ?></td>
                    <td><?php echo $this->item->total_price ?></td>
                    <td><?php echo $this->item->total_price ?></td>
                    <td><?php echo $this->item->total_price ?></td>
                    <td><?php echo $this->item->total_price ?></td>
                    <td>
                        <?php echo VmHTML::change_order_status(array(), 'tsmart_orderstate_id', $this->item->tsmart_orderstate_id, 'class="change_order_status "'); ?>
                    </td>

                </tr>
            </table>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

