<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_virtuemart/assets/js/view-productdetails-default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_virtuemart/assets/less/view_productdetail_default.less');


$js_content = '';
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#tabbed-nav").zozoTabs({
            theme: "silver",
            orientation: "horizontal",
            position: "top-left",
            size: "medium",
            animation: {
                easing: "easeInOutExpo",
                duration: 400,
                effects: "slideH"
            },
            defaultTab: "tab1"
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
<div class="view_productdetail_default">
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
            <div class="content">

                <div class="row-fluid">
                    <div class="span12">
                        <fieldset class="tour-border">
                            <legend class="tour-border"><?php echo JText::_('Get best price for your travel date') ?></legend>
                            <?php echo VmHTML::select_number_passenger('total_passenger_from_12_years_old', '',1,20,1,''); ?>
                            <?php echo VmHTML::select_number_passenger('total_passenger_under_12_years_old', 'Passenger under 12 years old',1,20,1,''); ?>
                            <?php echo VmHTML::select_date('date_selected', ''); ?>
                            <div class="btn-go">
                                <?php echo VmHTML::input_button('submit', 'Go'); ?>
                            </div>
                        </fieldset>

                    </div>
                </div>
                <div class="row-fluid header">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="offset2 span3">
                                <?php echo JText::_('Dates listing')?>
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
                <div class="row-fluid">
                    <div class="span1">
                        <span title="" class="icon-location "></span>
                    </div>
                    <div class="span3">
                        <?php echo JText::_('Date not selected') ?>
                    </div>
                    <div class="span2">

                    </div>
                    <div class="span2">

                    </div>
                    <div class="span2">

                    </div>
                    <div class="span2">
                        <a href="javascript:void(0)" data-toggle="collapse" data-target="#demo"><span title=""
                                                                                                      class="icon-chevron-down  hasTooltip"
                                                                                                      data-original-title="Detail"></span></a>
                    </div>
                </div>
                <div id="demo" class="row-fluid collapse">
                    <div class="span2">
                        <div><?php echo JText::_('Start') ?></div>
                    </div>
                    <div class="span2">
                        <div><?php echo JText::_('Finish') ?></div>
                    </div>
                    <div class="span2">
                        <ul>
                            <li><?php echo JText::_('Senior') ?>:<span>180</span></li>
                            <li><?php echo JText::_('Adult') ?>:<span>180</span></li>
                            <li><?php echo JText::_('Teener') ?>:<span>180</span></li>
                            <li><?php echo JText::_('Child 6-11') ?>:<span>180</span></li>
                            <li><?php echo JText::_('Child 2-5') ?>:<span>180</span></li>
                            <li><?php echo JText::_('Infant') ?>:<span>180</span></li>
                        </ul>
                    </div>
                    <div class="span3">
                        <?php echo JText::_('text1') ?>
                    </div>
                    <div class="span3">
                        <?php echo JText::_('text2') ?>
                    </div>
                </div>
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