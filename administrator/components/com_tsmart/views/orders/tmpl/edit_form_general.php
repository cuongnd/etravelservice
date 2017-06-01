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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_from_general.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
// set row counter
?>
<div class="edit-form-general form-vertical">
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
                        <?php echo VmHTML::row_control('text_view',JText::_('Service name'), $this->tour->product_name); ?>
                        <?php echo VmHTML::row_control('text_view',JText::_('Supplier'), ''); ?>
                        <?php echo VmHTML::row_control('text_view',JText::_('Customer'), ''); ?>
                        <?php echo VmHTML::row_control('text_view',JText::_('Booker by'), ''); ?>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('text_view',JText::_('Start city'), $this->cities_helper->get_path_city_state_country_by_city_id($this->tour->start_city)->full_city); ?>
                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('text_view',JText::_('End city'),  $this->cities_helper->get_path_city_state_country_by_city_id($this->tour->start_city)->full_city); ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('select_date',JText::_('Start date'), 'departure_date', $this->departure->departure_date); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('select_date',JText::_('End Date'), 'departure_date_end', $this->departure->departure_date_end); ?>

                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Reference'), 'product_name', $this->item->hotel_name, ' placeholder="service name"  style="width: 100%;" '); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('select_user_name', 'Assign to',array(),'assign_user_id',$this->item->assign_user_id,''); ?>

                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('textarea', 'Terms & condition','terms_condition', $this->item->terms_condition,'',100,4); ?>
                        <?php echo VmHTML::row_control('textarea', 'Reservation notes','reservation_notes', $this->item->terms_condition,'',100,4); ?>

                    </div>
                </div>


            </fieldset>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

