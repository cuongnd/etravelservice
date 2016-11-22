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
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/administrator/components/com_tsmart/assets/js/view_itinerary_default.js');
$doc->addLessStyleSheet(JUri::root().'/administrator/components/com_tsmart/assets/less/view_itinerary_default.less');
$input = JFactory::getApplication()->input;
$task=$input->get('task','');
$listOrder = $this->escape($this->lists['filter_order']);
$listDirn  = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder)
{

    $saveOrderingUrl = 'index.php?option=com_tsmart&controller=itinerary&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'itineraryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$user=JFactory::getUser();
$userId    = $user->get('id');

AdminUIHelper::startAdminArea($this);

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-itinerary-default').view_itinerary_default({
                task: "<?php echo $task ?>"
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
?>

<div class="view-itinerary-default form-tour-build">
    <?php echo vmproduct::get_html_tour_information($this,$this->tsmart_product_id); ?>

    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-striped" id="itineraryList" cellspacing="0" cellpadding="0">
                <thead>

                <tr>

                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php  echo $this->sort('tsmart_transfer_addon_id','Id') ; ?></label>
                    </th>
                    <th>
                        <?php echo $this->sort('ordering', 'Day title'); ?>
                        <?php if ($saveOrder) :?>
                            <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'saveOrder' ); ?>
                        <?php endif; ?>
                    </th>
                    <th>
                        <?php echo $this->sort('overnight', 'Overnight'); ?>
                    </th>

                    <th>
                        <?php echo $this->sort('short_description', 'Short description'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('full_description', 'Full description'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('trip_note1', 'Trip note 1'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('trip_note2', 'Trip note 2'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('meal_selection', 'Meal selection'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('photo1', 'Photo 1'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('photo2', 'Photo 2'); ?>
                    </th>

                    <th width="10">
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
                    $row->tsmart_product_id=$row->tsmart_product_id?$row->tsmart_product_id:$this->tsmart_product_id;
                    $checked = JHtml::_('grid.id', $i, $row->tsmart_itinerary_id);
                    $published = $this->gridPublished($row, $i);
                    $delete = $this->grid_delete_in_line($row, $i, 'tsmart_cityarea_id');
                    $editlink = JROUTE::_('index.php?option=com_tsmart&controller=itinerary&task=edit&&tsmart_product_id='.$row->tsmart_product_id.'&cid[]=' . $row->tsmart_itinerary_id);
                    $edit = $this->gridEdit($row, $i, 'tsmart_itinerary_id', $editlink);
                    ?>
                    <tr class="row<?php echo $k; ?>">


                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <span class="sortable-handler">
								<span class="icon-menu"></span>
							</span>
                            <?php if ($saveOrder) : ?>
                                <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order " />
                            <?php endif; ?>


                            <a href="<?php echo $editlink; ?>"><?php echo $row->title; ?></a>
                        </td>
                        <td align="left">
                            <?php echo $row->city_area_name; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->brief_itinerary; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->full_itinerary; ?>
                        </td>

                        <td align="left">
                            <?php echo $row->trip_note1; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->trip_note2; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->list_meal; ?>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::show_image(JUri::root().'/'.$row->photo1, 'class="required"',40,40); ?>
                        </td>
                        <td align="left">
                            <?php echo VmHTML::show_image(JUri::root().'/'.$row->photo2, 'class="required"',40,40); ?>
                        </td>
                        <td align="center" width="70">
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
                    <td colspan="13">
                        <?php echo $this->pagination->getListFooter(); ?>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <?php echo VmHTML::inputHidden(array(
            'key[tsmart_product_id]'=>$this->tsmart_product_id
        )); ?>

        <input type="hidden" name="option" value="com_tsmart">
        <input type="hidden" name="controller" value="itinerary">
        <input type="hidden" name="tsmart_vendor_id" value="1">
        <input type="hidden" name="view" value="itinerary" />
        <input type="hidden" name="task" value="">
        <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />';
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <?php
    if ($task == 'edit'||$task=='add') {
        echo $this->loadTemplate('edit');
    }
    ?>
</div>

<?php AdminUIHelper::endAdminArea(); ?>