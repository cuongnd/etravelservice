<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage State
 * @author RickG, Max Milbers
 * @link http://www.tsmart.net
 * @copyright Copyright (c) 2004 - 2010 tsmart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * tsmart is free software. This version may have been modified pursuant
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

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=state&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'state_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_state_default.less');
AdminUIHelper::startAdminArea($this);

?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-striped" id="state_list" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php  echo $this->sort('states.tsmart_state_id','Id') ; ?></label>

                    </th>
                    <th>
                        <?php echo $this->sort('states.sate_name',JText::_('GEO_STATE_NAME')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country.country_name',JText::_('GEO_COUNTRY_NAME')); ?>

                    </th>
                    <th>
                        <?php echo $this->sort('states.flag',JText::_('GEO_STATE_FLAG')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('states.flag',JText::_('GEO_STATE_ABBR')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('states.flag',JText::_('GEO_STATE_ISO_ALPHA2')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('states.zip_code',JText::_('GEO_STATE_ZIP_CODE')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('states.phone_code',JText::_('GEO_STATE_PHONE_CODE')); ?>
                    </th>

                    <th>
                        <?php echo $this->sort('total_city', 'No number'); ?>

                    </th>
                    <th>
                        <?php echo $this->sort('total_airport', JText::_('List airport')); ?>

                    </th>
                    <th width="70px">
                        <?php echo tsmText::_('Action'); ?>
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

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_state_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=state&task=edit_in_line&cid[]=' . $row->tsmart_state_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_state_id', $editlink);
                    $save_link = JROUTE::_('index.php?option=com_tsmart&view=state&task=save_in_line&cid[]=' . $row->tsmart_cstate_id);
                    $save = $this->grid_save_in_line($row, $i, 'tsmart_state_id', $save_link);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_state_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'tsmart_state_id');
                    $show_edit = ($show_edit_in_line == 1 && in_array($row->tsmart_state_id, $cid)) || ($show_edit_in_line == 1 && count($cid) == 0 && $i == 0);

                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::inputHidden(array(tsmart_state_id => $row->tsmart_state_id)); ?>
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
                                <?php echo VmHTML::select('tsmart_country_id', $this->list_country, $row->tsmart_country_id, '', 'tsmart_country_id', 'country_name'); ?>
                            <?php } else { ?>
                                <?php echo VmHTML::show_image(JUri::root().'/'.$row->country_flag, 'class="required"',20,20); ?>
                                <?php echo $row->country_name; ?>
                            <?php } ?>

                        </td>
                        <td align="left">

                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::image('flag', $row->flag, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo VmHTML::show_image(JUri::root().'/'.$row->flag, 'class="required"',20,20); ?>
                            <?php } ?>
                        </td>

                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('state_abbr', $row->state_abbr, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->state_abbr; ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('iso_alpha2', $row->iso_alpha2, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->iso_alpha2; ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('zip_code', $row->zip_code, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->zip_code ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('phone_code', $row->phone_code, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->phone_code ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php echo $row->total_city; ?>

                        </td>
                        <td>
                            <?php echo $row->list_airport_name; ?>

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