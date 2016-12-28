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
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_discount_default.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_discount_default.less');
$input = JFactory::getApplication()->input;
AdminUIHelper::startAdminArea($this);
$js_content = '';
$task = $input->get('task');
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-discount-default').view_discount_default({
            task: "<?php echo $task ?>"
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
$option = array(
    'tsmart_product_id' => '',
    'product_name' => 'Please select tour'
);
array_unshift($this->list_tour, $option);
?>
<div class="view-discount-default">
    <form action="index.php" class="form-vertical" method="post" name="adminForm" id="adminForm">
        <div class="row-fluid filter">
            <div class="control-group btn_search"><?php echo VmHTML::input_button('', 'Reset'); ?></div>
            <?php echo VmHTML::row_control('input', 'discount name ', 'filter_search', $this->escape($this->state->get('filter.search'))); ?>
            <?php echo VmHTML::row_control('input', 'discount code ', 'filter_discount_code', $this->escape($this->state->get('filter.discount_code'))); ?>
            <?php echo VmHTML::row_control('range_of_date', 'discount creation', 'filter_creation_from', 'filter_creation_to', $this->state->get('filter.creation_from'), $this->state->get('filter.creation_to')); ?>
            <?php echo VmHTML::row_control('select_state', 'Status', 'filter_state', $this->state->get('filter.state'), ''); ?>
            <div class="control-group btn_search"><?php echo VmHTML::input_button('', 'Search'); ?></div>
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
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_discount_id', 'Id'); ?>
                        </label>
                    </th>
                    <th>
                        <?php echo $this->sort('discount_name', 'discount name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('created_on', 'Create date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('discount_code', 'discount code'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('discount_value', 'Value'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Validity') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Time use') ?>
                    </th>
                    <th>
                        <?php echo JText::_('remain') ?>
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
                if (count($this->items)) {
                    $k = 0;
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        $checked = JHtml::_('grid.id', $i, $row->tsmart_discount_id);
                        $published = $this->gridPublished($row, $i);
                        $delete = $this->grid_delete_in_line($row, $i, 'tsmart_discount_id');
                        $editlink = JROUTE::_('index.php?option=com_tsmart&view=discount&task=edit_item&cid[]=' . $row->tsmart_discount_id);
                        $edit = $this->gridEdit($row, $i, 'tsmart_discount_id', $editlink);
                        ?>
                        <tr class="row<?php echo $k; ?>">
                            <td class="admin-checkbox">
                                <?php echo $checked; ?>
                            </td>
                            <td align="left">
                                <a href="<?php echo $editlink; ?>"><?php echo $row->discount_name; ?></a>
                            </td>
                            <td align="left">
                                <?php echo JHtml::_('date', $row->created_on, tsmConfig::$date_format); ?>
                            </td>
                            <td align="left">
                                <a href="/administrator/index.php?option=com_tsmart&view=discount&task=edit_item&cid[]=<?php echo $row->tsmart_discount_id; ?>"><?php echo $row->discount_code; ?></a>
                            </td>
                            <td align="left">
                                <?php echo $row->discount_value; ?><?php echo $row->percent_or_total == 'percent' ? '%' : ' $US'; ?>
                            </td>
                            <td align="left">
                                <?php echo JHtml::_('date', $row->discount_start_date, tsmConfig::$date_format); ?>
                                <br>
                                <?php echo JHtml::_('date', $row->discount_expiry_date, tsmConfig::$date_format); ?>
                            </td>
                            <td align="left">
                                <?php echo $row->discount_used; ?>
                            </td>
                            <td align="left">
                                <?php echo $row->remain; ?>
                            </td>
                            <td align="left">
                                <?php echo $row->product_name; ?>
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
        <input type="hidden" value="discount" name="controller">
        <input type="hidden" value="discount" name="view">
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order']; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['filter_order_Dir']; ?>"/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <?php
    if ($task == 'add_new_item' || $task == 'edit_item') {
        echo $this->loadTemplate('edit');
    } ?>
</div>
<?php AdminUIHelper::endAdminArea(); ?>

