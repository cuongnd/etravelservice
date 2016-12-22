<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_tsmart/assets/js/view_book_default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_tsmart/assets/less/view_book_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$app = JFactory::getApplication();
$input = $app->input;
$tsmart_price_id = $input->getInt('tsmart_price_id', 0);
$js_content = '';
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-book-default').view_book_default({

        });


    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
<div class="view-book-default">
    <form
        action="<?php echo JRoute::_('index.php?option=com_tsmart&view=book&tsmart_price_id=' . $tsmart_price_id) ?>" method="post"
        id="tour_price" name="tour_price">
        <div class="row-fluid">
            <div class="span6 offset6">

            </div>
        </div>
        <div class="row-fluid">
            <div class="span10">
                <h3 class="passenger-details"><?php echo JText::_('Passenger details') ?></h3>
                <div class="row-fluid">
                    <div class="span12">
                        <fieldset class="tour-border">
                            <legend
                                class="tour-border"><?php echo JText::_('Get best price for your travel date') ?></legend>
                            <?php echo VmHTML::select_number_passenger('filter_total_passenger_from_12_years_old', '', 1, 20, 1, ''); ?>
                            <?php echo VmHTML::select_number_passenger('filter_total_passenger_under_12_years_old', 'Passenger under 12 years old', 1, 20, 1, ''); ?>
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
                            <div data-tsmart_price_id="<?php echo $trip->tsmart_price_id ?>" class="row-fluid item">

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

                        </div>
                    </div>
                </div>
            </div>
            <div class="span2">
                <h1><?php echo JText::_('Book and go') ?></h1>
                <h3><?php echo JText::_('Booking summary') ?></h3>
                <div><span><?php echo JText::_('details') ?></span></div>
                <div class="row-fluid">
                    <div class="span6">
                        <div><span class="icon-file-2"></span><?php echo JText::_('Start date, city') ?></div>
                    </div>
                    <div class="span6">
                        <div><span class="icon-file-2"></span><?php echo JText::_('Finish date, city') ?></div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        line
                    </div>
                </div>
                <div class="row-fluid">
                    <div style="text-align: center" class="span12">
                        <span class="icon-file-2"></span> 20 days | duration
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo JText::_('Tour style') ?>: <?php echo JText::_('joint group') ?>
                    </div>
                    <div class="span6">
                        <?php echo JText::_('Service class') ?>: <?php echo JText::_('Stander') ?>
                    </div>
                </div>
                <div class="line"></div>
                <h4><span class="icon-users"></span><?php echo JText::_('Passenger number') ?>:7 pers.</h4>
                <ul>
                    <li>1.per1</li>
                    <li>2.per2</li>
                    <li>3.per3</li>
                </ul>

            </div>
        </div>

        <input name="option" value="com_tsmart" type="hidden">
        <input name="controller" value="trip" type="hidden">
        <input type="hidden" value="trip" name="view">
        <input name="tsmart_price_id" value="0" type="hidden">
        <input name="task" value="" type="hidden">
    </form>



</div>