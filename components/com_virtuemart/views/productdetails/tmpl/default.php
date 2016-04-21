<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_virtuemart/assets/js/view_productdetails_default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_virtuemart/assets/less/view_productdetail_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$app = JFactory::getApplication();
$input = $app->input;
$virtuemart_product_id = $input->getInt('virtuemart_product_id', 0);
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
                    id="tour_price" name="tour_price">
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
                                <?php echo VmHTML::select_number_passenger('filter_total_passenger_from_12_years_old', '', 1, 20, 1, ''); ?>
                                <?php echo VmHTML::select_number_passenger('filter_total_passenger_under_12_years_old', 'Passenger under 12 years old', 1, 20, 1, ''); ?>
                                <?php echo VmHTML::range_of_date('filter_start_date', 'filter_end_date'); ?>
                                <div class="btn-go">
                                    <?php echo VmHTML::input_button('submit', 'Go'); ?>
                                </div>
                            </fieldset>

                        </div>
                    </div>
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
                                    ?>
                                    <div data-virtuemart_price_id="<?php echo $trip->virtuemart_price_id ?>" class="row-fluid item">

                                        <div class="span12">
                                            <div class="row-fluid header-item">
                                                <div class="span1">
                                                    <span title="" class="icon-location "></span>
                                                </div>
                                                <div class="span3">
                                                    <?php echo JText::_('Date not selected') ?>
                                                </div>
                                                <div class="span2 service-class ">
                                                    <?php echo $trip->service_class_name ?>
                                                </div>
                                                <div class="span2 price ">
                                                    <span class="price"
                                                          data-a-sign="US$"><?php echo $trip->price_adult ?></span>
                                                </div>
                                                <div class="span4 service-class-price hide">
                                                    <?php echo JText::_('Deluxe class price') ?>
                                                </div>
                                                <div class="span2">

                                                </div>
                                                <div class="span2">
                                                    <a href="javascript:void(0)" class="btn-collapse"
                                                       data-toggle="collapse" data-target="#trip-<?php echo $i ?>"><span
                                                            title=""
                                                            class="icon-chevron-down  hasTooltip"
                                                            data-original-title="Detail"></span></a>
                                                </div>
                                            </div>
                                            <div id="trip-<?php echo $i ?>" class="row-fluid body-item collapse">
                                                <div class="span2">
                                                    <div><?php echo JText::_('Start') ?></div>
                                                </div>
                                                <div class="span2">
                                                    <div><?php echo JText::_('Finish') ?></div>
                                                </div>
                                                <div class="span2">
                                                    <ul class="dl-ve">
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
                                                <div class="span6">
                                                    <div class="row-fluid">
                                                        <div class="span6" style="text-align: center">
                                                            <?php echo JText::_('text1') ?>
                                                        </div>
                                                        <div class="span6" style="text-align: center">
                                                            <?php echo JText::_('text2') ?>
                                                        </div>
                                                    </div>
                                                    <div class="row-fluid">
                                                        <div class="span12" style="text-align: center">
                                                            <button class="btn btn-primary book-now" type="submit"  ><?php echo JText::_('20 seats left || Book now') ?></button>
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