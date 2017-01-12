<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addLessStyleSheet(JUri::root() . '/components/com_tsmart/assets/less/view_productdetail_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
JHtml::_('behavior.formvalidation');
$doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/plugin/Animated-jQuery-Modal/src/js/dreyanim.js');
$doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/plugin/Animated-jQuery-Modal/src/js/dreymodal.js');
$doc->addStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/plugin/Animated-jQuery-Modal/src/css/dreyanim.css');
$doc->addStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/plugin/Animated-jQuery-Modal/src/css/dreymodal.css');
$doc->addScript(JUri::root() . '/components/com_tsmart/assets/js/view_productdetails_default.js');
$app = JFactory::getApplication();
$input = $app->input;
$tsmart_product_id = $input->getInt('tsmart_product_id', 0);
require_once JPATH_ROOT . '/administrator/components/com_tsmart/helpers/tsmgroupsize.php';
?>
</div>
<div class="view-productdetails-default">
    <!-- Zozo Tabs Start-->
    <div class="row">
        <div class="span9">
            <div id="tabbed-nav">
                <!-- Tab Navigation Menu -->
                <ul>
                    <li><a>Overview</a></li>
                    <li><a>Features</a></li>
                    <li><a>Docs</a></li>
                    <li><a>Themes</a></li>
                    <li><a>Purchase</a></li>
                </ul>
                <!-- Content container -->
                <div>
                    <!-- Overview -->
                    <div class="content content-overview">
                        <form
                            action="<?php echo JRoute::_('index.php?option=com_tsmart&view=productdetails&tsmart_product_id=' . $tsmart_product_id) ?>"
                            method="post"
                            id="tour_price" name="tour_price">
                            <div class="row header-content">
                                <div class="span10 product-name">
                                    <h2><?php echo $this->product->product_name ?></h2>
                                    <h3></h3>
                                </div>
                                <div class="span2 request">
                                    <a href="javascript:void(0)"><span
                                            title=""
                                            class="icon-file-2  hasTooltip"
                                            data-original-title="Request this tour"></span>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <fieldset class="tour-border">
                                            <legend
                                                class="tour-border"><?php echo JText::_('Get best price for your travel date') ?></legend>
                                            <div class="departure-date-select">
                                                <?php echo VmHTML::select_number_passenger('filter_total_passenger_from_12_years_old', '', 1, 50, $this->state->get('filter.total_passenger_from_12_years_old'), ' class="required" '); ?>
                                                <?php echo VmHTML::select_number_passenger('filter_total_passenger_under_12_years_old', 'Passenger under 12 years old', 1, 50, $this->state->get('filter.total_passenger_under_12_years_old'), ' class="required" '); ?>
                                                <?php if ($this->product->price_type != tsmGroupSize::FLAT_PRICE) {
                                                    echo VmHTML::select_date('filter_start_date', $this->state->get('filter.start_date'), '', '', '', '', ' required ');
                                                } else {
                                                    echo VmHTML::select_month('filter_month', $this->state->get('filter.month'), '', '', '', '', ' required ');
                                                }
                                                ?>
                                                <div class="btn-go">
                                                    <?php echo VmHTML::input_button('submit', '<div class="check-price">' . JText::_('Check price') . '</div><div class="go">' . JText::_('Go') . '</div>'); ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <input name="option" value="com_tsmart" type="hidden">
                            <input name="controller" value="productdetails" type="hidden">
                            <input type="hidden" value="productdetails" name="view">
                            <input name="tsmart_product_id" value="<?php echo $tsmart_product_id ?>" type="hidden">
                            <input name="task" value="" type="hidden">
                        </form>
                        <form
                            action="<?php echo JRoute::_('index.php?option=com_tsmart&view=productdetails&tsmart_product_id=' . $tsmart_product_id) ?>"
                            method="post"
                            id="tour_price" name="tour_price">
                            <div class="table table-trip">
                                <div class="row header">
                                    <div class="col-lg-12">
                                        <div class="hidden-xxxs">
                                            <div class="row">
                                                <div class="offset9 span2">
                                                    <div><?php echo JText::_('Dates listing') ?></div>
                                                    <a class="pull-left" href="javascript:void(0)"><span title=""
                                                                                                         class="icon-arrow-down-3 "></span></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-1">
                                                </div>
                                                <div class="col-lg-3">
                                                    <?php echo JText::_('tour start/end date') ?>
                                                </div>
                                                <div class="col-lg-2">
                                                    <?php echo JText::_('Tour class') ?>
                                                </div>
                                                <div class="col-lg-2">
                                                    <?php echo $this->start_date ? JText::_('Trip price') : JText::_('Lowest price') ?>
                                                </div>
                                                <div class="col-lg-2">
                                                    <?php echo JText::_('Space status') ?>
                                                </div>
                                                <div class="col-lg-2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hidden-tablet-desktop">
                                            <div class="row ">
                                                <div class="col-xxxs-6"><?php echo JText::_('Date & price') ?></div>
                                                <div class="col-xxxs-6"><span><i
                                                            class="glyphicon glyphicon-chevron-down"></i><?php echo JText::_('Open') ?></span>
                                                    | <span><i
                                                            class="glyphicon glyphicon-chevron-down"></i><?php echo JText::_('Close') ?></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xxxs-1">&nbsp;
                                                </div>
                                                <div class="col-xxxs-8">
                                                    <?php echo JText::_('Tour start/end date') ?>
                                                </div>
                                                <div class="col-xxxs-3">
                                                    <?php echo $this->start_date ? JText::_('Trip price') : JText::_('Lowest price') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row body">
                                    <div class="col-lg-12">
                                        <?php for ($i = 0; $i < count($this->list_trip); $i++) { ?>
                                            <?php
                                            $trip = $this->list_trip[$i];
                                            $list_destination = $trip->list_destination;
                                            $filter_start_date = $this->state->get('filter.start_date');
                                            $start_date = JFactory::getDate($filter_start_date);
                                            $total_day = $trip->total_day - 1;
                                            $total_day = $total_day ? $total_day : 0;
                                            $list_destination = explode(';', $list_destination);
                                            $des_start = reset($list_destination);
                                            $des_finish = end($list_destination);
                                            $end_date = clone $start_date;
                                            $end_date->modify("+$total_day day");
                                            ?>
                                            <div data-tsmart_price_id="<?php echo $trip->tsmart_price_id ?>"
                                                 class="row item">
                                                <div class="col-lg-12">
                                                    <div class="row header-item">
                                                        <div class="col-lg-1 col-xxxs-1 person">
                                                            <span title="" class="travel-icon">n</span>
                                                        </div>
                                                        <div class="col-lg-3 col-xxxs-8 departure-date">
                                                            <?php echo $filter_start_date ? JHtml::_('date', $filter_start_date, tsmConfig::$date_format) . ' - ' . JHtml::_('date', $end_date, tsmConfig::$date_format) : JText::_('Date not selected') ?>
                                                            <div
                                                                class="hidden-tablet-desktop"><?php echo $trip->service_class_name ?></div>
                                                        </div>
                                                        <div class="col-lg-2 hidden-xxxs service-class ">
                                                            <?php echo $trip->service_class_name ?>
                                                        </div>
                                                        <div class=" col-lg-2 hidden-xxxs price ">
                                                            <span class="price"
                                                                  data-a-sign="US$ "><?php echo $trip->sale_price_adult ?></span>
                                                        </div>
                                                        <div class="col-lg-2 hidden-xxxs service-class-price">
                                                            <?php echo JText::_('trip price') ?>
                                                        </div>
                                                        <div class="col-lg-2 col-xxxs-3 tour_state">
                                                            <?php if ($trip->tour_state == 1) { ?>
                                                                <?php echo JText::_('Available') ?>
                                                            <?php } else { ?>
                                                                <?php echo JText::_('Request') ?>
                                                            <?php } ?>
                                                            <div class="hidden-tablet-desktop">
                                                                <span class="price"
                                                                      data-a-sign="US$ "><?php echo $trip->sale_price_adult ?></span>
                                                                <a href="javascript:void(0)"
                                                                   class="action-collapse btn-collapse <?php echo $this->state->get('filter.start_date') ? '' : ' required-select-date ' ?>" <?php echo $this->state->get('filter.start_date') ? ' data-toggle="collapse" ' : '' ?>
                                                                   data-target="#trip-<?php echo $i ?>"><span
                                                                        title=""
                                                                        class="glyphicon glyphicon-chevron-down  hasTooltip"
                                                                        data-original-title="Detail"></span></a>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="xxxs-collapse hidden-tablet-desktop col-xxxs-3 icon">
                                                            <a href="javascript:void(0)"
                                                               class="action-collapse btn-collapse <?php echo $this->state->get('filter.start_date') ? '' : ' required-select-date ' ?>" <?php echo $this->state->get('filter.start_date') ? ' data-toggle="collapse" ' : '' ?>
                                                               data-target="#trip-<?php echo $i ?>"><span
                                                                    title=""
                                                                    class="glyphicon glyphicon-chevron-down  hasTooltip"
                                                                    data-original-title="Detail"></span></a>
                                                        </div>
                                                        <div class="col-lg-2 hidden-xxxs icon">
                                                            <a href="javascript:void(0)"
                                                               class="action-collapse btn-collapse <?php echo $this->state->get('filter.start_date') ? '' : ' required-select-date ' ?>" <?php echo $this->state->get('filter.start_date') ? ' data-toggle="collapse" ' : '' ?>
                                                               data-target="#trip-<?php echo $i ?>"><span
                                                                    title=""
                                                                    class="glyphicon glyphicon-chevron-down  hasTooltip"
                                                                    data-original-title="Detail"></span></a>
                                                        </div>
                                                        <div class="triangle">
                                                        </div>
                                                    </div>
                                                    <div id="trip-<?php echo $i ?>"
                                                         class="row body-item collapse">
                                                        <?php
                                                        ?>
                                                        <div class="col-lg-6 item-body-left">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-xxxs-12">
                                                                    <div class="pull-left start">
                                                                        <div><span
                                                                                class="text-start"><?php echo JText::_('Start') ?></span>
                                                                        </div>
                                                                        <div><?php echo JHtml::_('date', $this->state->get('filter.start_date'), tsmConfig::$date_format) ?></div>
                                                                        <div><?php echo $des_start ?></div>
                                                                    </div>
                                                                    <div class="pull-left finish">
                                                                        <div><span
                                                                                class="text-finish"><?php echo JText::_('Finish') ?></span>
                                                                        </div>
                                                                        <div><?php echo JHtml::_('date', $end_date, tsmConfig::$date_format) ?></div>
                                                                        <div><?php echo $des_finish ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-xxxs-12">
                                                                    <table class="list price">
                                                                        <?php if ($trip->sale_price_senior != 0) { ?>
                                                                            <tr>
                                                                                <td class=""><?php echo JText::_('Senior') ?>:</td>
                                                                                <td class=""><span
                                                                                        data-a-sign="US$ "><?php echo $trip->sale_price_senior ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <?php if ($trip->sale_price_adult != 0) { ?>
                                                                            <tr>
                                                                                <td><?php echo JText::_('Adult') ?>:</td>
                                                                                <td><span
                                                                                        data-a-sign="US$ "><?php echo $trip->sale_price_adult ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <?php if ($trip->sale_price_teen != 0) { ?>
                                                                            <tr>
                                                                                <td><?php echo JText::_('Teener') ?>:</td>
                                                                                <td><span
                                                                                        data-a-sign="US$ "><?php echo $trip->sale_price_teen ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <?php if ($trip->sale_price_children1 != 0) { ?>
                                                                            <tr>
                                                                                <td><?php echo JText::_('Child 6-11') ?>:</td>
                                                                                <td><span
                                                                                        data-a-sign="US$ "><?php echo $trip->sale_price_children1 ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <?php if ($trip->sale_price_children2 != 0) { ?>
                                                                            <tr>
                                                                                <td><?php echo JText::_('Child 2-5') ?>:</td>
                                                                                <td><span
                                                                                        data-a-sign="US$ "><?php echo $trip->sale_price_children2 ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <?php if ($trip->sale_price_infant != 0) { ?>
                                                                            <tr>
                                                                                <td><?php echo JText::_('Infant') ?>:</td>
                                                                                <td><span
                                                                                        data-a-sign="US$ "><?php echo $trip->sale_price_infant ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 item-body-right    area-booking">
                                                            <div class="row area-text">
                                                                <div class="col-lg-6 text-left"
                                                                     style="text-align: center">
                                                                    <?php echo JText::_('total price per person based on passenger age and tour date') ?>
                                                                </div>
                                                                <div class="col-lg-6 text-right"
                                                                     style="text-align: center">
                                                                    <?php echo JText::_('Select private room+US$ 300/person') ?>
                                                                </div>
                                                            </div>
                                                            <div class="row area-button">
                                                                <div class="col-lg-12" style="text-align: center">
                                                                    <?php if ($trip->tour_state == 1) { ?>
                                                                        <button class="btn btn-primary book-now"
                                                                                type="submit"><?php echo JText::_('Available || Book now') ?></button>
                                                                    <?php } else { ?>
                                                                        <button class="btn btn-primary book-now"
                                                                                type="submit"><?php echo JText::_('Send request') ?></button>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <input name="option" value="com_tsmart" type="hidden">
                            <input name="controller" value="trip" type="hidden">
                            <input name="booking_date" value="" type="hidden">
                            <input type="hidden" value="trip" name="view">
                            <input name="tsmart_price_id" value="0" type="hidden">
                            <input name="task" value="" type="hidden">
                        </form>
                    </div>
                    <!-- Features -->
                    <div>
                        <h4>Features</h4>
                        <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                            was born and I will give you a complete account of the system, and expound the actual
                            teachings of the great explorer</p>
                    </div>
                    <!-- Docs -->
                    <div>
                        <h4>Docs</h4>
                        <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                            was born and I will give you a complete account of the system, and expound the actual
                            teachings of the great explorer</p>
                    </div>
                    <!-- Themes -->
                    <div>
                        <h4>Themes</h4>
                        <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                            was born and I will give you a complete account of the system, and expound the actual
                            teachings of the great explorer</p>
                    </div>
                    <!-- Purchase -->
                    <div>
                        <h4>Purchase</h4>
                        <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                            was born and I will give you a complete account of the system, and expound the actual
                            teachings of the great explorer</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
        </div>
    </div>
    <!-- Zozo Tabs End-->
</div>
<?php
$js_content = '';
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-productdetails-default').view_productdetails_default({});
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
?>
