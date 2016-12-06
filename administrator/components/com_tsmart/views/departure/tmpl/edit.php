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
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/view_departure_edit.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/multi_calendar_date_picker/css/base.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/multi_calendar_date_picker/css/clean.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/tooltipster-master/css/tooltipster.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/tooltipster-master/css/themes/tooltipster-light.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-daterangepicker-master/daterangepicker-bs2.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css');
$doc->addLessStyleSheet(JUri::root() . '/administrator/components/com_tsmart/assets/less/view_departure_edit.less');

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
require_once JPATH_ROOT.DS.'administrator/components/com_tsmart/helpers/utility.php';
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
AdminUIHelper::startAdminArea($this);

?>
    <div class="view-departure-default">
        <form action="index.php" method="post" class="departure-edit-form" name="adminForm" id="adminForm">
            <div class="col50">
                <div class="depature-information form-horizontal">
                    <h3 class="title"><?php echo JText::_('Departure information') ?></h3>
                    <div class="row-fluid ">
                        <div class="span4">
                            <fieldset>
                                <legend><?php echo JText::_('Trip detail') ?></legend>
                                <?php echo VmHTML::row_control('text_view', JText::_('Trip name'),$this->departure->product_name, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', JText::_('Trip code'),$this->departure->product_code, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', JText::_('Trip style'),$this->departure->tour_style_name, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', JText::_('Start city'),$this->departure->start_city_name, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', JText::_('End city'),$this->departure->end_city_name, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', JText::_('Physical grade'),$this->departure->physicalgrade_name, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', JText::_('Tour Operator'),$this->departure->tour_operator, 'class="required"'); ?>
                            </fieldset>

                        </div>
                        <div class="span4">
                            <fieldset>
                                <legend><?php echo JText::_('departure date') ?></legend>
                                <?php echo VmHTML::row_control('input',JText::_('Departure name'),'departure_name',$this->departure->departure_name,'class="required"'); ?>

                                <?php echo VmHTML::row_control('text_view', JText::_('Departure code'),$this->departure->departure_code, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('select_date',JText::_('Departure date'),'departure_date',$this->departure->service_class_name,'mm/dd/yy'); ?>

                                <?php echo VmHTML::row_control('text_view', JText::_('Service class'),$this->departure->service_class_name, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view_from_to', JText::_('Min-Max space'), $this->departure->min_space,$this->departure->max_space,'to', 'style="width:65px"',' style="width:65px" '); ?>
                                <?php echo VmHTML::row_control('text_view_from_to', JText::_('Open close sale'), $this->departure->min_space,$this->departure->max_space,'to', 'style="width:65px"',' style="width:65px" '); ?>
                                <?php echo VmHTML::row_control('text_view_from_to', JText::_('G-L space'), $this->departure->g_guarantee,$this->departure->limited_space,'to', 'style="width:65px"',' style="width:65px" '); ?>


                            </fieldset>
                        </div>
                        <div class="span4">
                            <fieldset >
                                <legend><?php echo JText::_('Hold seat') ?></legend>
                                <?php echo VmHTML::view_list(array($view->product->tour_section)); ?>
                            </fieldset>
                            <fieldset class="list_tour_service_class">
                                <legend><?php echo JText::_('Payment rule') ?></legend>
                                <?php echo VmHTML::view_list($view->product->list_tour_service_class); ?>
                            </fieldset>
                        </div>
                    </div>

                </div>

                <div class="depature-information-seat-price form-horizontal">
                    <h3 class="title"><?php echo JText::_('General information edit') ?></h3>
                    <div class="row-fluid ">
                        <div class="span4">
                            <fieldset>
                                <legend>General</legend>
                                <?php echo VmHTML::row_control('text_view', 'Tour name', $view->product->product_name.'('.$view->product->product_code.')', 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', 'Tour length', $view->product->tour_length, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', 'Tour countries', $view->product->list_country, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', 'start city', $view->product->start_city, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', 'end city', $view->product->end_city, 'class="required"'); ?>
                            </fieldset>

                        </div>
                        <div class="span4">
                            <fieldset>
                                <legend>Particularity</legend>

                                <?php echo VmHTML::row_control('text_view', 'Tour type', $view->product->tour_type, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', 'Tour stype', $view->product->tour_style, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', 'Difficulty grade', $view->product->physicalgrade, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view_from_to', 'Min Max pers', $view->product->min_person,$view->product->max_person,$text='to', 'style="width:65px"',' style="width:65px" '); ?>
                                <?php echo VmHTML::row_control('text_view_from_to', 'Min Max age', $view->product->min_age,$view->product->max_age,$text='to', 'style="width:65px"',' style="width:65px" '); ?>


                            </fieldset>
                        </div>
                        <div class="span4">
                            <fieldset >
                                <legend>Tour Section</legend>
                                <?php echo VmHTML::view_list(array($view->product->tour_section)); ?>
                            </fieldset>
                            <fieldset class="list_tour_service_class">
                                <legend>Service class</legend>
                                <?php echo VmHTML::view_list($view->product->list_tour_service_class); ?>
                            </fieldset>
                        </div>
                    </div>

                </div>


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



                    </div>
                </div>
            </div>
            <input type="hidden" id="tsmart_departure_id" name="tsmart_departure_id"
                   value="0"/>
            <input type="hidden" id="tsmart_departure_parent_id" name="tsmart_departure_parent_id"
                   value="0"/>
            <input type="hidden" id="published" name="published"
                   value="1"/>

            <?php echo $this->addStandardHiddenToForm('departure', 'ajax_save_departure_item'); ?>
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

        </form>
    </div>


<?php AdminUIHelper::endAdminArea(); ?>