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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/edit_form_general_popup_main_tour.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
// set row counter
?>
<div class="edit-form-general-popup-main-tour edit_form_general_popup_main_tour form-vertical">
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
                        <?php echo VmHTML::row_control('supplier.select_supplier', 'Supplier',array(),'main_tour_tsmart_supplier_id','',''); ?>
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
                                <?php echo VmHTML::row_control('select_date',JText::_('Check in date'), 'departure_date', ''); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('select_date',JText::_('Check out date'), 'departure_date_end',''); ?>

                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Reference'), 'main_product_code', $this->tour->product_code, ' placeholder="service name"   style="width: 100%;" '); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('user.select_user_name', 'Assign to',array(),'assign_user_id_manager_main_tour',array(),'  '); ?>
                            </div>

                        </div>
                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('textarea', 'Terms & condition','main_tour_terms_condition', $this->item->terms_condition,' ',100,4); ?>
                        <?php echo VmHTML::row_control('textarea', 'Reservation notes','reservation_notes', $this->item->terms_condition,'',100,4); ?>

                    </div>
                </div>

            </fieldset>
            <div class="row-fluid ">
                <div class="span12">
                    <?php echo VmHTML::row_control('editor', JText::_('itinerary'), 'itinerary', $this->item->itinerary, '100% ',  20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                </div>
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
<!-- Product pricing -->


<div class="clear"></div>

