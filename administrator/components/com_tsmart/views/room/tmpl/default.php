<<<<<<< master
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
$doc = JFactory::getDocument();
JHtml::_('jquery.framework');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');

$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');
$input=JFactory::getApplication()->input;

$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_room_default.js');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_room_default.less');
AdminUIHelper::startAdminArea($this);
$task=$input->getString('task','');
$js_content = '';
$app = JFactory::getApplication();

ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-room-default').view_room_default({
            task: "<?php echo $task ?>"
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=room&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'tour_class_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}



?>
<div class="view-room-default">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <!--<table>
		<tr>
			<td width="100%">
				<?php /*echo $this->displayDefaultViewSearch ('com_tsmart_CURRENCY','search') ; */ ?>
			</td>
		</tr>
		</table>-->
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-bordered table-striped" id="tour_class_list" cellspacing="0"
                   cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_transfer_addon_id', 'Id'); ?>
                        </label>
                    </th>
                    <th>
                        <?php echo $this->sort('room_name', 'room name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('icon', 'Image 1'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('icon', 'Image 2'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Description'); ?>
                    </th>
<!--                    <th>
                        <?php /*echo $this->sort('ordering', 'ordering'); */?>
                        <?php /*if ($saveOrder) : */?>
                            <?php /*echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); */?>
                        <?php /*endif; */?>

                    </th>
-->                    <th>
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

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_room_id);
                    $published = $this->gridPublished($row, $i);
                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=room&task=show_parent_popup&cid[]=' . $row->tsmart_room_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_room_id', $editlink);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_room_id');
                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->room_name; ?></a>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::show_image(JUri::root() . '/' . $row->image1, 'class="required"', 40, 40); ?>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::show_image(JUri::root() . '/' . $row->image2, 'class="required"', 40, 40); ?>
                        </td>
                        <td align="left">
                            <?php echo $row->description; ?>
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

=======
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
$doc = JFactory::getDocument();
JHtml::_('jquery.framework');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');

$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');


$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_room_default.js');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_room_default.less');
AdminUIHelper::startAdminArea($this);
$js_content = '';
$app = JFactory::getApplication();
$input = JFactory::getApplication()->input;

$task=$input->get('task','');

ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-room-default').view_room_default({
            task: "<?php echo $task ?>"
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=room&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'tour_class_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$app=JFactory::getApplication();
$tsmart_hotel_id=$app->input->getInt('tsmart_hotel_id',0);

?>
<div class="view-room-default">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <!--<table>
		<tr>
			<td width="100%">
				<?php /*echo $this->displayDefaultViewSearch ('com_tsmart_CURRENCY','search') ; */ ?>
			</td>
		</tr>
		</table>-->
        <a href="index.php?option=com_tsmart&view=hotel"><?php echo JText::_('Back hotel listing') ?></a>
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-bordered table-striped" id="tour_class_list" cellspacing="0"
                   cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('tsmart_transfer_addon_id', 'Id'); ?>
                        </label>
                    </th>
                    <th>
                        <?php echo $this->sort('room_name', 'room name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('icon', 'Image 1'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('icon', 'Image 2'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Description'); ?>
                    </th>
<!--                    <th>
                        <?php /*echo $this->sort('ordering', 'ordering'); */?>
                        <?php /*if ($saveOrder) : */?>
                            <?php /*echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder'); */?>
                        <?php /*endif; */?>

                    </th>
-->                    <th>
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

                    $checked = JHtml::_('grid.id', $i, $row->tsmart_room_id);
                    $published = $this->gridPublished($row, $i);
                    $editlink = JROUTE::_('index.php?option=com_tsmart&view=room&task=edit&cid[]=' . $row->tsmart_room_id.'&tsmart_hotel_id='.$tsmart_hotel_id.'&key[tsmart_hotel_id]='.$tsmart_hotel_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_room_id', $editlink);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_room_id');
                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->room_name; ?></a>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::show_image(JUri::root() . '/' . $row->image1, 'class="required"', 40, 40); ?>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::show_image(JUri::root() . '/' . $row->image2, 'class="required"', 40, 40); ?>
                        </td>
                        <td align="left">
                            <?php echo $row->description; ?>
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

>>>>>>> local
