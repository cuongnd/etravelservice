<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addLessStyleSheet(JUri::root() . '/components/com_virtuemart/assets/less/view_productdetail_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
JHtml::_('behavior.formvalidation');
$doc->addScript(JUri::root() . '/components/com_virtuemart/assets/js/view_productdetails_default.js');
$app = JFactory::getApplication();
$input = $app->input;
$virtuemart_product_id = $input->getInt('virtuemart_product_id', 0);
require_once JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/vmgroupsize.php';
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
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
<div class="view-productdetails-default">
    <!-- Zozo Tabs Start-->
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
                    action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $virtuemart_product_id) ?>" method="post"
                    id="tour_price"  name="tour_price">
                    <div class="row-fluid header-content">
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
                    <div class="row-fluid">
                        <div class="span12">
                            <fieldset class="tour-border">
                                <legend
                                    class="tour-border"><?php echo JText::_('Get best price for your travel date') ?></legend>
                                <?php echo VmHTML::select_number_passenger('filter_total_passenger_from_12_years_old', '', 1, 50, $this->state->get('filter.total_passenger_from_12_years_old'), ' class="required" '); ?>
                                <?php echo VmHTML::select_number_passenger('filter_total_passenger_under_12_years_old', 'Passenger under 12 years old', 1, 50,$this->state->get('filter.total_passenger_under_12_years_old'), ' class="required" '); ?>
                                <?php if($this->product->price_type!=vmGroupSize::FLAT_PRICE) {
                                    echo VmHTML::select_date('filter_start_date', $this->state->get('filter.start_date'), '', '', '', '', ' required ');
                                }else{
                                    echo VmHTML::select_month('filter_month', $this->state->get('filter.month'), '', '', '', '', ' required ');
                                }
                                ?>

                                <div class="btn-go">
                                    <?php echo VmHTML::input_button('submit', 'Go'); ?>
                                </div>
                            </fieldset>

                        </div>
                    </div>
                    <input name="option" value="com_virtuemart" type="hidden">
                    <input name="controller" value="productdetails" type="hidden">
                    <input type="hidden" value="productdetails" name="view">
                    <input name="virtuemart_product_id" value="<?php echo $virtuemart_product_id ?>" type="hidden">
                    <input name="task" value="" type="hidden">
                </form>
                <form
                    action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $virtuemart_product_id) ?>" method="post"
                    id="tour_price" name="tour_price">

                    <div class="table table-trip">
                        <div class="row-fluid header">
                            <div class="span12">
                                <div class="row-fluid">
                                    <div class="offset9 span2">
                                        <div><?php echo JText::_('Dates listing') ?></div>
                                        <a class="pull-left" href="javascript:void(0)"><span title=""
                                                                                             class="icon-arrow-down-3 "></span></a>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span1">

                                    </div>
                                    <div class="span3">
                                        <?php echo JText::_('tour start/end date') ?>
                                    </div>
                                    <div class="span2">
                                        <?php echo JText::_('Tour class') ?>
                                    </div>
                                    <div class="span2">
                                        <?php echo JText::_('Lowest price') ?>
                                    </div>
                                    <div class="span2">
                                        <?php echo JText::_('Space status') ?>
                                    </div>
                                    <div class="span2">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row-fluid body">
                            <div class="span12">
                                <?php for ($i = 0; $i < count($this->list_trip); $i++) { ?>
                                    <?php
                                    $trip = $this->list_trip[$i];
                                    $list_destination=$trip->list_destination;
                                    $filter_start_date=$this->state->get('filter.start_date');
                                    $start_date=JFactory::getDate($filter_start_date);
                                    $total_day=$trip->total_day-1;

                                    $total_day=$total_day?$total_day:0;

                                    ?>
                                    <div data-virtuemart_price_id="<?php echo $trip->virtuemart_price_id ?>" class="row-fluid item">

                                        <div class="span12">
                                            <div class="row-fluid header-item">
                                                <div class="span1 person" >
                                                    <span title="" class="travel-icon">n</span>
                                                </div>
                                                <div class="span3">
                                                    <?php echo $filter_start_date?JHtml::_('date',$filter_start_date):JText::_('Date not selected') ?>
                                                </div>
                                                <div class="span2 service-class ">
                                                    <?php echo $trip->service_class_name ?>
                                                </div>
                                                <div class="span2 price ">
                                                    <span class="price"
                                                          data-a-sign="US$"><?php echo $trip->price_adult ?></span>
                                                </div>
                                                <div class="span4 service-class-price hide">
                                                    <?php echo $trip->service_class_name ?> <?php echo JText::_('class price') ?>
                                                </div>
                                                <div class="span2">
                                                    <?php echo JText::_('Available/Request') ?>
                                                </div>
                                                <div class="span2">
                                                    <a href="javascript:void(0)" class="btn-collapse <?php echo $this->state->get('filter.start_date')?'':' required-select-date ' ?>" <?php echo $this->state->get('filter.start_date')?' data-toggle="collapse" ':'' ?>
                                                        data-target="#trip-<?php echo $i ?>"><span
                                                            title=""
                                                            class="icon-chevron-down  hasTooltip"
                                                            data-original-title="Detail"></span></a>
                                                </div>
                                            </div>
                                            <div id="trip-<?php echo $i ?>" class="row-fluid body-item collapse">
                                                <?php

                                                $list_destination=explode(';',$list_destination);
                                                $des_start=reset($list_destination);
                                                $des_finish=end($list_destination);
                                                ?>
                                                <div class="span2 start">
                                                    <div><span class="text-start"><?php echo JText::_('Start') ?></span></div>
                                                    <div><?php echo JHtml::_('date',$this->state->get('filter.start_date')) ?></div>
                                                    <div><?php echo $des_start ?></div>
                                                </div>
                                                <div class="span2 finish">
                                                    <div><span class="text-finish"><?php echo JText::_('Finish') ?></span></div>
                                                    <?php

                                                    $start_date->modify("+$total_day day");

                                                    ?>
                                                    <div><?php echo JHtml::_('date',$start_date) ?></div>
                                                    <div><?php echo $des_finish ?></div>
                                                </div>
                                                <div class="span2">
                                                    <ul class="list">
                                                        <li><?php echo JText::_('Senior') ?>:<span class="price"
                                                                                                   data-a-sign="US$"><?php echo $trip->price_senior ?></span>
                                                        </li>
                                                        <li><?php echo JText::_('Adult') ?>:<span class="price"
                                                                                                  data-a-sign="US$"><?php echo $trip->price_adult ?></span>
                                                        </li>
                                                        <li><?php echo JText::_('Teener') ?>:<span class="price"
                                                                                                   data-a-sign="US$"><?php echo $trip->price_teen ?></span>
                                                        </li>
                                                        <li><?php echo JText::_('Child 6-11') ?>:<span class="price"
                                                                                                       data-a-sign="US$"><?php echo $trip->price_children1 ?></span>
                                                        </li>
                                                        <li><?php echo JText::_('Child 2-5') ?>:<span class="price"
                                                                                                      data-a-sign="US$"><?php echo $trip->price_children2 ?></span>
                                                        </li>
                                                        <li><?php echo JText::_('Infant') ?>:<span class="price"
                                                                                                   data-a-sign="US$"><?php echo $trip->price_infant ?></span>
                                                        </li>
                                                    </ul>

                                                </div>
                                                <div class="span6 area-booking">
                                                    <div class="row-fluid area-text">
                                                        <div class="span6 text-left" style="text-align: center">
                                                            <?php echo JText::_('total price per person based on passenger age and tour date') ?>
                                                        </div>
                                                        <div class="span6 text-right" style="text-align: center">
                                                            <?php echo JText::_('Select private room+US$ 300/person') ?>
                                                        </div>
                                                    </div>
                                                    <div class="row-fluid area-button">
                                                        <div class="span12" style="text-align: center">
                                                            <button class="btn btn-primary book-now" type="submit"  ><?php echo JText::_('Available || Book now') ?></button>
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
                    <input name="option" value="com_virtuemart" type="hidden">
                    <input name="controller" value="trip" type="hidden">
                    <input type="hidden" value="trip" name="view">
                    <input name="virtuemart_price_id" value="0" type="hidden">
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
    <!-- Zozo Tabs End-->

</div>