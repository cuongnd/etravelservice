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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_add_room_hotel_addon.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
// set row counter
?>
<div class="view_orders_edit_form_add_room_hotel_addon form-horizontal">
    <div class="row-fluid ">
        <h3 class="text-uppercase"><span class="pull-right"><?php echo JText::sprintf('service name : %s',$this->tour->product_name) ?></span></h3>
        <div class="buil_rooming_hotel_add_on">
            <?php echo VmHtml::_('build_room_hotel_add_on.build_room',$this->list_passenger_not_in_temporary_and_not_in_room,'rooming_list_hotel_add_on','',$this->departure,$this->passenger_config,$this->debug);?>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="pull-right">
                    <button type="button" class="btn btn-primary save-room"><?php echo JText::_('Save') ?></button>
                    <button type="button" class="btn btn-primary cancel"><?php echo JText::_('Cancel') ?></button>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

