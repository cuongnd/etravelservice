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
$doc = JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/less/view_orders_edit_order_edit_main_tour.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;

?>
<div class="edit_form_book_add_on">
    <div class="tabbed-nav book-add-on">
        <!-- Tab Navigation Menu -->
        <ul>
            <li><a><?php echo JText::_('Fre night') ?></a></li>
            <li><a><?php echo JText::_('Post night') ?></a></li>
            <li><a><?php echo JText::_('Fre transfer') ?></a></li>
            <li><a><?php echo JText::_('Post transfer') ?></a></li>
            <li><a><?php echo JText::_('Excursion') ?></a></li>
        </ul>
        <!-- Content container -->
        <div>
            <!-- Overview -->
            <div>
                <div class="view_orders_edit_order_edit_main_tour">
                    <?php echo $this->loadTemplate('form_general_add_hotel_night') ?>
                    <?php echo $this->loadTemplate('form_service_cost_add_hotel_night') ?>
                    <?php echo $this->loadTemplate('form_condition_add_hotel_night') ?>
                    <?php echo $this->loadTemplate('form_edit_room_add_hotel_night') ?>
                    <?php echo $this->loadTemplate('form_rooming_add_hotel_night') ?>
                </div>
                <!-- Product pricing -->


                <div class="clear"></div>
            </div>
            <!-- Features -->
            <div>
                <div class="view_orders_edit_order_edit_main_tour">
                    <?php echo $this->loadTemplate('form_general_add_hotel_night') ?>
                    <?php echo $this->loadTemplate('form_service_cost_add_hotel_night') ?>
                    <?php echo $this->loadTemplate('form_condition_add_hotel_night') ?>
                    <?php echo $this->loadTemplate('form_edit_room_add_hotel_night') ?>
                    <?php echo $this->loadTemplate('form_rooming_add_hotel_night') ?>
                </div>
                <!-- Product pricing -->


                <div class="clear"></div>
            </div>
            <!-- Docs -->
            <div>
                <div class="view_orders_edit_order_edit_main_tour">

                    <?php echo $this->loadTemplate('form_general_add_transfer') ?>
                    <?php echo $this->loadTemplate('form_service_cost_add_transfer') ?>
                    <?php echo $this->loadTemplate('form_condition_add_transfer') ?>
                    <?php echo $this->loadTemplate('form_edit_room_add_transfer') ?>
                    <?php echo $this->loadTemplate('form_rooming_add_transfer') ?>
                </div>
                <!-- Product pricing -->


                <div class="clear"></div>
            </div>
            <!-- Themes -->
            <div>
                <div class="view_orders_edit_order_edit_main_tour">
                    <?php echo $this->loadTemplate('form_general_add_transfer') ?>
                    <?php echo $this->loadTemplate('form_service_cost_add_transfer') ?>
                    <?php echo $this->loadTemplate('form_condition_add_transfer') ?>
                    <?php echo $this->loadTemplate('form_edit_room_add_transfer') ?>
                    <?php echo $this->loadTemplate('form_rooming_add_transfer') ?>
                </div>
                <!-- Product pricing -->


                <div class="clear"></div>
            </div>
            <!-- Purchase -->
            <div>
                <div class="view_orders_edit_order_edit_main_tour">
                    <?php echo $this->loadTemplate('form_general_add_excursion') ?>
                    <?php echo $this->loadTemplate('form_service_cost_add_excursion') ?>
                    <?php echo $this->loadTemplate('form_condition_add_excursion') ?>
                    <?php echo $this->loadTemplate('form_edit_passenger_excursion_cost') ?>
                    <?php echo $this->loadTemplate('form_excursion_itinerary') ?>
                </div>
                <!-- Product pricing -->


                <div class="clear"></div>
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


