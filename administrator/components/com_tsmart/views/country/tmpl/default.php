<?php
/**
 *
 * Description
 *
 * @package    tsmart
 * @subpackage Country
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
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_country_default.less');
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

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=country&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'country_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


AdminUIHelper::startAdminArea($this);
$states = tsmText::_('com_tsmart_STATE_S');
?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-striped table-bordered" id="country_list" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php  echo $this->sort('tsmart_country_id','Id') ; ?></label>
                    </th>
                    <th>
                        <?php echo $this->sort('country_name',JText::_('GEO_COUNTRY_NAME')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('GEO_COUNTRY_FLAG')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('GEO_LANGUAGE')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('GEO_CURRENCY')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('GEO_COUNTRY_ISO_ALPHA2')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('GEO_COUNTRY_ISO_ALPHA3')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('GEO_COUNTRY_ISO_NUMERIC')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('GEO_COUNTRY_PCODE')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('GEO_COUNTRY_NO_STATE')) ?>
                    </th>
                    <th>
                        <?php echo $this->sort('country_flag',JText::_('Action')) ?>
                    </th>
<!--                    <th width="1%" class="nowrap center hidden-phone">
                        <?php /*echo $this->sort('ordering', 'ordering'); */?>
                        <?php /*if ($saveOrder) : */?>
                            <?php /*echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); */?>
                        <?php /*endif; */?>
                    </th>
-->
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

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_country_id);
                    $published = $this->gridPublished($row, $i);
                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=country&task=edit_in_line&cid[]=' . $row->tsmart_country_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_country_id', $editlink);
                    $save_link = JROUTE::_('index.php?option=com_tsmart&view=country&task=save_in_line&cid[]=' . $row->tsmart_country_id);
                    $save = $this->grid_save_in_line($row, $i, 'tsmart_country_id', $save_link);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_country_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'tsmart_country_id');
                    $show_edit = ($show_edit_in_line == 1 && in_array($row->tsmart_country_id, $cid)) || ($show_edit_in_line == 1 && count($cid) == 0 && $i == 0);


                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::inputHidden(array(tsmart_country_id => $row->tsmart_country_id)); ?>
                                <?php echo $checked ?>
                            <?php } else { ?>
                                <?php echo $checked ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('country_name', $row->country_name, 'class="required"'); ?>
                            <?php } else { ?>
                                <a href="<?php echo $editlink; ?>"><?php echo $row->country_name ?> </a>
                            <?php } ?>

                        </td>
                        <td align="left">

                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::image('flag', $row->flag, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo VmHTML::show_image(JUri::root().'/'.$row->flag, 'class="required"',20,20); ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::select_language(array(), 'tsmart_language_id', $row->tsmart_language_id, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->language_name ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::select_currency(array(), 'tsmart_currency_id', $row->tsmart_currency_id, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->currency_name ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('iso2', $row->iso2, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->iso2 ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('iso3', $row->iso3, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->iso3 ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('iso_number', $row->iso_number, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo $row->iso_number ?>
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
                            <?php echo $row->total_state ?>

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
<!--                        <td>
                            <span class="sortable-handler">
								<span class="icon-menu"></span>
							</span>
                            <?php /*if ($saveOrder) : */?>
                                <input type="text" style="display:none" name="order[]" size="5" value="<?php /*echo $row->ordering; */?>" class="width-20 text-area-order " />
                            <?php /*endif; */?>
                            <?php /*//echo $row->ordering */?>

                        </td>
-->                    </tr>
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
        <input type="hidden" name="controller" value="country"/>
        <input type="hidden" name="view" value="country"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <?php echo JHtml::_('form.token'); ?>
    </form>


<?php AdminUIHelper::endAdminArea(); ?>