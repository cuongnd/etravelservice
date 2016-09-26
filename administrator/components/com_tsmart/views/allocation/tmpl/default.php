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

$doc->addScript(JUri::root() . '/media/system/js/datepicker/js/datepicker.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-dateFormat-master/src/jquery.dateFormat.js');
$doc->addScript(JUri::root() . '/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js');
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


$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_departure_default.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/base.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/datepicker/css/clean.css');
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
                            <?php echo $this->sort('currency_name', 'Tour name'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Service class'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Departure date'); ?>
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
                            <?php echo $this->sort('currency_name', 'departure'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Discount'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Sale period'); ?>
                        </th>
                        <th>
                            <?php echo $this->sort('currency_name', 'Asign'); ?>
                        </th>
                        <th width="10">
                            <?php echo tsmText::_('com_tsmart_PUBLISHED'); ?>
                        </th>
                        <th colspan="2">Action</th>
                        <?php /*	<th width="10">
				<?php echo vmText::_('com_tsmart_SHARED'); ?>
			</th> */ ?>
                    </tr>
                    </thead>
                    <?php
                    $k = 0;
                    for ($i = 0, $n = count($this->departures); $i < $n; $i++) {
                        $row = $this->departures[$i];
                        $checked = JHtml::_('grid.id', $i, $row->tsmart_departure_id);
                        $published = $this->gridPublished($row, $i);

                        $editlink = JROUTE::_('index.php?option=com_tsmart&view=departure&task=edit&cid[]=' . $row->tsmart_departure_id);
                        ?>
                        <tr role="row" class="row<?php echo $k; ?>"
                            data-tsmart_departure_id="<?php echo $row->tsmart_departure_id ?>"
                            data-tour_id="<?php echo $item->tour_id ?>">
                            <td class="admin-checkbox">
                                <?php echo $checked; ?>
                            </td>
                            <td>
                                <a href="<?php echo $editlink ?>"><?php echo $row->departure_name ?></a>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Tour name'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Service class'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Departure date'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Min-max'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Avaibility'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Tour price'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'departure'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Discount'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Sale period'); ?>
                            </td>
                            <td>
                                <?php echo $this->sort('currency_name', 'Asign'); ?>
                            </td>
                            <td align="center">
                                <?php echo $published; ?>
                            </td>
                            <td><a href="javascript:void(0)" class="edit-departure">
                                    <span class="icon-edit icon-white"></span>
                                </a>
                                <a href="javascript:void(0)" class=" publish-departure">
                                    <span
                                        class="icon-<?php echo $departure_price->published ? 'publish' : 'unpublish' ?> icon-white"></span>
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
                        <select id="tour_id" name="tour_id" style="width: 300px">
                            <?php foreach ($this->list_tour as $tour) { ?>
                                <option
                                    value="<?php echo $tour->tsmart_product_id ?>"><?php echo $tour->product_name ?></option>
                            <?php } ?>
                        </select>

                        <label>Select Service Class</label>
                        <select id="tour_service_class_id" name="tour_service_class_id" style="width: 300px">
                            <?php foreach ($this->list_tour_class as $tour_service_class) { ?>
                                <option
                                    value="<?php echo $tour_service_class->tsmart_service_class_id ?>"><?php echo $tour_service_class->service_class_name ?></option>
                            <?php } ?>
                        </select>
                        <label>Departure name</label>
                        <input type="text" size="16"
                               value="<?php echo $this->departure->departure_name ?>"
                               id="departure_name" style="width: 100px"
                               name="departure_name"
                               class="inputbox">

                        <label>G-Guarantee</label>
                        <input type="number" size="16"
                               value="<?php echo $this->departure->g_guarantee ?>"
                               id="g_guarantee" name="g_guarantee"
                               class="inputbox number" equired="true">
                        <label>L-limit space</label>

                        <input type="number" size="16"
                               value="<?php echo $this->departure->limited_space ?>"
                               id="limited_space" name="limited_space"
                               class="inputbox number" equired="true">



                    </div>
                    <div class="span6">
                        <label>Setup space</label>
                        <input type="number" size="7" value="<?php echo $this->departure->min_space ?>"
                               id="min_space" name="min_space" class="inputbox number">
                        To
                        <input type="number" size="7" value="<?php echo $this->departure->max_space ?>"
                               id="max_space" name="max_space" class="inputbox number">
                        <label>Sale periol</label>
                        <?php echo vmJsApi::jDate($this->departure->sale_period_open, 'sale_period_open'); ?>
                        To
                        <?php echo vmJsApi::jDate($this->departure->sale_period_close, 'sale_period_close'); ?>
                        <label>Vaild period</label>
                        <?php echo vmJsApi::jDate($this->departure->vail_period_from, 'vail_period_from'); ?>
                        To
                        <?php echo vmJsApi::jDate($this->departure->vail_period_to, 'vail_period_to'); ?>
                        <label>Departure Note</label>
                        <textarea>
                            <?php echo $this->departure->departure_note ?>
                        </textarea>
                        <br/>
                        <a href="javascript:void(0)" class="btn btn-small btn-success pull-right calculator-price">
                            <span class="icon-refresh icon-white"></span>
                            Calculator price
                        </a>

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
                                <input name="weekly[]" <?php echo $this->departure->mon == 1 ? 'checked' : '' ?>
                                       value="1"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Teen
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->tue == 1 ? 'checked' : '' ?>
                                       value="2"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Child 1
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->wen == 1 ? 'checked' : '' ?>
                                       value="3"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Child 2
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->thu == 1 ? 'checked' : '' ?>
                                       value="4"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Infant
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->fri == 1 ? 'checked' : '' ?>
                                       value="5"
                                       type="checkbox">
                            </div>

                            <div class="span2">

                            </div>
                            <div class="span1"></div>
                            <div class="span1"></div>
                        </div>
                        <div class="row-fluid">
                            <div class="span2"><h4>Weekly repeat setup</h4></div>
                            <div class="span1"><i class="icon-rightarrow"></i></div>
                            <div class="span1">
                                MON
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->mon == 1 ? 'checked' : '' ?>
                                       value="1"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Tue
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->tue == 1 ? 'checked' : '' ?>
                                       value="2"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Wen
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->wen == 1 ? 'checked' : '' ?>
                                       value="3"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Thu
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->thu == 1 ? 'checked' : '' ?>
                                       value="4"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Fri
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->fri == 1 ? 'checked' : '' ?>
                                       value="5"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Sat
                                <br/>
                                <input name="weekly[]" <?php echo $this->departure->sat == 1 ? 'checked' : '' ?>
                                       value="6"
                                       type="checkbox">
                            </div>
                            <div class="span1">
                                Sun
                                <br/>
                                <input <?php echo $this->departure->sun == 1 ? 'checked' : '' ?> value="1"
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
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>
    </div>


<?php AdminUIHelper::endAdminArea(); ?>