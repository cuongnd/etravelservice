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

AdminUIHelper::startAdminArea($this);
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=hotel&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'hotel_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_hotel_default.js');
$doc->addLessStyleSheet(JUri::root().'administrator/components/com_tsmart/assets/less/view_hotel_default.less');
$input = JFactory::getApplication()->input;

$task=$input->get('task','');
$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-hotel-default').view_hotel_default({
                task: "<?php echo $task ?>"
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);


?>
<div class="view-hotel-default">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table id="hotel_list" class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_hotel_id', 'Id'); ?>

                    </th>
                    <th>
                        <?php echo $this->sort('hotel_name', 'Hotel name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('location', 'location'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('address', 'address'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('star_rating', 'Star'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('Review', 'review'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Tel/Fax'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('email', 'Email'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Room type'); ?>
                    </th>
<!--                    <th>
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
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_hotel_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=hotel&task=edit&layout=default&cid[]=' . $row->tsmart_hotel_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_hotel_id', $editlink);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_hotel_id');
                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->hotel_name; ?></a>
                        </td>
                        <td align="left">
                            <?php echo $row->location; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->address; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->star_rating; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->review; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->tel.'/'.$row->fax; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->email; ?>
                        </td>
                        <td align="left">
                            <a href="index.php?option=com_tsmart&amp;view=airport"><span title="" class="icon-eye"></span></a>
                            <a   href="index.php?option=com_tsmart&amp;view=room&key[tsmart_hotel_id]=<?php echo $row->tsmart_hotel_id ?>&tsmart_hotel_id=<?php echo $row->tsmart_hotel_id ?>" class=" <?php echo $row->total_room?' existed-room ':'' ?>" title="<?php echo $row->total_room?' existed room ':'' ?>"><span title="" class="icon-edit"></span></a>
                        </td>
<!--                        <td align="left">
                            <span class="sortable-handler">
								<span class="icon-menu"></span>
							</span>
                            <?php /*if ($saveOrder) : */?>
                                <input type="text" style="display:none" name="order[]" size="5"
                                       value="<?php /*echo $row->ordering; */?>" class="width-20 text-area-order "/>
                            <?php /*endif; */?>


                        </td>
-->

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
    <?php
    if ($task == 'edit'||$task=='add') {
        echo $this->loadTemplate('edit');
    }
    ?>
</div>
<?php AdminUIHelper::endAdminArea(); ?>