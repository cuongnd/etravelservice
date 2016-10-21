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
$tsmart_currency_id = $input->get('tsmart_currency_id', 0, 'int');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn  = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder)
{

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=currency&task=saveOrderAjax&tmpl=component';
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
                        <?php echo $this->sort('currency_name',JText::_('GEO_CURRENCY_NAME') ); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('currency_code',JText::_('GEO_CURRENCY_CODE')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('Sign', JText::_('GEO_CURRENCY_SYMBOL')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('image', JText::_('GEO_CURRENCY_SYMBOL_IMAGE')); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('image', JText::_('GEO_CURRENCY_HAX_SYMBOL')); ?>
                    </th>
                    <th width="1%" class="nowrap center hidden-phone">
                        <?php echo $this->sort('ordering', 'ordering'); ?>
                        <?php if ($saveOrder) : ?>
                            <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); ?>
                        <?php endif; ?>
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
                $add_new = $show_edit_in_line == 1 && count($cid) == 0;
                if ($add_new) {
                    $item = new stdClass();
                    $item->published = 1;
                    array_unshift($this->items, $item);
                }
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];
                    $checked = JHtml::_('grid.id', $i, $row->tsmart_currency_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=currency&task=edit_in_line&tsmart_currency_id='.$row->tsmart_currency_id.'&cid[]=' . $row->tsmart_currency_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_currency_id', $editlink);
                    $save_link = JROUTE::_('index.php?option=com_tsmart&view=currency&task=save_in_line&cid[]=' . $row->tsmart_currency_id);
                    $save = $this->grid_save_in_line($row, $i, 'tsmart_currency_id', $save_link);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_currency_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'tsmart_currency_id');
                    if($show_edit_in_line==1)
                    {
                        $show_edit=($row->tsmart_currency_id==$tsmart_currency_id);
                    }

                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::inputHidden(array(tsmart_currency_id => $row->tsmart_currency_id)); ?>
                                <?php echo $checked ?>
                            <?php } else { ?>
                                <?php echo $checked ?>
                            <?php } ?>


                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('currency_name', $row->currency_name); ?>
                            <?php } else { ?>
                                <?php echo $row->currency_name ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('iso_code', $row->iso_code); ?>
                            <?php } else { ?>
                                <?php echo $row->iso_code ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('currency_symbol', $row->currency_symbol); ?>
                            <?php } else { ?>
                                <?php echo $row->currency_symbol ?>
                            <?php } ?>
                        </td>
                        <td align="left">

                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::image('image', $row->image, 'class="required"'); ?>
                            <?php } else { ?>
                                <?php echo VmHTML::show_image(JUri::root().'/'.$row->image, 'class="required"',20,20); ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('hex_symbol', $row->hex_symbol); ?>
                            <?php } else { ?>
                                <?php echo $row->hex_symbol ?>
                            <?php } ?>
                        </td>
                        <td align="left">


                            <span class="sortable-handler">
								<span class="icon-menu"></span>
							</span>
                            <?php if ($saveOrder) : ?>
                                <input type="text" style="display:none" name="order[]" size="5"
                                       value="<?php echo $row->ordering; ?>" class="width-20 text-area-order "/>
                            <?php endif; ?>



                            <?php //echo $row->ordering; ?>
                        </td>

                        <td align="center">
                            <?php if ($show_edit) { ?>
                                <?php echo $add_new ? '' : $published; ?>
                                <?php echo $save; ?>
                                <?php echo $cancel; ?>
                                <?php echo VmHTML::inputHidden(array(published => $row->published)); ?>

                            <?php } else { ?>
                                <?php echo $row->tsmart_currency_id?$published:''; ?>
                                <?php echo $edit; ?>
                                <?php echo $row->tsmart_currency_id?$delete:''; ?>
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