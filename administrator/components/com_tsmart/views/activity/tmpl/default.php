<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
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

AdminUIHelper::startAdminArea($this);
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=activity&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'activity_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
<!--        <table>
            <tr>
                <td width="100%">
                    <?php /*echo $this->displayDefaultViewSearch('activity', 'search'); */?>
                </td>
            </tr>
        </table>
-->        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table id="activity_list" class="adminlist table table-bordered table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('virtuemart_transfer_addon_id', 'Id'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('title', 'Tour activity name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('icon', 'Icon'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('meta_title', 'Meta title'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('key_word', 'Key word'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Description'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('ordering', 'ordering'); ?>
                        <?php if ($saveOrder) : ?>
                            <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); ?>
                        <?php endif; ?>

                    </th>

                    <th width="70px">
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

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_activity_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=activity&task=show_parent_popup&cid[]=' . $row->virtuemart_activity_id.'&add_new_popup=1');
                    $edit = $this->gridEdit($row, 'virtuemart_activity_id', $i,$editlink);
                    $delete = $this->grid_delete_in_line($row, $i, 'virtuemart_activity_id');

                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->title; ?></a>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::show_image(JUri::root() . '/' . $row->icon, 'class="required"', 40, 40); ?>
                        </td>
                        <td align="left">
                            <?php echo $row->meta_title; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->key_word; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->description; ?>
                        </td>
                        <td align="left">
                            <span class="sortable-handler">
								<span class="icon-menu"></span>
							</span>
                            <?php if ($saveOrder) : ?>
                                <input type="text" style="display:none" name="order[]" size="5"
                                       value="<?php echo $row->ordering; ?>" class="width-20 text-area-order "/>
                            <?php endif; ?>


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