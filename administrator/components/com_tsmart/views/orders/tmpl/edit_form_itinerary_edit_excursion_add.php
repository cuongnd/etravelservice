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
//$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_order_booking_information_itinerary.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="booking-information view_orders_edit_order_booking_information_itinerary form-vertical">
    <div class="row-fluid ">
        <div class="span12">
            <?php echo VmHTML::row_control('editor', JText::_('itinerary'), 'excursion_itinerary_readonly', '', '100% ',  20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="pull-right">
                <a class="edit-general-excursion-add-on" href="javascript:void(0)"><?php echo JText::_('Edit') ?></a>
            </div>
        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

