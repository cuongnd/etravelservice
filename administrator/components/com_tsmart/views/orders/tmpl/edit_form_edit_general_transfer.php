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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_from_general_edit_transfer.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
// set row counter
?>
<!--edit_form_general_edit_hotel_addon-->
<div class="view_orders_edit_from_general_edit_transfer form-vertical">
    <div class="row-fluid ">
        <div class="span12">
            <fieldset class="general">
                <legend><?php echo JText::_('General') ?></legend>
                <div class="row-fluid ">
                    <div class="span12">
                        <div class="pull-right"><?php echo JText::sprintf('ID: <span class="group_hotel_addon_order">%s</span>','') ?></div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo VmHTML::row_control('text_view',JText::_('Service name'), "",'class="text-view transfer_addon_name "'); ?>
                        <?php echo VmHTML::row_control('text_view',JText::_('Supplier'), ''); ?>
                        <?php echo VmHTML::row_control('text_view',JText::_('Customer'), ''); ?>
                        <?php echo VmHTML::row_control('text_view',JText::_('Booker by'), ''); ?>
                        <div class="row-fluid">
                            <div class="span12">
                                <?php echo VmHTML::row_control('text_view',JText::_('Location'),'', ' class="text-view transfer_location " '); ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('text_view',JText::_('Check in date'), ' ','class="text-view check_in_date "'); ?>
                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('text_view',JText::_('Check out date'), ' ','class="text-view check_out_date "'); ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Reference'), 'product_code', $this->tour->product_code, ' placeholder="service name"  readonly style="width: 100%;" '); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('user.select_user_name', 'Assign to',array(),'read_only_list_assign_user_id_manager_transfer',array(),' disabled '); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('textarea', 'Terms & condition','terms_condition', "",' readonly ',100,4); ?>
                        <?php echo VmHTML::row_control('textarea', 'Reservation notes','reservation_notes', "",' readonly ',100,4); ?>

                    </div>
                </div>


            </fieldset>

        </div>
    </div>
    <div>
        <div class="pull-right"><a href="javascript:void(0)" class="edit-transfer-add-on"><?php echo JText::_('Edit') ?></a></div>
    </div>
</div>
<!-- Product pricing -->


<div class="clear"></div>

