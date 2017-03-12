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
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_hoteladdon_default.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_hoteladdon_default.less');
$input = JFactory::getApplication()->input;
AdminUIHelper::startAdminArea($this);
$js_content = '';
$task = $input->get('task');
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-hoteladdon-default').view_hoteladdon_default({
            task: "<?php echo $task ?>"
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
$option=array(
    'tsmart_product_id'=>'',
    'product_name'=>'Please select tour'
);
array_unshift($this->list_tour,$option);
?>
<div class="view-hoteladdon-default">

    <form action="index.php" class="form-vertical" method="post" name="adminForm" id="adminForm">
        <div class="row-fluid filter">
            <div class="control-group btn_search"><?php echo VmHTML::input_button('','Reset'); ?></div>
            <?php echo VmHTML::row_control('input', 'Hotel name or rule code','filter_search',$this->escape($this->state->get('filter.search'))); ?>
            <?php echo VmHTML::row_control('location_city', 'Location', 'filter_location_city', $this->state->get('filter.location_city'), 'tsmart_cityarea_id', 'full_city'); ?>
            <?php echo VmHTML::row_control('select', 'Tour name', 'filter_tsmart_product_id', $this->list_tour, $this->state->get('filter.tsmart_product_id'),'', 'tsmart_product_id', 'product_name', false); ?>
            <?php echo VmHTML::row_control('range_of_date','Valid date (Date to Date)', 'filter_vail_from', 'filter_vail_to', $this->state->get('filter.vail_from'),$this->state->get('filter.vail_to')); ?>
            <?php echo VmHTML::row_control('select_state', 'Status', 'filter_state',$this->state->get('filter.state'),''); ?>
            <div class="control-group btn_search"><?php echo VmHTML::input_button('','Search'); ?></div>

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
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_hotel_addon_id', 'Id'); ?>
                        </label>

                    </th>
                    <th>
                        <?php echo $this->sort('hotel_name', 'hotel name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('hotel_addon_type', 'hotel addon type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('created_on', 'Create date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('tsmart_cityarea_id', 'Location'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Price'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Valid period') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Amend date') ?>
                    </th>
                    <th>
                        <?php echo $this->sort('hotel_payment_type', 'hotel payment type'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Application') ?>
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


                if(count($this->items)) {
                    $k = 0;
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];

                        $checked = JHtml::_('grid.id', $i, $row->tsmart_hotel_addon_id);
                        $published = $this->gridPublished($row, $i);
                        $delete = $this->grid_delete_in_line($row, $i, 'tsmart_hotel_addon_id');
                        $editlink = JROUTE::_('index.php?option=com_tsmart&view=hoteladdon&task=edit_item&cid[]=' . $row->tsmart_hotel_addon_id);
                        $edit = $this->gridEdit($row, $i, 'tsmart_hotel_addon_id', $editlink);
                        ?>
                        <tr class="row<?php echo $k; ?>">
                            <td class="admin-checkbox">
                                <?php echo $checked; ?>
                            </td>
                            <td align="left">
                                <a href="<?php echo $editlink; ?>"><?php echo $row->hotel_name; ?></a>
                            </td>
                            <td align="left">
                                <?php echo JText::_($row->hotel_addon_type); ?>
                            </td>
                            <td align="left">
                                <?php echo JHtml::_('date', $row->created_on, tsmConfig::$date_format); ?>
                            </td>
                            <td align="left">
                                <a href="/administrator/index.php?option=com_tsmart&view=cityarea&task=edit_item&cid[]=<?php echo $row->tsmart_cityarea_id; ?>"><?php echo $row->city_area_name; ?></a>
                            </td>
                            <td align="left">
                                <a href="javascript:void(0)"><span title="" class="icon-eye"></span></a>
                            </td>
                            <td align="left">
                                <?php echo JHtml::_('date', $row->vail_from, tsmConfig::$date_format); ?>
                                <br>
                                <?php echo JHtml::_('date', $row->vail_to,tsmConfig::$date_format); ?>
                            </td>
                            <td align="left">
                                <?php echo JHtml::_('date', $row->modified_on ,tsmConfig::$date_format); ?>
                            </td>
                            <td align="left">
                                <?php echo JText::_($row->hotel_payment_type); ?>
                            </td>
                            <td align="left">
                                <?php echo $row->list_tour; ?>
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
                }
                ?>
            </table>
        </div>
        <input type="hidden" value="" name="task">
        <input type="hidden" value="com_tsmart" name="option">
        <input type="hidden" value="hoteladdon" name="controller">
        <input type="hidden" value="hoteladdon" name="view">
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order']; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['filter_order_Dir']; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <?php

    if ($task == 'add_new_item'||$task == 'edit_item') {
        echo $this->loadTemplate('edit');
    } ?>
</div>
    <?php AdminUIHelper::endAdminArea(); ?>

