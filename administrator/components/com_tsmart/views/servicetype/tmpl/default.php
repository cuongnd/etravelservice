<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage servicetype
 * @author RickG
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$input = $app->input;
$show_edit_in_line = $input->get('show_edit_in_line', 0, 'int');
$cid = $input->get('cid', array(), 'array');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn  = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder)
{

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=servicetype&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'servicetype_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


AdminUIHelper::startAdminArea($this);
$states = tsmText::_('com_tsmart_STATE_S');
?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="header">
            <div id="filterbox">
                <table>
                    <tr>
                        <td align="left" width="100%">
                            <?php echo tsmText::_('com_tsmart_FILTER') ?>:
                            &nbsp;<input type="text" value="<?php echo vRequest::getVar('filter_servicetype'); ?>"
                                         name="filter_servicetype" size="25"/>
                            <button class="btn btn-small"
                                    onclick="this.form.submit();"><?php echo tsmText::_('com_tsmart_GO'); ?></button>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="resultscounter"><?php echo $this->pagination->getResultsCounter(); ?></div>
        </div>
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-striped" id="servicetype_list" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/>ID</label>

                    </th>
                    <th>
                        <?php echo $this->sort('servicetype_name') ?>
                    </th>
                    <th>
                        <?php echo $this->sort('Created date', 'created_on'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('Modified date', 'modified_on'); ?>
                    </th>
                    <th width="20">
                        <?php echo tsmText::_('Action'); ?>
                    </th>
                    <th width="1%" class="nowrap center hidden-phone">
                        <?php echo $this->sort('ordering', 'ordering'); ?>
                        <?php if ($saveOrder) : ?>
                            <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); ?>
                        <?php endif; ?>
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

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_service_type_id);
                    $published = $this->gridPublished($row, $i);
                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=servicetype&task=edit_in_line&cid[]=' . $row->virtuemart_service_type_id);
                    $edit = $this->gridEdit($row, $i, 'virtuemart_service_type_id', $editlink);
                    $save_link = JROUTE::_('index.php?option=com_tsmart&view=servicetype&task=save_in_line&cid[]=' . $row->virtuemart_service_type_id);
                    $save = $this->grid_save_in_line($row, $i, 'virtuemart_service_type_id', $save_link);
                    $delete = $this->grid_delete_in_line($row, $i, 'virtuemart_service_type_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'virtuemart_service_type_id');
                    $show_edit = ($show_edit_in_line == 1 && in_array($row->virtuemart_service_type_id, $cid)) || ($show_edit_in_line == 1 && count($cid) == 0 && $i == 0);


                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::inputHidden(array(virtuemart_service_type_id => $row->virtuemart_service_type_id)); ?>
                                <?php echo $checked ?>
                            <?php } else { ?>
                                <?php echo $checked ?>
                            <?php } ?>

                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('servicetype_name', $row->title, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php
                                $servicetype_string = tsmText::_($prefix . $row->servicetype_3_code); ?>
                                <a href="<?php echo $editlink; ?>"><?php echo $row->title ?> </a>
                            <?php } ?>




                        </td>

                        <td align="left">
                            <?php echo $row->created_on; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->modified_on; ?>
                        </td>
                        <td align="center" width="70">
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
                        <td>
                            <span class="sortable-handler">
								<span class="icon-menu"></span>
							</span>
                            <?php if ($saveOrder) : ?>
                                <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order " />
                            <?php endif; ?>
                            <?php //echo $row->ordering ?>

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

        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['filter_order_Dir']; ?>"/>
        <input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order']; ?>"/>
        <input type="hidden" name="option" value="com_tsmart"/>
        <input type="hidden" name="controller" value="servicetype"/>
        <input type="hidden" name="view" value="servicetype"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <?php echo JHtml::_('form.token'); ?>
    </form>


<?php AdminUIHelper::endAdminArea(); ?>