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
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_orders_edit_form_show_last_history_rooming.less');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// set row counter
$i = 0;
?>
<div class="view_orders_edit_form_show_last_history_rooming form-horizontal">
    <div class="row-fluid ">
        <div class="span12">
            <table class="adminlist table table-striped last_history_rooming" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th >
                            <?php echo $this->sort('tsmart_room_order_id', 'Id'); ?>
                        </th>
                        <th width="20%">
                            <?php echo $this->sort('customer_name', 'Passenger'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('title', 'title'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('room_type', 'Room type'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('room_note', 'Room note'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('creation', 'Creation'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="room_order_item" data-tsmart_room_order_id="">
                        <td><span class="tsmart_room_order_id"></span></td>
                        <td><span class="names"></span></td>
                        <td><span class="titles"></span></td>
                        <td><span class="room_type"></span></td>
                        <td><span class="room_note"></span></td>
                        <td><span class="creation"></span></td>
                    </tr>
                </tbody>

            </table>

            <div class="row-fluid">
                <div class="span12">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary cancel"><?php echo JText::_('Close') ?></button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- Product pricing -->


<div class="clear"></div>

