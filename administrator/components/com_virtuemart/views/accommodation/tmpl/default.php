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


$input = JFactory::getApplication()->input;
$task = $input->getString('task', '');
echo $task;
AdminUIHelper::startAdminArea($this);

$js_content = '';
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-accommodation-default').view_accommodation_default({
            task: "<?php echo $task ?>"
        });
    });
</script>
<?php

$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/view_accommodation_default.js');

$listOrder = $this->escape($this->lists['filter_order']);
$listDirn = $this->escape($this->lists['filter_order_Dir']);
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {

    $saveOrderingUrl = 'index.php?option=com_virtuemart&controller=accommodation&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'tour_type_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


?>
<div class="view-accommodation-default form-tour-build">
    <?php echo vmproduct::get_html_tour_information($this, $this->virtuemart_product_id); ?>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table>
            <tr>
                <td width="100%">
                    <?php echo $this->displayDefaultViewSearch('tour type', 'search'); ?>
                </td>
            </tr>
        </table>
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table id="tour_type_list" class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('virtuemart_transfer_addon_id', 'Id'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('title', 'Location'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('icon', 'service class'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('meta_title', 'Hotel name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('key_word', 'Hotel grade'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Room type'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Hotel area'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Review'); ?>
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

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_tour_type_id);
                    $published = $this->gridPublished($row, $i);

                    $editlink = JROUTE::_('index.php?option=com_virtuemart&view=accommodation&task=' . ($row->virtuemart_accommodation_id ? 'add_new_item' : 'edit_item') . '&cid[]=' . $row->virtuemart_accommodation_id . '&virtuemart_product_id=' . $row->virtuemart_product_id.'&virtuemart_itinerary_id='.$row->virtuemart_itinerary_id);
                    $edit = $this->gridEdit($row, $i, 'virtuemart_tour_type_id', $editlink);
                    $delete = $this->grid_delete_in_line($row, $i, 'virtuemart_tour_type_id');

                    $virtuemart_accommodation_id=$row->virtuemart_accommodation_id;
                    $virtuemart_itinerary_id=$row->virtuemart_itinerary_id;
                    require_once  JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmserviceclass.php';
                    $list_service_class=vmServiceclass::get_list_service_class_by_tour_id($this->virtuemart_product_id);
                    require_once  JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmhotel.php';

                    require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmaccommodation.php';
                    $list_hotel_selected_by_service_class_id_and_itinerary_id=vmaccommodation::get_list_hotel_selected_by_service_class_id_and_itinerary_id_accommodation_id($list_service_class,$virtuemart_itinerary_id,$virtuemart_accommodation_id);
                    $rowspan=0;
                    foreach($list_hotel_selected_by_service_class_id_and_itinerary_id AS $service_class )
                    {
                        $rowspan+=count($service_class->list_hotel)+1;
                    }
                    $rowspan=$rowspan+1;
                    ?>

                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox" rowspan="<?php echo $rowspan ?>">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left" rowspan="<?php echo $rowspan ?>">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->city_area_name; ?></a>
                        </td>
                        <td align="left">
                        </td>
                        <td align="left">
                            <?php echo $row->meta_title; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->meta_title; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->meta_title; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->meta_title; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->key_word; ?>
                        </td>


                        <td align="center">
                            <?php echo $published; ?>
                            <?php echo $edit; ?>
                            <?php echo $delete; ?>
                        </td>
                    </tr>


                    <?php foreach($list_hotel_selected_by_service_class_id_and_itinerary_id AS $service_class){ ?>
                        <tr class="row<?php echo $k; ?>">


                            <td align="left">
                                <?php echo $service_class->service_class_name ?>
                            </td>
                            <td align="left">
                                <?php echo $row->meta_title; ?>
                            </td>
                            <td align="left">
                                <?php echo $row->meta_title; ?>
                            </td>
                            <td align="left">
                                <?php echo $row->meta_title; ?>
                            </td>
                            <td align="left">
                                <?php echo $row->meta_title; ?>
                            </td>
                            <td align="left">
                                <?php echo $row->key_word; ?>
                            </td>


                            <td align="center">

                            </td>
                        </tr>
                        <?php foreach($service_class->list_hotel AS $hotel){ ?>
                            <?php
                            $hotel_item=$this->list_hotel[$hotel->virtuemart_hotel_id];
                            ?>
                            <tr class="row<?php echo $k; ?>">
                                <td align="left">

                                </td>
                                <td align="left">
                                    <?php echo $hotel_item->hotel_name ?>
                                </td>
                                <td align="left">
                                    <?php echo $row->meta_title; ?>
                                </td>
                                <td align="left">
                                    <?php echo $row->meta_title; ?>
                                </td>
                                <td align="left">
                                    <?php echo $row->meta_title; ?>
                                </td>
                                <td align="left">
                                    <?php echo $row->key_word; ?>
                                </td>


                                <td align="center">

                                </td>
                            </tr>
                        <?php } ?>

                    <?php } ?>

                    <?php
                    $k = 1 - $k;
                }
                ?>
                <tfoot>
                <tr>
                    <td colspan="12">
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
    if ($task == 'edit_item'||$task=='add_new_item') {
        echo $this->loadTemplate('edit');
    }
    ?>
</div>


<?php AdminUIHelper::endAdminArea(); ?>

