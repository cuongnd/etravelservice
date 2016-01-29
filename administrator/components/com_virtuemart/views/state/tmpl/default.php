<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage State
 * @author RickG, Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8670 2015-01-27 14:10:38Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$input = $app->input;
$show_edit_in_line = $input->get('show_edit_in_line', 0, 'int');
$cid = $input->get('cid', array(), 'array');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'states.ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_virtuemart&controller=state&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'state_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


AdminUIHelper::startAdminArea($this);

?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div><?php echo JHtml::_('link', 'index.php?option=com_virtuemart&view=state&virtuemart_state_id=' . $this->virtuemart_state_id, vmText::sprintf('COM_VIRTUEMART_STATES_state', $this->state_name)); ?></div>
            <table class="adminlist table table-striped" id="state_list" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php  echo $this->sort('states.virtuemart_state_id','Id') ; ?></label>

                    </th>
                    <th>
                        <?php echo $this->sort('states.sate_name', 'COM_VIRTUEMART_STATE_NAME'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_name', 'Country'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('virtuemart_worldzone_id', 'COM_VIRTUEMART_ZONE_ASSIGN_CURRENT_LBL'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('state_2_code', 'COM_VIRTUEMART_STATE_2_CODE'); ?>

                    </th>
                    <th>
                        <?php echo $this->sort('state_3_code', 'COM_VIRTUEMART_STATE_3_CODE'); ?>

                    </th>
                    <th width="70px">
                        <?php echo vmText::_('Action'); ?>
                    </th>
                </tr>
                </thead>
                <?php
                $k = 0;
                $add_new = $show_edit_in_line == 1 && count($cid) == 0;
                if ($add_new) {
                    $item = new stdClass();
                    $item->published = 1;
                    array_unshift($this->items, $item);
                }
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_state_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_virtuemart&view=state&task=edit_in_line&cid[]=' . $row->virtuemart_state_id);
                    $edit = $this->gridEdit($row, $i, 'virtuemart_state_id', $editlink);
                    $save_link = JROUTE::_('index.php?option=com_virtuemart&view=state&task=save_in_line&cid[]=' . $row->virtuemart_cstate_id);
                    $save = $this->grid_save_in_line($row, $i, 'virtuemart_state_id', $save_link);
                    $delete = $this->grid_delete_in_line($row, $i, 'virtuemart_state_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'virtuemart_state_id');
                    $show_edit = ($show_edit_in_line == 1 && in_array($row->virtuemart_state_id, $cid)) || ($show_edit_in_line == 1 && count($cid) == 0 && $i == 0);

                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::inputHidden(array(virtuemart_state_id => $row->virtuemart_state_id)); ?>
                                <?php echo $checked ?>
                            <?php } else { ?>
                                <?php echo $checked ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('state_name', $row->state_name, 'class="required"'); ?>
                            <?php } else { ?>
                                <a href="<?php echo $editlink; ?>"><?php echo $row->state_name; ?></a>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::select('virtuemart_country_id', $this->list_country, $row->virtuemart_country_id, '', 'virtuemart_country_id', 'country_name'); ?>
                            <?php } else { ?>
                                <?php echo $row->country_name; ?>
                            <?php } ?>

                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('virtuemart_worldzone_id', $row->virtuemart_worldzone_id, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->virtuemart_worldzone_id; ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('state_2_code', $row->state_2_code, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->state_2_code; ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('state_3_code', $row->state_3_code, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->state_3_code; ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo $add_new ? '' : $published; ?>
                                <?php echo $save; ?>
                                <?php echo $cancel; ?>
                                <?php echo VmHTML::inputHidden(array(published => $row->published)); ?>

                            <?php } else { ?>
                                <?php echo $published; ?>
                                <?php echo $edit; ?>
                                <?php echo $delete; ?>
                            <?php } ?>

                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
                <tfoot>
                <tr>
                    <td colspan="10">
                        <?php echo $this->pagination->getListFooter(); ?>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <?php echo $this->addStandardHiddenToForm(); ?>
    </form>


<?php AdminUIHelper::endAdminArea(); ?>