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
$i = 0;
?>
<div class="edit-form-general form-vertical">
    <div class="row-fluid ">
        <div class="span12">
            <fieldset class="general">
                <legend><?php echo JText::_('General') ?></legend>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo VmHTML::row_control('input',JText::_('Service name'), 'product_name', $this->item->hotel_name, ' placeholder="service name" '); ?>

                        <?php echo VmHTML::row_control('select_service_class', 'Supplier',array(),'tsmart_service_class_id',$this->item->tsmart_service_class_id,''); ?>
                        <?php echo VmHTML::row_control('select_service_class', 'Customer',array(),'tsmart_service_class_id',$this->item->tsmart_service_class_id,''); ?>
                        <?php echo VmHTML::row_control('select_service_class', 'Booking by',array(),'tsmart_service_class_id',$this->item->tsmart_service_class_id,''); ?>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Start city'), 'product_name', $this->item->hotel_name, ' placeholder="service name" style="width: auto;" '); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('End city'), 'product_name', $this->item->hotel_name, ' placeholder="service name" style="width: auto;" '); ?>

                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Start date'), 'product_name', $this->item->hotel_name, ' placeholder="service name" style="width: auto;" '); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('End Date'), 'product_name', $this->item->hotel_name, ' placeholder="service name" style="width: auto;" '); ?>

                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <?php echo VmHTML::row_control('input',JText::_('Reference'), 'product_name', $this->item->hotel_name, ' placeholder="service name"  style="width: auto;" '); ?>

                            </div>
                            <div class="span6">
                                <?php echo VmHTML::row_control('select_service_class', 'Assign to',array(),'tsmart_service_class_id',$this->item->tsmart_service_class_id,''); ?>

                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <?php echo VmHTML::row_control('input', 'Hotel location','location', $this->hotel->city_area_name, ' placeholder="location" readonly '); ?>
                        <?php echo VmHTML::row_control('textarea', 'Included service','included_service', $this->item->included_service,'',100,4); ?>
                        <?php echo VmHTML::row_control('textarea', 'Included service','included_service', $this->item->included_service,'',100,4); ?>

                    </div>
                </div>


            </fieldset>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

