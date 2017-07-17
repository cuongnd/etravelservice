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
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
// set row counter
?>

<div class="view_orders_edit_from_general_edit_hotel_addon_child form-vertical">
    <div class="row-fluid ">
        <div class="span12">
            <fieldset class="general">
                <legend><?php echo JText::_('General') ?></legend>
                <div class="row-fluid ">
                    <div class="span12">
                        <div class="pull-right"><?php echo JText::sprintf('ID: %s',$this->item->order_number) ?></div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo VmHTML::row_control('text_view',JText::_('Service name'), "",'class="text-view hotel_name "'); ?>
                        <?php echo VmHTML::row_control('supplier.select_supplier', 'Supplier',array(),'tsmart_Supplier_id','',''); ?>
                        <?php echo VmHTML::row_control('text_view',JText::_('Customer'), ''); ?>
                        <?php echo VmHTML::row_control('text_view',JText::_('Booker by'), ''); ?>
                        <div class="row-fluid">
                            <div class="span12">
                                <?php echo VmHTML::row_control('text_view',JText::_('Location'), '', ' class="text-view hotel_location " '); ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('select_date',JText::_('Check in date'), 'night_hotel_checkin_date', ''); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('select_date',JText::_('Check out date'), 'night_hotel_checkout_date',''); ?>

                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Reference'), 'product_code', $this->tour->product_code, ' placeholder="service name" readonly  style="width: 100%;" '); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('user.select_user_name', 'Assign to',array(),'list_assign_user_id_manager_hotel_add_on',array(),''); ?>

                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('textarea', 'Terms & condition','terms_condition', "",'',100,4); ?>
                        <?php echo VmHTML::row_control('textarea', 'Reservation notes','reservation_notes', "",'',100,4); ?>

                    </div>
                </div>


            </fieldset>

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
<!-- Product pricing -->


<div class="clear"></div>

