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

$doc->addScript(JUri::root() . '/media/system/js/jquery-validation-1.14.0/dist/jquery.validate.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_departure_default.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/multi_calendar_date_picker/css/base.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/multi_calendar_date_picker/css/clean.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/tooltipster-master/css/tooltipster.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/tooltipster-master/css/themes/tooltipster-light.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_departure_default.less');

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-departure-default').view_departure_default({});
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
AdminUIHelper::startAdminArea($this);

?>
    <div class="view-departure-default">
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
                            <?php echo $this->sort('currency_name', 'Departure name'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('departure_code', 'Departure code'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Tour name'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Service class'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Min-max'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Avaibility'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Tour price'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('departure_date', 'Departure date'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Sale period'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Discount'); ?>
                        </th>

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
                    for ($i = 0, $n = count($this->list_departure); $i < $n; $i++) {
                        $row = $this->list_departure[$i];
                        $checked = JHtml::_('grid.id', $i, $row->tsmart_departure_id);
                        $published = $this->gridPublished($row, $i);

                        $editlink = JROUTE::_('index.php?option=com_tsmart&view=departure&task=edit&cid[]=' . $row->tsmart_departure_id);
                        ?>
                        <tr role="row" class="row<?php echo $k; ?>"
                            data-tsmart_departure_id="<?php echo $row->tsmart_departure_id ?>"
                            data-tsmart_product_id="<?php echo $item->tsmart_product_id ?>">
                            <td class="admin-checkbox">
                                <?php echo $checked; ?>
                            </td>
                            <td>
                                <a href="<?php echo $editlink ?>"><?php echo $row->departure_name ?></a>
                            </td>
                            <td>
                                <?php echo $row->departure_code ?>
                            </td>
                            <td>
                                <?php echo $row->product_name ?>
                            </td>
                            <td>
                                <?php echo $row->service_class_name ?>
                            </td>
                            <td>
                                <?php echo $row->min_space ?>-<?php echo $row->max_space ?>
                            </td>
                            <td>
                                <span class="icon-eye"></span>
                            </td>
                            <td>
                                B(<span class="price"
                                      data-a-sign="US$"><?php echo tsmConfig::render_price($row->sale_price_adult) ?></span>)
                                <?php if($row->promotion_price_adult){ ?>
                                P(<span class="price"
                                      data-a-sign="US$"><?php echo tsmConfig::render_price($row->sale_promotion_price_adult) ?></span>)
                                <?php } ?>

                            </td>
                            <td>
                                <?php echo JHtml::_('date', $row->departure_date, tsmConfig::$date_format); ?></span>
                            </td>
                            <td class="sale_period"><?php echo JHtml::_('date', $row->sale_period_from, tsmConfig::$date_format); ?>
                                -<?php echo JHtml::_('date', $row->sale_period_to, tsmConfig::$date_format); ?>
                            <td>

                            <td>
                                <?php echo $row->asign_name ?></span>
                            </td>
                            <td><a href="javascript:void(0)" class="edit-departure">
                                    <span class="icon-edit icon-white"></span>
                                </a>
                                <a href="javascript:void(0)" class=" publish-departure">
                                    <span
                                        class="icon-<?php echo $row->published ? 'publish' : 'unpublish' ?> icon-white"></span>
                                </a>
                                <a href="javascript:void(0)" class=" delete-departure">
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
            <input type="hidden" name="controller" value="departure"/>
            <input type="hidden" name="view" value="departure"/>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order']; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['filter_order_Dir']; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
        <form action="index.php" method="post" class="departure-edit-form" name="adminFormEdit" id="adminFormEdit">


            <div class="col50">
                <div class="row-fluid">
                    <div class="span6 ">

                        <label>Select tour name</label>
                        <select id="tsmart_product_id" name="tsmart_product_id" disable_chosen="true" required
                                style="width: 300px">
                            <option value="">select tour</option>
                            <?php foreach ($this->list_tour as $tour) { ?>
                                <option
                                    value="<?php echo $tour->tsmart_product_id ?>"><?php echo $tour->product_name ?></option>
                            <?php } ?>
                        </select>

                        <label>Select Service Class</label>
                        <select id="tsmart_service_class_id" disable_chosen="true" required
                                name="tsmart_service_class_id" style="width: 300px">
                            <option value="">select service</option>
                            <?php foreach ($this->list_tour_class as $tour_service_class) { ?>
                                <option
                                    value="<?php echo $tour_service_class->tsmart_service_class_id ?>"><?php echo $tour_service_class->service_class_name ?></option>
                            <?php } ?>
                        </select>
                        <label>Departure name</label>
                        <input type="text" size="16"
                               value="<?php echo $this->departure->departure_name ?>"
                               id="departure_name"
                               name="departure_name" required
                               class="inputbox">
                        <div class="range-of-date">
                            <label>Vaild period</label>
                            <?php echo VmHTML::range_of_date('sale_period_from', 'sale_period_to', '', ''); ?>
                        </div>
                        <label>Departure Note</label>
                        <textarea name="note" id="note">
                            <?php echo $this->departure->departure_note ?>
                        </textarea>


                    </div>
                    <div class="span6">
                        <label>Setup space</label>
                        <input type="text" id="min_max_space" required name="min_max_space">
                        <label>Sale period open before</label>
                        <input type="text" id="sale_period_open_before" required name="sale_period_open_before"/>
                        <label>Sale period close before</label>
                        <input type="text" id="sale_period_close_before" required name="sale_period_close_before"/>

                        <label>G-Guarantee</label>
                        <input type="text" size="16"
                               value="<?php echo $this->departure->g_guarantee ?>"
                               id="g_guarantee" name="g_guarantee"
                               class="inputbox number" required="true">
                        <label>L-limit space</label>

                        <input type="text" size="16"
                               value="<?php echo $this->departure->limited_space ?>"
                               id="limited_space" name="limited_space"
                               class="inputbox number" required="true">


                        <br/>
                        <button class="btn btn-small btn-success pull-right calculator-price">
                            <span class="icon-refresh icon-white"></span>
                            Calculator price
                        </button>

                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <h3>Sale price</h3>
                            <table class="table-bordered  table table-striped sale-price">
                                <tr>
                                    <td>Passenger</td>
                                    <td style="text-align: center">Senior</td>
                                    <td style="text-align: center">Adult</td>
                                    <td style="text-align: center">Teen</td>
                                    <td style="text-align: center">Child 1</td>
                                    <td style="text-align: center">Child 2</td>
                                    <td style="text-align: center">Infant</td>
                                    <td style="text-align: center">Pr. Room</td>
                                </tr>
                                <tbody>
                                <tr class="base-price" role="row">
                                    <td style="text-align: center">Base price</td>
                                    <td><span column-type="senior">0</span></td>
                                    <td><span column-type="adult">0</span></td>
                                    <td><span column-type="teen">0</span></td>
                                    <td><span column-type="children1">0</span></td>
                                    <td><span column-type="children2">0</span></td>
                                    <td><span column-type="infant">0</span></td>
                                    <td><span column-type="private_room">0</span></td>

                                </tr>
                                <tr class="departure-price" role="row">
                                    <td style="text-align: center">departure price</td>
                                    <td><span column-type="senior">0</span></td>
                                    <td><span column-type="adult">0</span></td>
                                    <td><span column-type="teen">0</span></td>
                                    <td><span column-type="children1">0</span></td>
                                    <td><span column-type="children2">0</span></td>
                                    <td><span column-type="infant">0</span></td>
                                    <td><span column-type="private_room">0</span></td>

                                </tr>
                                </tbody>

                            </table>
                        </div>

                        <div class="row-fluid">
                            <div class="span2"><h4>Allow passenger</h4></div>
                            <div class="span1"><i class="icon-rightarrow"></i></div>
                            <div class="span1">
                                Senior
                                <br/>
                                <input
                                    name="allow_passenger[]" <?php echo in_array('senior', $this->departure->allow_passenger) ? 'checked' : '' ?>
                                    value="senior"
                                    type="checkbox">
                            </div>
                            <div class="span1">
                                Adult
                                <br/>
                                <input
                                    name="allow_passenger[]" <?php echo in_array('adult', $this->departure->allow_passenger) ? 'checked' : '' ?>
                                    value="adult"
                                    type="checkbox">
                            </div>
                            <div class="span1">
                                Teen
                                <br/>
                                <input
                                    name="allow_passenger[]" <?php echo in_array('teen', $this->departure->allow_passenger) ? 'checked' : '' ?>
                                    value="teen"
                                    type="checkbox">
                            </div>
                            <div class="span1">
                                Child 1
                                <br/>
                                <input
                                    name="allow_passenger[]" <?php echo in_array('child_1', $this->departure->allow_passenger) ? 'checked' : '' ?>
                                    value="child_1"
                                    type="checkbox">
                            </div>
                            <div class="span1">
                                Child 2
                                <br/>
                                <input
                                    name="allow_passenger[]" <?php echo in_array('child_2', $this->departure->allow_passenger) ? 'checked' : '' ?>
                                    value="child_2"
                                    type="checkbox">
                            </div>
                            <div class="span1">
                                Infant
                                <br/>
                                <input
                                    name="allow_passenger[]" <?php echo in_array('infant', $this->departure->allow_passenger) ? 'checked' : '' ?>
                                    value="infant"
                                    type="checkbox">
                            </div>

                            <div class="span2">

                            </div>
                            <div class="span1"></div>
                            <div class="span1"></div>
                        </div>
                        <div class="area-select-date">
                            <div class="row-fluid">
                                <div class="span12">
                                    <select disable_chosen="true" name="date_type" id="date_type">
                                        <option selected value="weekly">weekly</option>
                                        <option value="day_select">day select</option>
                                    </select>


                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span2"><h4>Weekly repeat setup</h4></div>
                                <div class="span1"><i class="icon-rightarrow"></i></div>
                                <div class="span1">
                                    MON
                                    <br/>
                                    <input
                                        name="weekly[]" <?php echo in_array('mon', $this->departure->weekly) ? 'checked' : '' ?>
                                        value="mon"
                                        type="checkbox">
                                </div>
                                <div class="span1">
                                    Tue
                                    <br/>
                                    <input
                                        name="weekly[]" <?php echo in_array('tue', $this->departure->weekly) ? 'checked' : '' ?>
                                        value="tue"
                                        type="checkbox">
                                </div>
                                <div class="span1">
                                    Wen
                                    <br/>
                                    <input
                                        name="weekly[]" <?php echo in_array('wen', $this->departure->weekly) ? 'checked' : '' ?>
                                        value="wen"
                                        type="checkbox">
                                </div>
                                <div class="span1">
                                    Thu
                                    <br/>
                                    <input
                                        name="weekly[]" <?php echo in_array('thu', $this->departure->weekly) ? 'checked' : '' ?>
                                        value="thu"
                                        type="checkbox">
                                </div>
                                <div class="span1">
                                    Fri
                                    <br/>
                                    <input
                                        name="weekly[]" <?php echo in_array('fri', $this->departure->weekly) ? 'checked' : '' ?>
                                        value="fri"
                                        type="checkbox">
                                </div>
                                <div class="span1">
                                    Sat
                                    <br/>
                                    <input
                                        name="weekly[]" <?php echo in_array('sat', $this->departure->weekly) ? 'checked' : '' ?>
                                        value="sat"
                                        type="checkbox">
                                </div>
                                <div class="span1">
                                    Sun
                                    <br/>

                                    <input
                                        name="weekly[]" <?php echo in_array('sun', $this->departure->weekly) ? 'checked' : '' ?>
                                        value="sun"
                                        type="checkbox">
                                </div>
                                <div class="span2">

                                </div>
                                <div class="span1"></div>
                                <div class="span1"></div>
                            </div>
                            <h4>Customer setting</h4>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div id="multi-calendar-departure">
                                        <input type="hidden" id="days_seleted" name="days_seleted"
                                               value="<?php echo $this->departure->days_seleted ?>">

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
            <input type="hidden" id="tsmart_departure_id" name="tsmart_departure_id"
                   value="0"/>
            <input type="hidden" id="published" name="published"
                   value="1"/>

            <?php echo $this->addStandardHiddenToForm('departure', 'ajax_save_departure_item'); ?>
        </form>
    </div>


<?php AdminUIHelper::endAdminArea(); ?>