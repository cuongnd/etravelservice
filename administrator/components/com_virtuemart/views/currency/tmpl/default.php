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
$virtuemart_country_id = $input->get('virtuemart_country_id', 0, 'int');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn  = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder)
{

    $saveOrderingUrl = 'index.php?option=com_virtuemart&controller=currency&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'city_area_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


AdminUIHelper::startAdminArea($this);

?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table>
            <tr>
                <td width="100%">
                    <?php echo $this->displayDefaultViewSearch('currency', 'search'); ?>
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
                                                       onclick="Joomla.checkAll(this)"/><?php  echo $this->sort('virtuemart_transfer_addon_id','Id') ; ?></label>
                    </th>
                    <th>
                        <?php echo $this->sort('title', 'Country name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('iso2', 'ISO2'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('iso3', 'ISO3'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('Flag', 'flag'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('currency_name', 'currency name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('currency_code', 'currency code'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('Sign', 'sign'); ?>
                    </th>
                    <th width="1%" class="nowrap center hidden-phone">
                        <?php echo $this->sort('ordering', 'ordering'); ?>
                        <?php if ($saveOrder) : ?>
                            <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); ?>
                        <?php endif; ?>
                    </th>

                    <th width="70">
                        <?php echo vmText::_('Action'); ?>
                    </th>
                    <?php /*	<th width="10">
				<?php echo vmText::_('COM_VIRTUEMART_SHARED'); ?>
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

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_currency_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_virtuemart&view=currency&task=edit_in_line&virtuemart_country_id='.$row->virtuemart_country_id.'&cid[]=' . $row->virtuemart_currency_id);
                    $edit = $this->gridEdit($row, $i, 'virtuemart_currency_id', $editlink);
                    $save_link = JROUTE::_('index.php?option=com_virtuemart&view=currency&task=save_in_line&cid[]=' . $row->virtuemart_currency_id);
                    $save = $this->grid_save_in_line($row, $i, 'virtuemart_currency_id', $save_link);
                    $delete = $this->grid_delete_in_line($row, $i, 'virtuemart_currency_id');
                    $cancel = $this->grid_cancel_in_line($row, $i, 'virtuemart_currency_id');
                    if($show_edit_in_line==1)
                    {
                        $show_edit=($row->virtuemart_country_id==$virtuemart_country_id);
                    }

                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::inputHidden(array(virtuemart_currency_id => $row->virtuemart_currency_id)); ?>
                                <?php echo VmHTML::inputHidden(array(virtuemart_country_id => $row->virtuemart_country_id)); ?>
                                <?php echo $checked ?>
                            <?php } else { ?>
                                <?php echo $checked ?>
                            <?php } ?>


                        </td>
                        <td align="left">
                            <?php echo $row->country_name; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->iso2; ?>

                        </td>
                        <td align="left">
                            <?php echo $row->iso3; ?>

                        </td>
                        <td align="left">
                            <?php echo VmHTML::show_image(JUri::root().'/'.$row->flag, 'class="required"',20,20); ?>

                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('currency_name', $this->currency_name); ?>
                            <?php } else { ?>
                                <?php echo $row->currency_name ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('currency_code', $this->currency_code); ?>
                            <?php } else { ?>
                                <?php echo $row->currency_code ?>
                            <?php } ?>
                        </td>
                        <td align="left">
                            <?php if ($show_edit) { ?>
                                <?php echo VmHTML::input('sign', $this->sign); ?>
                            <?php } else { ?>
                                <?php echo $row->sign ?>
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
                                <?php echo $row->virtuemart_currency_id?$published:''; ?>
                                <?php echo $edit; ?>
                                <?php echo $row->virtuemart_currency_id?$delete:''; ?>
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