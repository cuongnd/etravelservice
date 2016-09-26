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
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
//JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');

$doc->addScript(JUri::root() . '/media/system/js/multi_calendar_date_picker/js/multi_calendar_date_picker.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/jquery.dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js');
$doc->addScript(JUri::root() . '/media/system/js/jQuery-Plugin-For-Bootstrap-Button-Group-Toggles/select-toggleizer.js');
$doc->addScript(JUri::root() . '/media/system/js/tooltipster-master/js/jquery.tooltipster.js');
$doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
$doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/moment.js');
$doc->addScript(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker.js');
$doc->addScript(JUri::root() . '/media/system/js/ion.rangeSlider-master/js/ion.rangeSlider.js');
$doc->addScript(JUri::root() . '/media/system/js/jQuery.serializeObject-master/jquery.serializeObject.js');
//$doc->addScript(JUri::root() . '/media/system/js/jquery.twbsPagination.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery.serializeObject.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');

$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/datepicker.css');

$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/tipso-master/src/tipso.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
$doc->addScript(JUri::root().'/media/system/js/tipso-master/src/tipso.js');

$doc->addScript(JUri::root() . '/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_dateavailability_default.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/multi_calendar_date_picker/css/base.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/multi_calendar_date_picker/css/clean.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/tooltipster-master/css/tooltipster.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/tooltipster-master/css/themes/tooltipster-light.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_dateavailability_default.less');

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-dateavailability-default').view_dateavailability_default({});
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
AdminUIHelper::startAdminArea($this);

?>
    <div class="view-dateavailability-default">
        <form action="index.php" method="post" name="adminForm" id="adminForm">
            <table>
                <tr>
                    <td width="100%">
                        <?php echo $this->displayDefaultViewSearch('com_tsmart_CURRENCY', 'search'); ?>
                    </td>
                </tr>
            </table>
            <div id="editcell">
                <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="admin-checkbox">
                            <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)"/>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Tour name'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Tour code'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Service class'); ?>
                        </th>
                        <td>
                            <?php echo $this->sort('amen_text', 'Amen date'); ?>
                        </td>

                        <th>
                            <?php echo $this->sort('currency_name', 'Asign'); ?>
                        </th>
                        <th colspan="2">Action</th>
                        <?php /*	<th width="10">
				<?php echo vmText::_('com_tsmart_SHARED'); ?>
			</th> */ ?>
                    </tr>
                    </thead>
                    <?php
                    $k = 0;
                    for ($i = 0, $n = count($this->list_date_availability); $i < $n; $i++) {

                        $row = $this->list_date_availability[$i];
                        $checked = JHtml::_('grid.id', $i, $row->virtuemart_dateavailability_id);
                        $published = $this->gridPublished($row, $i);

                        $editlink = JROUTE::_('index.php?option=com_tsmart&view=dateavailability&task=edit&cid[]=' . $row->virtuemart_dateavailability_id);
                        ?>
                        <tr role="row" class="row<?php echo $k; ?>"
                            data-virtuemart_dateavailability_id="<?php echo $row->virtuemart_date_availability_id ?>"
                            data-virtuemart_product_id="<?php echo $row->virtuemart_product_id ?>" data-virtuemart_service_class_id="<?php echo $row->virtuemart_service_class_id ?>">
                            <td class="admin-checkbox">
                                <?php echo $checked; ?>
                            </td>
                            <td>
                                <?php echo $row->product_name ?>
                            </td>
                            <td>
                                <?php echo $row->product_code ?>
                            </td>
                            <td>
                                <?php echo $row->service_class_name ?>
                            </td>
                            <td>
                                <span data-tipso-content="<?php echo $row->dates ?>" class="show-list-date icon-eye"></span>
                            </td>

                            <td>
                                <?php echo $row->assign_user_name ?></span>
                            </td>
                            <td><a href="javascript:void(0)" class="edit-dateavailability">
                                    <span class="icon-edit icon-white"></span>
                                </a>
                                <a href="javascript:void(0)" class=" publish-dateavailability">
                                    <span
                                        class="icon-<?php echo $row->published ? 'publish' : 'unpublish' ?> icon-white"></span>
                                </a>
                                <a href="javascript:void(0)" class=" delete-dateavailability">
                                    <span class="icon-delete icon-white"></span>
                                </a>
                            </td>

                            <?php /*
		<td align="center">
			<?php echo $row->shared; ?>
		</td>	*/ ?>
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

            <input type="hidden" name="option" value="com_tsmart"/>
            <input type="hidden" name="controller" value="dateavailability"/>
            <input type="hidden" name="view" value="dateavailability"/>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order']; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['filter_order_Dir']; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
        <form action="index.php" method="post" class="dateavailability-edit-form" name="adminFormEdit" id="adminFormEdit">


            <div class="col50">
                <div class="row-fluid">
                    <div class="span12 ">

                        <label>Select tour name</label>
                        <select id="virtuemart_product_id" name="virtuemart_product_id" disable_chosen="true" required
                                style="width: 300px">
                            <option value="">select tour</option>
                            <?php foreach ($this->list_tour as $tour) { ?>
                                <option
                                    value="<?php echo $tour->virtuemart_product_id ?>"><?php echo $tour->product_name ?></option>
                            <?php } ?>
                        </select>

                        <label>Select Service Class</label>
                        <select id="virtuemart_service_class_id" disable_chosen="true" required
                                name="virtuemart_service_class_id" style="width: 300px">
                            <option value="">select service</option>
                            <?php foreach ($this->list_tour_class as $tour_service_class) { ?>
                                <option
                                    value="<?php echo $tour_service_class->virtuemart_service_class_id ?>"><?php echo $tour_service_class->service_class_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="area-select-date">
                            <div style="height:;" class="row-fluid">
                                <div class="span12">
                                    <select disable_chosen="true" name="date_type" id="date_type">
                                        <option selected value="day_select">day select</option>
                                    </select>


                                </div>
                            </div>
                            <h4>Customer setting</h4>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div id="multi-calendar-dateavailability">
                                        <input type="hidden" id="days_seleted" name="days_seleted"
                                               value="<?php echo $this->dateavailability->days_seleted ?>">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <a class="btn btn-small btn-success pull-right save-close-price">
                            <span class="icon-save icon-white"></span>
                            Save&close
                        </a>
                        <button class="btn btn-small btn-success pull-right apply-price">
                            <span class="icon-apply icon-white"></span>
                            apply
                        </button>
                        <button class="btn btn-small btn-success pull-right cancel-price">
                            <span class="icon-cancel icon-white"></span>
                            Cancel
                        </button>

                    </div>
                </div>
            </div>
            <input type="hidden" id="virtuemart_dateavailability_id" name="virtuemart_dateavailability_id"
                   value="0"/>
            <input type="hidden" id="published" name="published"
                   value="1"/>

            <?php echo $this->addStandardHiddenToForm('dateavailability', 'ajax_save_dateavailability_item'); ?>
        </form>
    </div>


<?php AdminUIHelper::endAdminArea(); ?>