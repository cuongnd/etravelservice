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
$app = JFactory::getApplication();
$input = $app->input;
$show_edit_in_line = $input->get('show_edit_in_line', 0, 'int');
$cid = $input->get('cid', array(), 'array');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn  = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder)
{

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=cityarea&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'city_area_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


AdminUIHelper::startAdminArea($this);

?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div class="vm-page-nav">
                <?php echo AdminUIHelper::render_pagination($this->pagination) ?>
            </div>
            <table class="adminlist table table-striped table-bordered" id="city_area_list" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php  echo $this->sort('tsmart_transfer_addon_id','Id') ; ?></label>
                    </th>
                    <th>
                        <?php echo $this->sort('city_area_name', JText::_('GEO_CITY_NAME')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('phone_code',JText::_('GEO_CITY_PHONE_CODE')); ?>
                    </th>

                    <th>
                        <?php echo $this->sort('phone_code',JText::_('GEO_CITY_ABBR')); ?>
                    </th>

                    <th>
                        <?php echo $this->sort('list_airport',JText::_('GEO_CITY_LIST_AIRPORT') ); ?>
                    </th>
                    <?php if(!$show_edit_in_line){ ?>
                    <th>
                        <?php echo $this->sort('state_name', 'State'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_name', 'Country'); ?>
                    </th>
                    <?php }else{ ?>
                        <th>
                            <?php echo $this->sort('country_name', 'Country'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('state_name', 'State'); ?>
                        </th>

                    <?php } ?>
<!--                    <th width="1%" class="nowrap center hidden-phone">
                        <?php /*echo $this->sort('ordering', 'ordering'); */?>
                        <?php /*if ($saveOrder) : */?>
                            <?php /*echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); */?>
                        <?php /*endif; */?>
                    </th>
-->
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
                $add_new = $show_edit_in_line == 1 && count($cid) == 0;
                if ($add_new) {
                    $item = new stdClass();
                    $item->published = 1;
                    array_unshift($this->items, $item);
                }
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_cityarea_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=cityarea&task=edit_in_line&cid[]=' . $row->tsmart_cityarea_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_cityarea_id', $editlink);
                    $save_link = JROUTE::_('index.php?option=com_tsmart&view=cityarea&task=save_in_line&cid[]=' . $row->tsmart_cityarea_id);
                    $save = $this->grid_save_in_line($row, $i, 'tsmart_cityarea_id', $save_link);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_cityarea_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'tsmart_cityarea_id');
                    $show_edit = ($show_edit_in_line == 1 && in_array($row->tsmart_cityarea_id, $cid)) || ($show_edit_in_line == 1 && count($cid) == 0 && $i == 0);

                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::inputHidden(array(tsmart_cityarea_id => $row->tsmart_cityarea_id)); ?>
                                <?php echo $checked ?>
                            <?php } else { ?>
                                <?php echo $checked ?>
                            <?php } ?>


                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('city_area_name', $row->city_area_name, 'class="required"'); ?>
                            <?php } else { ?>
                                <a href="<?php echo $editlink; ?>"><?php echo $row->city_area_name; ?></a>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('phone_code', $row->phone_code, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->phone_code ?>
                            <?php } ?>

                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('city_abbr', $row->city_abbr, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->city_abbr; ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php echo $row->list_airport ?>
                        </td>
                        <?php if(!$show_edit_in_line){ ?>
                        <td>
                            <?php echo $row->state_name ?>
                        </td>
                        <td>
                            <?php echo VmHTML::show_image(JUri::root().'/'.$row->country_flag, 'class="required"',20,20); ?>
                            <?php echo $row->country_name ?>
                        </td>
                        <?php }else{ ?>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::show_image(JUri::root().'/'.$row->country_flag, 'class="required"',20,20); ?>
                                <?php echo VmHTML::select('tsmart_country_id', $this->list_country, $row->tsmart_country_id, '', 'tsmart_country_id', 'country_name'); ?>
                            <?php } else { ?>
                                <?php echo VmHTML::show_image(JUri::root().'/'.$row->country_flag, 'class="required"',20,20); ?>
                                <?php echo $row->country_name ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::select_state_province('tsmart_state_id', $this->list_state, $row->tsmart_state_id, '', 'tsmart_state_id', 'state_name','select[name="tsmart_country_id"]'); ?>
                            <?php } else { ?>
                                <?php echo $row->state_name ?>
                            <?php } ?>
                        </td>
                        <?php } ?>
<!--                        <td align="left">
                            <span class="sortable-handler">
								<span class="icon-menu"></span>
							</span>
                            <?php /*if ($saveOrder) : */?>
                                <input type="text" style="display:none" name="order[]" size="5"
                                       value="<?php /*echo $row->ordering; */?>" class="width-20 text-area-order "/>
                            <?php /*endif; */?>



                            <?php /*//echo $row->ordering; */?>
                        </td>
-->
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
                    <td colspan="11">
                      &nbsp;
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <?php echo $this->addStandardHiddenToForm(); ?>
        <?php echo JHtml::_('form.token'); ?>
    </form>


<?php AdminUIHelper::endAdminArea(); ?>