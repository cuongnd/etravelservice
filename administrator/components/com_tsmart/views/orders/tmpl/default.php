<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Currency
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
JHtml::_('jquery.framework');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');
TSMHtmlJquery::ui();
TSMHtmlJquery::notify();
$doc->addScript(JUri::root() . '/media/system/js/multi_calendar_date_picker/js/multi_calendar_date_picker.js');
$doc->addScript(JUri::root() . '/media/system/js/ion.rangeSlider-master/js/ion.rangeSlider.js');
$doc->addScript(JUri::root() . '/media/system/js/jQuery-Plugin-For-Bootstrap-Button-Group-Toggles/select-toggleizer.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');
$doc->addScript(JUri::root() . 'administrator/components/com_tsmart/assets/js/view_orders_default.js');
$doc->addLessStyleSheet(JUri::root() . 'administrator/components/com_tsmart/assets/less/view_orders_default.less');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$input = JFactory::getApplication()->input;
AdminUIHelper::startAdminArea($this);
$js_content = '';
$task = $input->get('task');
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-orders-default').view_orders_default({
            task: "<?php echo $task ?>"
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
$format_date=tsmConfig::$date_format;// 'd-m-Y';

?>
<div class="view-orders-default">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div class="row-fluid filter filter-orders">
            <div class="row-fluid ">
                <div class="span1">
                    <div class="padding5">
                        <div class="control-group btn_search"><?php echo VmHTML::input_button('', 'Reset'); ?></div>
                    </div>
                </div>
                <div class="span10">
                    <div class="border-left border-right">
                        <div class="border-bottom padding5">
                            <div class="row-fluid">
                                <div class="span12">
                                    <?php echo VmHTML::row_control('input', 'Booking ID/Guest name', 'filter_search', $this->escape($this->state->get('filter.search'))); ?>
                                    <?php echo VmHTML::row_control('input', 'trip name', 'filter_trip_code', $this->escape($this->state->get('filter.trip_code'))); ?>
                                    <?php echo VmHTML::row_control('select_tour_type', 'trip type', 'filter_trip_type_id', $this->state->get('filter.trip_type_id')); ?>
                                    <?php echo VmHTML::row_control('select_tour_style', 'trip style', 'filter_trip_style_id', $this->state->get('filter.trip_style_id')); ?>
                                    <?php //echo VmHTML::row_control('active', 'active', 'filter_active', $this->state->get('filter.active')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">

                                <?php echo VmHTML::row_control('input', 'trip name', 'filter_search', $this->escape($this->state->get('filter.search'))); ?>
                                <?php echo VmHTML::row_control('input', 'trip code', 'filter_trip_code', $this->escape($this->state->get('filter.trip_code'))); ?>
                                <?php echo VmHTML::row_control('select_tour_type', 'trip type', 'filter_trip_type_id', $this->state->get('filter.trip_type_id')); ?>
                                <?php echo VmHTML::row_control('select_tour_style', 'trip style', 'filter_trip_style_id', $this->state->get('filter.trip_style_id')); ?>
                                <?php //echo VmHTML::row_control('active', 'active', 'filter_active', $this->state->get('filter.active')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span1">
                    <div class="padding5">
                        <div class="control-group btn_search"><?php echo VmHTML::input_button('', 'Search'); ?></div>
                    </div>
                </div>
            </div>



        </div>


        <div id="editcell">
            <div class="vm-page-nav">
                <?php echo AdminUIHelper::render_pagination($this->pagination) ?>
            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_order_id', 'Id'); ?>
                        </label>

                    </th>
                    <th>
                        <?php echo $this->sort('customer_name', 'Customer name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('created_on', 'Tour name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('location', 'Type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('creation', 'Creation'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Tour date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('modified_on', 'Start/End city'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Client'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Value') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Receipt') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Balance') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Assign') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Status') ?>
                    </th>
                    <th width="70">
                        <?php echo tsmText::_('Action'); ?>
                    </th>
                    <?php /*	<th width="10">
				<?php echo vmText::_('com_tsmart_SHARED'); ?>
			</th> */ ?>
                </tr>
                </thead>
                <?php
                $k = 0;
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_order_id);
                    $published = $this->gridPublished($row, $i);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_order_id');
                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=orders&task=edit_item&cid[]=' . $row->tsmart_order_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_order_id', $editlink);
                    ?>
                    <tr class="row<?php echo $k; ?>" data-tsmart_order_id="<?php echo $row->tsmart_order_id?>">
                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->custom_name; ?></a>
                        </td>
                        <td align="left">
                            <?php echo $row->product->product_name; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->product_type; ?>
                        </td>
                        <td align="left">
                            <?php echo JHtml::_('date', $row->created_on, tsmConfig::$date_format); ?>
                        </td>
                        <td align="left">
                            <?php echo JHtml::_('date', $row->departure_date, tsmConfig::$date_format); ?>
                        </td>
                        <td align="left">
                            <?php echo $row->product->start_city; ?>,<?php echo $row->product->start_country; ?>
                            <br/>
                            <?php echo $row->product->end_city; ?>,<?php echo $row->product->end_country; ?>
                        </td>
                        <td align="left">
                            <?php echo JText::sprintf('%s pers',$row->total_passenger) ?>

                        </td>
                        <td align="left">
                            <span class="cost"><?php echo $row->order_total; ?></span>
                        </td>
                        <td align="left">
                             <span class="cost"><?php echo $row->receipt; ?></span>
                        </td>
                        <td align="left">
                             <span class="cost"><?php echo $row->order_total-$row->receipt; ?></span>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::select_user_name(array(), 'assign_user_id_'.$row->tsmart_order_id, $row->assign_user_id, 'class="assign_user_id "'); ?>

                        </td>
                        <td align="left">
                            <?php echo JText::_($row->order_status_name) ; ?>
                        </td>
                        <td align="center">
                            <?php echo $published; ?>
                            <?php echo $edit; ?>
                            <?php echo $delete; ?>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
            </table>
        </div>
        <input type="hidden" value="" name="task">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="orders" name="controller">
        <input type="hidden" value="orders" name="view">
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <?php

    if ($task == 'add_new_item'||$task == 'edit_item') {
        echo $this->loadTemplate('edit');
    } ?>
</div>
    <?php AdminUIHelper::endAdminArea(); ?>

