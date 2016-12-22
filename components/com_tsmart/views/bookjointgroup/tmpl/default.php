<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_tsmart/assets/js/view_bookjointgroup_default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_tsmart/assets/less/view_bookjointgroup_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/jquery-cookie-master/src/jquery.cookie.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/controller/build_room/html_build_room.js');
$doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');

$app = JFactory::getApplication();
$input = $app->input;
$tsmart_price_id = $input->getInt('tsmart_price_id', 0);
$departure = $this->depatrure;


$departure->sale_price_senior=400;
$departure->sale_price_adult=500;
$departure->sale_price_teen=400;
$departure->sale_price_children1=300;
$departure->sale_price_children2=200;
$departure->sale_price_infant=100;
$departure->sale_price_private_room=100;
$departure->sale_price_extra_bed=50;


$departure->full_charge_children1=0;
$departure->full_charge_children2=1;


/*$departure->sale_promotion_price_senior=100;
$departure->sale_promotion_price_adult=100;
$departure->sale_promotion_price_teen=100;
$departure->sale_promotion_price_children1=100;
$departure->sale_promotion_price_children2=100;
$departure->sale_promotion_price_infant=100;
$departure->sale_promotion_price_private_room=100;
$departure->sale_promotion_price_extra_bed=100;*/



$passenger_config=tsmConfig::get_passenger_config();

