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
require_once JPATH_ROOT.DS.'administrator/components/com_tsmart/helpers/tsmutility.php';
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
                            <fieldset class="trip-detail">
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
                            <fieldset class="departure-date">
                                <legend><?php echo JText::_('departure date') ?></legend>
                                <?php echo VmHTML::row_control('input',JText::_('Departure name'),'departure_name',$this->departure->departure_name,'class="required"'); ?>

                                <?php echo VmHTML::row_control('text_view', JText::_('Departure code'),$this->departure->departure_code, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('text_view', JText::_('Departure date'),$this->departure->departure_date, 'class="required icon-date"'); ?>

                                <?php echo VmHTML::row_control('text_view', JText::_('Service class'),$this->departure->service_class_name, 'class="required"'); ?>
                                <?php echo VmHTML::row_control('range_of_integer', JText::_('Min-Max space'),'min_space','max_space', $this->departure->min_space,$this->departure->max_space); ?>
                                <?php echo VmHTML::row_control('range_of_integer', JText::_('Open close sale'),'min_space','max_space', $this->departure->min_space,$this->departure->max_space); ?>
                                <?php echo VmHTML::row_control('range_of_integer', JText::_('G-L space'),'g_guarantee','limited_space', $this->departure->g_guarantee,$this->departure->limited_space); ?>


                            </fieldset>
                        </div>
                        <div class="span4">
                            <fieldset >
                                <legend><?php echo JText::_('Hold seat') ?></legend>
                                <div class="hold-seat">
                                    <div class="line-1 row-fluid">
                                        <div class="pull-left"><?php echo $this->departure->hold_seat?JText::_('Accepted'):JText::_('NotAccepted') ?></div>
                                        <div class="pull-right"><?php echo $this->departure->hold_seat?JText::sprintf('Time: %s hours',$this->departure->hold_seat_hours):JText::_('None') ?></div>
                                    </div>
                                    <div class="line-2 row-fluid">
                                        <div class="pull-left"><?php echo $this->departure->hold_seat?JText::sprintf('Hold seat'):JText::_('None') ?></div>
                                        <div class="pull-right"><?php echo $this->departure->hold_seat?JText::sprintf('Number: %s persons',4):JText::_('None') ?></div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="">
                                <legend><?php echo JText::_('Payment rule') ?></legend>
                                <div class="payment-rule">
                                    <div class="line row-fluid">
                                        <?php
                                        $amount=$this->departure->amount;
                                        $amount=$this->departure->deposit_amount_type=="percent"?"$amount%":"US$ $amount";
                                        ?>
                                        <div class="pull-left"><?php echo JText::sprintf('Deposit: %s',$amount) ?></div>
                                        <div class="pull-right"><?php echo JText::sprintf('Cancel: %s days',$this->departure->cancellation_of_day) ?></div>
                                    </div>
                                    <div class="line row-fluid">
                                        <div class="pull-left"><?php echo JText::sprintf('Balance 1: %s',$this->departure->percent_balance_of_day_1) ?></div>
                                        <div class="pull-right"><?php echo JText::sprintf('Date: %s days',$this->departure->balance_of_day_1) ?></div>
                                    </div>
                                    <div class="line row-fluid">
                                        <div class="pull-left"><?php echo JText::sprintf('Balance 2: %s',$this->departure->percent_balance_of_day_2) ?></div>
                                        <div class="pull-right"><?php echo JText::sprintf('Date: %s days',$this->departure->balance_of_day_2) ?></div>
                                    </div>
                                    <div class="line row-fluid">
                                        <div class="pull-left"><?php echo JText::sprintf('Balance 3: %s',$this->departure->percent_balance_of_day_3) ?></div>
                                        <div class="pull-right"><?php echo JText::sprintf('Date: %s days',$this->departure->balance_of_day_3) ?></div>
                                    </div>
                                </div>

                            </fieldset>
                            <fieldset >
                                <legend><?php echo JText::_('Allow passenger') ?></legend>
                                <div class="allow-passenger">
                                    <?php echo VmHTML::allow_passenger('allow_passenger', explode(',',$this->departure->allow_passenger), '', 3); ?>
                                </div>
                            </fieldset>

                        </div>
                    </div>

                </div>

                <div class="depature-information-seat-price form-horizontal">
                    <h3 class="title"><?php echo JText::_('Seat and price management') ?></h3>
                    <div class="price-status">
                    <div class="row-fluid ">
                        <div class="span6">
                            <ul class="list-price">
                                <li class="price"><span class="title head">&nbsp;</span> <span class="sale-price head"><?php echo JText::_('sale price') ?></span> <span class="discount-price head"><?php echo JText::_('discount price') ?></span></li>
                                <li class="price"><span class="title"><?php echo JText::_('price for adult') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                                <li class="price"><span class="title"><?php echo JText::_('price for senior') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                                <li class="price"><span class="title"><?php echo JText::_('price for teener') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                                <li class="price"><span class="title"><?php echo JText::_('price for child 6-11 years') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                                <li class="price"><span class="title"><?php echo JText::_('price for child 2-5 years') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                                <li class="price"><span class="title"><?php echo JText::_('price for child <2 years') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                                <li class="price"><span class="title"><?php echo JText::_('Private room') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                                <li class="price"><span class="title"><?php echo JText::_('Extra bed') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                                <li class="price"><span class="title"><?php echo JText::_('room fee include child 6-11 years price') ?></span> <span class="sale-price">US$ 1500</span> <span class="discount-price">US$ 1500</span></li>
                            </ul>
                        </div>
                        <div class="span6">
                            <?php echo VmHTML::row_control('number_state', JText::_('Open space'),'open_space',$this->departure->g_guarantee,$this->departure->limited_space,$this->departure->limited_space,'#990100','#ff9900'); ?>
                            <?php echo VmHTML::row_control('number_state', JText::_('Sold out'),'sold_out',0,$this->departure->limited_space,14,'#0033fe','#ccc'); ?>
                            <?php echo VmHTML::row_control('number_state', JText::_('Hold seat'),'hold_seat',0,$this->departure->limited_space,2,'#0033fe','#ccc'); ?>
                            <?php echo VmHTML::row_control('number_state', JText::_('Request'),'request',0,$this->departure->limited_space,5,'#ff9900','#ccc'); ?>

                        </div>
                    </div>
                    </div>
                    <div class="promotion-info form-vertical">
                        <div class="row-fluid ">
                            <div class="span12">
                                <?php echo VmHTML::row_control('editor', 'Operator info', 'operator_info', $this->item->operator_info,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                            </div>
                        </div>
                    </div>
                    <div class="departure-note form-vertical">
                        <div class="row-fluid ">
                            <div class="span12">
                                <?php echo VmHTML::row_control('editor', 'Departure note', 'departure_note', $this->item->departure_note,'40%', 20, 10, 20, tsmConfig::$list_editor_plugin_disable); ?>
                            </div>
                        </div>
                    </div>
                    <div class="controls-center">
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

                </div>


            </div>
            <input type="hidden" id="tsmart_departure_id" name="tsmart_departure_id"
                   value="0"/>
            <input type="hidden" id="tsmart_departure_parent_id" name="tsmart_departure_parent_id"
                   value="0"/>
            <input type="hidden" id="published" name="published"
                   value="1"/>

            <?php echo $this->addStandardHiddenToForm('departure', 'ajax_save_departure_item'); ?>

        </form>
    </div>


<?php AdminUIHelper::endAdminArea(); ?>