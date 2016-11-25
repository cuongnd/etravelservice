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
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_transferaddon_default.js');
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
        $('.view-transferaddon-default').view_transferaddon_default({
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
<div class="view-transferaddon-default">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div class="vm-page-nav">
                <?php echo AdminUIHelper::render_pagination($this->pagination) ?>
            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_transfer_addon_id', 'Id'); ?>
                        </label>

                    </th>
                    <th>
                        <?php echo $this->sort('title', 'Transfer name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('created_on', 'Create date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('location', 'Location'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Price'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Valid period') ?>
                    </th>
                    <th>
                        <?php echo $this->sort('modified_on', 'Amend date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Description'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Application') ?>
                    </th>
                    <th>
                        <?php echo JText::_('Add on type') ?>
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

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_transfer_addon_id);
                    $published = $this->gridPublished($row, $i);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_transfer_addon_id');
                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=transferaddon&task=edit_item&cid[]=' . $row->tsmart_transfer_addon_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_transfer_addon_id', $editlink);
                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->transfer_addon_name; ?></a>
                        </td>
                        <td align="left">
                            <?php echo JHtml::_('date', $row->created_on, $format_date); ?>
                        </td>
                        <td align="left">
                            <?php echo $row->city_area_name; ?>
                        </td>
                        <td align="left">
                            <span class="icon-eye"></span>
                        </td>
                        <td align="left">
                            <?php echo JHtml::_('date', $row->vail_from, $format_date); ?>
                            <br/>
                            <?php echo JHtml::_('date', $row->vail_to, $format_date); ?>
                        </td>
                        <td align="left">
                            <?php echo JHtml::_('date', $row->modified_on, $format_date); ?>
                        </td>
                        <td align="left">
                            <?php echo $row->transfer_note; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->list_tour; ?>
                        </td>
                        <td align="left">
                            <?php echo JText::_($row->transfer_type) ; ?>
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
        <input type="hidden" value="transferaddon" name="controller">
        <input type="hidden" value="transferaddon" name="view">
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <?php

    if ($task == 'add_new_item'||$task == 'edit_item') {
        echo $this->loadTemplate('edit');
    } ?>
</div>
    <?php AdminUIHelper::endAdminArea(); ?>

