<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG
 * @link http://www.virtuemart.net
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
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=airport&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'city_area_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


AdminUIHelper::startAdminArea($this);

?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table>
            <tr>
                <td width="100%">
                    <?php echo $this->displayDefaultViewSearch('airport', 'search'); ?>
                </td>
            </tr>
        </table>
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-striped" id="city_area_list" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('virtuemart_transfer_addon_id', 'Id'); ?>
                        </label>
                    </th>
                    <th>
                       <!-- <?php /*echo $this->sort('ordering', 'ordering'); */?>
                        <?php /*if ($saveOrder) : */?>
                            <?php /*echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); */?>
                        --><?php /*endif; */?>
                        <?php echo $this->sort('airport_name', 'Airport name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('ata_code', 'ATA code'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('icao_code', 'ICAO code'); ?>
                    </th>
                    <?php if (!$show_edit_in_line) { ?>
                        <th>
                            <?php echo $this->sort('state_name', 'City name'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('state_name', 'State name'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('country_name', 'Country name'); ?>
                        </th>
                    <?php } else { ?>
                        <th>
                            <?php echo $this->sort('country_name', 'Country name'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('state_name', 'State name'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('state_name', 'City name'); ?>
                        </th>
                    <?php } ?>

                    <th width="70">
                        <?php echo vmText::_('Action'); ?>
                    </th>
                    <?php /*	<th width="10">
				<?php echo vmText::_('com_tsmart_SHARED'); ?>
			</th> */ ?>
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

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_airport_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=airport&task=edit_in_line&cid[]=' . $row->virtuemart_airport_id);
                    $edit = $this->gridEdit($row, $i, 'virtuemart_airport_id', $editlink);
                    $save_link = JROUTE::_('index.php?option=com_tsmart&view=airport&task=save_in_line&cid[]=' . $row->virtuemart_airport_id);
                    $save = $this->grid_save_in_line($row, $i, 'virtuemart_airport_id', $save_link);
                    $delete = $this->grid_delete_in_line($row, $i, 'virtuemart_airport_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'virtuemart_airport_id');
                    $show_edit = ($show_edit_in_line == 1 && in_array($row->virtuemart_airport_id, $cid)) || ($show_edit_in_line == 1 && count($cid) == 0 && $i == 0);

                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::inputHidden(array(virtuemart_airport_id => $row->virtuemart_airport_id)); ?>
                                <?php echo $checked ?>
                            <?php } else { ?>
                                <?php echo $checked ?>
                            <?php } ?>


                        </td>
                        <td align="left">
                           <!-- <?php /*if ($saveOrder) : */?>
                                <span class="sortable-handler">
								<span class="icon-menu"></span>
							</span>
                                <input type="text" style="display:none" name="order[]" size="5"
                                       value="<?php /*echo $row->ordering; */?>" class="width-20 text-area-order "/>
                            --><?php /*endif; */?>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('airport_name', $row->airport_name, 'class="required"'); ?>
                            <?php } else { ?>
                                <a href="<?php echo $editlink; ?>"><?php echo $row->airport_name; ?></a>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('ata_code', $row->ata_code, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->ata_code ?>
                            <?php } ?>

                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('icao_code', $row->icao_code, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->icao_code ?>
                            <?php } ?>

                        </td>
                        <?php if (!$show_edit_in_line) { ?>
                            <td align="left">
                                <?php echo $row->city_area_name ?>
                            </td>
                            <td align="left">
                                <?php echo $row->state_name ?>
                            </td>
                            <td align="left">
                                <?php echo VmHTML::show_image(JUri::root().'/'.$row->country_flag, 'class="required"',20,20); ?>
                                <?php echo $row->country_name ?>
                            </td>
                        <?php } else { ?>
                            <td align="left">
                                <?php if ($show_edit) { ?>
                                    <?php echo VmHTML::show_image(JUri::root().'/'.$row->country_flag, 'class="required"',20,20); ?>
                                    <?php echo VmHTML::select('virtuemart_country_id', $this->list_country,$this->item->virtuemart_country_id,'', 'virtuemart_country_id','country_name'); ?>
                                <?php } else { ?>
                                    <?php echo VmHTML::show_image(JUri::root().'/'.$row->country_flag, 'class="required"',20,20); ?>
                                    <?php echo $row->country_name ?>
                                <?php } ?>

                            </td>
                            <td align="left">
                                <?php if ($show_edit) { ?>
                                    <?php echo VmHTML::select_state_province('virtuemart_state_id', $this->list_state_province,$this->item->virtuemart_state_id,'', 'virtuemart_state_id','state_name','select[name="virtuemart_country_id"]'); ?>
                                <?php } else { ?>
                                    <?php echo $row->state_name ?>
                                <?php } ?>
                            </td>
                            <td align="left">
                                <?php if ($show_edit) { ?>
                                    <?php echo VmHTML::select_city('virtuemart_cityarea_id', $this->list_city_area,$this->item->virtuemart_cityarea_id,'', 'virtuemart_cityarea_id','city_area_name','select[name="virtuemart_state_id"]'); ?>
                                <?php } else { ?>
                                    <?php echo $row->city_area_name ?>
                                <?php } ?>
                            </td>
                        <?php } ?>

                        <td align="center">
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
        <?php echo JHtml::_('form.token'); ?>
    </form>


<?php AdminUIHelper::endAdminArea(); ?>