?>
<div class="view-bookjointgroup-default">
    <form
        action="<?php echo JRoute::_('index.php?option=com_tsmart&view=book&tsmart_price_id=' . $tsmart_price_id) ?>"
        method="post"
        id="tour_price" name="tour_price">
        <div class="row-fluid">
            <div class="span6 offset6">

            </div>
        </div>
        <div class="row-fluid">
            <div class="span9">
                <h3 class="passenger-details"><?php echo JText::_('Passenger details') ?></h3>
                <div class="row-fluid">
                    <div class="span12">
                        <fieldset class="tour-border departure-filter">
                            <legend
                                class="tour-border"><?php echo JText::_('Update passenger') ?></legend>
                            <?php echo VmHTML::select_number_passenger('filter_total_passenger_from_12_years_old', '', 1, 20, 1, ''); ?>
                            <?php echo VmHTML::select_number_passenger('filter_total_passenger_under_12_years_old', 'Passenger under 12 years old', 1, 20, 1, ''); ?>
                            <div class="btn-go">
                                <?php echo VmHTML::input_button('submit', 'Go'); ?>
                            </div>
                        </fieldset>

                    </div>
                </div>
                <div class="table table-trip departure-item">
                    <div class="row-fluid body">
                        <div class="span12">
                            <div class="row-fluid item">

                                <div class="span12">
                                    <div class="row-fluid header-item">
                                        <div class="span1">
                                            <span title="" class="icon-location "></span>
                                        </div>
                                        <div class="span3">
                                            <?php echo JText::_('Date not selected') ?>
                                        </div>
                                        <div class="span2 service-class ">
                                            <?php echo $departure->service_class_name ?>
                                        </div>
                                        <div class="span2 price ">
                                                    <span class="price"
                                                          data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_price_adult) ?></span>
                                        </div>
                                        <div class="span4 service-class-price hide">
                                            <?php echo JText::_('Deluxe class price') ?>
                                        </div>
                                        <div class="span2">

                                        </div>
                                        <div class="span2">
                                        </div>
                                    </div>
                                    <div id="trip" class="row-fluid body-item">
                                        <div class="span2">
                                            <div><?php echo JText::_('Start') ?></div>
                                        </div>
                                        <div class="span2">
                                            <div><?php echo JText::_('Finish') ?></div>
                                        </div>
                                        <div class="span2">
                                            <ul class="dl-ve">
                                                <li><?php echo JText::_('Senior') ?>:<span class="price"
                                                                                           data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_price_senior) ?></span>
                                                    <?php if($departure->sale_promotion_price_senior): ?><span class="price"
                                                          data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_promotion_price_senior) ?></span><?php endif ?>
                                                </li>
                                                <li><?php echo JText::_('Adult') ?>:<span class="price"
                                                                                          data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_price_adult) ?></span>
                                                    <?php if($departure->sale_promotion_price_adult): ?><span
                                                        class="price"
                                                        data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_promotion_price_adult) ?></span><?php endif ?>
                                                </li>
                                                <li><?php echo JText::_('Teener') ?>:<span class="price"
                                                                                           data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_price_teen) ?></span><?php if($departure->sale_promotion_price_teen): ?><span
                                                        class="price"
                                                        data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_promotion_price_teen) ?></span><?php endif ?>
                                                </li>
                                                <li><?php echo JText::_('Child 6-11') ?>:<span class="price"
                                                                                               data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_price_children1) ?></span><?php if($departure->sale_promotion_price_children1): ?><span
                                                        class="price"
                                                        data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_promotion_price_children1) ?></span><?php endif ?>
                                                </li>
                                                <li><?php echo JText::_('Child 2-5') ?>:<span class="price"
                                                                                              data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_price_children2) ?></span><?php if($departure->sale_promotion_price_children2): ?><span
                                                        class="price"
                                                        data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_promotion_price_children2) ?></span><?php endif ?>
                                                </li>
                                                <li><?php echo JText::_('Infant') ?>:<span class="price"
                                                                                           data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_price_infant) ?></span><?php if($departure->sale_promotion_price_infant): ?><span
                                                        class="price"
                                                        data-a-sign="US$"><?php echo tsmConfig::render_price($departure->sale_promotion_price_infant) ?></span><?php endif ?>
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
                                                    <button class="btn btn-primary book-now"
                                                            type="submit"><?php echo JText::_('20 seats left || Book now') ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo VmHtml::input_passenger(array(),'json_list_passenger','',$this->product->min_age,$this->product->max_age,$departure,$passenger_config) ?>
                    </div>
                </div>
                <div class="form-contact form-horizontal">
                    <div class="row-fluid">
                        <div class="span12">
                            <h4 class="title contact-detail"><span class="icon-location " title=""></span><?php echo JText::_('Contact detail')?></h4>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <?php echo VmHTML::row_control('input', JText::_('Phone No'), 'phone_number', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_('Email Address'), 'email_address', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_('Confirm Email'), 'confirm_email', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_('Street address'), 'street_address', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_('Suburb/Town'), 'suburb_town', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_('State/province'), 'state_province', '', 'class="required"'); ?>

                        </div>
                        <div class="span6">
                            <?php echo VmHTML::row_control('input', JText::_('Postcode/Zip'), 'post_code_zip', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_('Res. Country'), 'res_country', '', 'class="required"'); ?>
                            <h3 class=" emergency-contact"><?php echo JText::_('Emergency contact') ?></h3>
                            <?php echo VmHTML::row_control('input', JText::_('Contact Name'), 'emergency_contact_name', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_('Street address'), 'emergency_email_address', '', 'class="required"'); ?>
                            <?php echo VmHTML::row_control('input', JText::_('Phone No'), 'emergency_phone_number', '', 'class="required"'); ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <fieldset class="tour-border rooming">
                            <legend
                                class="tour-border"><?php echo JText::_('Roomming') ?></legend>
                            <div class="row-fluid">
                                <div class="span3">
                                    <span class="icon-notification " title=""></span>
                                </div>
                                <div class="span9">
                                    <?php echo VmHTML::list_radio('rooming',$this->rooming_select,'share_room' ); ?>
                                </div>
                            </div>



                        </fieldset>

                    </div>
                </div>
                <div class="joint_group_note joint_group_note_1">
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo $this->lipsum->words(100) ?>
                        </div>
                    </div>
                </div>
                <div class="joint_group_note joint_group_note_2">
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo $this->lipsum->words(100) ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <h3 class="build-your-room"><?php echo JText::_('Build your room')?></h3>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo VmHtml::build_room(array(),"build_room","",$departure,$passenger_config) ?>

                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-large control-next "><span title="" class="icon-next "></span><?php echo JText::_('Next') ?></button>
                        </div>
                    </div>
                </div>

            </div>
            <?php
            $list_destination = explode(';', $departure->list_destination);
            $des_start = reset($list_destination);
            $des_finish = end($list_destination);
            $total_day = $departure->total_day - 1;
            $start_date = JFactory::getDate($departure->departure_date);

            $total_day = $total_day ? $total_day : 0;

            ?>
            <div class="span3">
                <div class="booking-summary-content">
                    <h1 class="book-and-go"><?php echo JText::_('Book and Go') ?></h1>
                    <div class="booking-summary-content-body">
                        <h3 class="booking-summary"><?php echo JText::_('Booking summary') ?></h3>
                        <div class="row-fluid" ><span class="detail pull-right"><?php echo JText::_('details') ?></span></div>
                        <div class="row-fluid">
                            <div class="span12">
                                <h4 class="trip"><span class="title"><?php echo JText::_('Trip') ?> :</span><span class="trip-name"><?php echo $this->product->product_name ?></span></h4>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="span6">
                                <div><span class="icon-clock"></span><?php echo  JHtml::_('date', $departure->departure_date) ?>,<?php echo  $des_start ?></div>
                            </div>
                            <div class="span6">
                                <?php
                                $start_date->modify("+$total_day day");

                                ?>
                                <div><span class="icon-clock"></span><?php echo  JHtml::_('date', $start_date) ?>,<?php echo  $des_finish ?></div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                line
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div style="text-align: center" class="span12">
                                <span class="icon-clock"></span> <?php echo $total_day ?> days | duration
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
                        <div class="line-dotted"></div>
                        <h4><span class="icon-users"></span><span class="passenger-number"><?php echo JText::_('Passenger number') ?> : </span><span class="total-passenger"> 7 </span> <?php echo JText::_('pers') ?>.</h4>
                        <ul class="list_passenger">
                            <li>1.per1</li>
                            <li>2.per2</li>
                            <li>3.per3</li>
                        </ul>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="passenger-service-fee pull-right">
                                    <?php echo JText::_('Service fee') ?> <span class="passenger-service-fee-total" data-a-sign="US$">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="line-dotted"></div>
                        <h3><?php echo JText::_('Room supplement fee') ?></h3>
                        <div class="list-room">
                            <ul class="list_passenger_room">

                            </ul>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="room-service-fee pull-right">
                                    <?php echo JText::_('Service fee') ?> <span class="room-service-fee-total" data-a-sign="US$"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input name="option" value="com_tsmart" type="hidden">
        <input name="controller" value="trip" type="hidden">
        <input type="hidden" value="trip" name="view">
        <input name="tsmart_price_id" value="0" type="hidden">
        <input name="task" value="" type="hidden">
    </form>


</div>
<?php
$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-bookjointgroup-default').view_bookjointgroup_default({
                passenger_config:<?php  echo json_encode($passenger_config) ?>,
                departure:<?php  echo json_encode($departure) ?>,
                tour_min_age:<?php echo $this->product->min_age ?>,
                tour_max_age:<?php echo $this->product->max_age ?>
            });


        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>