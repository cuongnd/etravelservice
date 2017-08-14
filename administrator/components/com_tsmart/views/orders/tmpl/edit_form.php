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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_order_edit_main_tour.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;

?>
<div class="view_orders_edit_order_edit_main_tour">

    <?php echo $this->loadTemplate('form_general') ?>
    <?php echo $this->loadTemplate('form_booking_summary') ?>
    <?php echo $this->loadTemplate('form_service_cost') ?>
    <?php echo $this->loadTemplate('form_condition') ?>
    <?php echo $this->loadTemplate('form_passenger') ?>
    <?php echo $this->loadTemplate('form_rooming') ?>
    <?php echo $this->loadTemplate('form_itinerary') ?>
    <input type="hidden" name="tsmart_order_main_tour_id" value="<?php echo $this->main_tour_order->tsmart_order_main_tour_id ?>">
    <div class="row-fluid">
        <div class="span12">
            <div class="pull-right">
                <button type="button" class="btn btn-primary cancel"><?php echo JText::_('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Product pricing -->


<div class="clear"></div>

