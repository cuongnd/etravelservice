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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_hotel_add_on_summary.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_hotel_add_on_summary form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <div class="vm-page-nav text-center ">
                <h3 class="text-uppercase"><?php echo JText::_('Booking summary') ?></h3>
            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th>
                        <?php echo JText::_('Gross total'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Discount'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Commission'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Net total'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Payment'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Balance'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Cancel'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Refund'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Status') ?>
                    </th>
                </tr>
                </thead>
                <tr>

                    <td><span class="gross_total"></span></td>
                    <td><span class="group_hotel_current_discount"></span></td>
                    <td><span class="group_hotel_current_commission"></span></td>
                    <td><span class="net_total"></span></td>
                    <td><input type="text" class="group_hotel_current_payment"></td>
                    <td><span class="balance"></span></td>
                    <td><span class="cancel"></span></td>
                    <td><span class="refund"></span></td>
                    <td>
                        <?php echo VmHTML::change_order_status(array(), 'hotel_add_on_status', 0, ''); ?>
                    </td>

                </tr>
            </table>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

