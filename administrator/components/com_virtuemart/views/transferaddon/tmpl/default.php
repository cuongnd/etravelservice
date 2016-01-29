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

AdminUIHelper::startAdminArea($this);

?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table>
            <tr>
                <td width="100%">
                    <?php echo $this->displayDefaultViewSearch('transferaddon', 'search'); ?>
                </td>
            </tr>
        </table>
        <div id="editcell">
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php  echo $this->sort('virtuemart_transferaddon_id','Id') ; ?></label>

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
                        <?php echo $this->sort('start_date', 'Start date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('end_date', 'End date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Description'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Application') ?>
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
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_transferaddon_id);
                    $published = $this->gridPublished($row, $i);
                    $delete = $this->grid_delete_in_line($row, $i, 'virtuemart_transferaddon_id');
                    $editlink = JROUTE::_('index.php?option=com_virtuemart&view=transferaddon&task=show_parent_popup&cid[]=' . $row->virtuemart_transferaddon_id);
                    $edit = $this->gridEdit($row, $i, 'virtuemart_transferaddon_id', $editlink);
                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->title; ?></a>
                        </td>
                        <td align="left">
                            <?php echo $row->created_on; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->location; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->price; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->start_date; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->end_date; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->description; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->list_tour; ?>
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
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <?php echo $this->addStandardHiddenToForm(); ?>
        <?php echo JHtml::_('form.token'); ?>
    </form>


<?php AdminUIHelper::endAdminArea(); ?>