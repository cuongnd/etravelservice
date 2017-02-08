<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_tsmart/assets/js/view_bookprivategroup_default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_tsmart/assets/less/view_bookprivategroup_default.less');
$doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
$doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/plugin/jquery-cookie-master/src/jquery.cookie.js');
$doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/controller/build_room/html_build_room.js');
$doc->addScript(JUri::root() . 'components/com_tsmart/assets/js/plugin/sidr-master/dist/jquery.sidr.js');
$doc->addStyleSheet(JUri::root() . 'components/com_tsmart/assets/js/plugin/sidr-master/dist/stylesheets/jquery.sidr.light.css');
TSMHtmlJquery::alert();
$doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
$app = JFactory::getApplication();
$input = $app->input;
$tsmart_price_id = $input->getInt('tsmart_price_id', 0);
$booking_date = $input->getString('booking_date', '');
$privategrouptrip = $this->privategrouptrip;
/*$privategrouptrip->sale_price_senior = 400;
$privategrouptrip->sale_price_adult = 500;
$privategrouptrip->sale_price_teen = 400;
$privategrouptrip->sale_price_children1 = 300;
$privategrouptrip->sale_price_children2 = 200;
$privategrouptrip->sale_price_infant = 100;
$privategrouptrip->sale_price_private_room = 100;
$privategrouptrip->sale_price_extra_bed = 50;
$privategrouptrip->full_charge_children1 = 0;
$privategrouptrip->full_charge_children2 = 1;*/
/*$departure->sale_promotion_price_senior=100;
$departure->sale_promotion_price_adult=100;
$departure->sale_promotion_price_teen=100;
$departure->sale_promotion_price_children1=100;
$departure->sale_promotion_price_children2=100;
$departure->sale_promotion_price_infant=100;
$departure->sale_promotion_price_private_room=100;
$departure->sale_promotion_price_extra_bed=100;*/
$passenger_config = tsmConfig::get_passenger_config();
$list_destination = explode(';', $privategrouptrip->list_destination);
$des_start = reset($list_destination);
$des_finish = end($list_destination);
$total_day = $privategrouptrip->total_day - 1;
$start_date = JFactory::getDate($privategrouptrip->departure_date);
$total_day = $total_day ? $total_day : 0;
$total_passenger_from_12_years_old=$this->privategrouptrip_model->getState('filter.total_passenger_from_12_years_old');
$total_passenger_under_12_years_old=$this->privategrouptrip_model->getState('filter.total_passenger_under_12_years_old');
?>
    <div class="view-bookprivategroup-default">
        <div class="row">
            <div class="col-lg-6 offset6">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9">
                <h3 class="passenger-details"><?php echo JText::_('Passenger details') ?></h3>
                <form
                    action="<?php echo JRoute::_('index.php?option=com_tsmart&view=bookprivategroup&tsmart_price_id=' . $tsmart_price_id.'&booking_date='.$booking_date) ?>"
                    method="post" class="departure-date-select"
                    id="check_tour_price" name="check_tour_price">
                    <div class="row">
                        <div class="col-lg-12">
                            <fieldset class="tour-border departure-filter">
                                <legend
                                    class="tour-border"><?php echo JText::_('Update passenger') ?></legend>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <?php echo VmHTML::select_number_passenger('filter_total_passenger_from_12_years_old', '', 1, 50, $total_passenger_from_12_years_old, '',"%s","%s person(s) over 12 years"); ?>
                                    </div>
                                    <div class="col-lg-5">
                                        <?php
                                        $disable_select= (int)$this->product->min_age>12;
                                        $total_passenger_under_12_years_old=$disable_select?0:$total_passenger_under_12_years_old;
                                        ?>
                                        <?php echo VmHTML::select_number_passenger('filter_total_passenger_under_12_years_old', 'Passenger under 12 years old', 0, 50, $total_passenger_under_12_years_old, '',"%s","%s person(s) under 12 years",$disable_select); ?>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="btn-go">
                                            <?php echo VmHTML::input_button('submit', 'Go'); ?>
                                        </div>
                                    </div>
                                </div>
                                <input name="option" value="com_tsmart" type="hidden">
                                <input name="controller" value="trip" type="hidden">
                                <input name="booking_date" value="<?php echo $booking_date ?>" type="hidden">
                                <input type="hidden" value="bookprivategroup" name="view">
                                <input name="tsmart_price_id" value="<?php echo $tsmart_price_id ?>" type="hidden">
                                <input name="task" value="" type="hidden">



                            </fieldset>
                        </div>
                    </div>
                </form>
                <div class=" departure-item">
                    <div class="row body">
                        <div class="col-lg-12">
                            <div class="row item">
                                <div class="col-lg-12">
                                    <div class="header-item">
                                        <div class="row">
                                            <div class="col-lg-1 col-xxxs-2">
                                                    <span title=""
                                                          class="arrow-right glyphicon glyphicon-arrow-right"></span>
                                            </div>
                                            <div class="col-lg-1 col-xxxs-4">
                                                <?php echo JHtml::_('date', $privategrouptrip->departure_date, tsmConfig::$date_format) ?>
                                            </div>
                                            <div class="col-lg-4 service-class  col-xxxs-6 ">
                                                <div class="pull-right text-uppercase "><?php echo JText::sprintf('%s class', $privategrouptrip->service_class_name) ?></div>
                                            </div>
                                            <div class="col-lg-6 sale-off text-uppercase hidden-xxxs">
                                                <?php echo JText::printf('off 10 early bird booking 90 days, code shtdgh') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="trip" class="body-item">
                                        <div class="row">
                                            <div class="col-lg-6 item-body-left">
                                                <div class="row">
                                                    <div class="col-lg-6 col-xxxs-12">
                                                        <div class="pull-left start">
                                                            <div><span
                                                                    class="text-start"><?php echo JText::_('Start') ?></span>
                                                            </div>
                                                            <div><?php echo JHtml::_('date', $privategrouptrip->departure_date, tsmConfig::$date_format) ?></div>
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
                                                        <?php echo $this->loadTemplate('price') ?>
                                                        <div class="hidden-tablet-desktop sale-off text-uppercase">
                                                            <?php echo JText::printf('off 10 early bird booking 90 days, code shtdgh') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 item-body-right area-booking">
                                                <div class="row area-text">
                                                    <div class="col-lg-6 text-left"
                                                         style="text-align: center">
                                                        <?php echo JText::_('total price per person based on passenger age and tour date') ?>
                                                    </div>
                                                    <div class="col-lg-6 text-right"
                                                         style="text-align: center">
                                                        <?php echo JText::sprintf('Select private room+<span class="price" data-a-sign="US$ ">%s</span>/person',$privategrouptrip->sale_price_private_room) ?>
                                                    </div>
                                                </div>
                                                <div class="row area-button">
                                                    <div class="col-lg-12" style="text-align: center">
                                                        <div class="seat-left btn">
                                                            <?php echo JText::_('Available') ?>
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
                </div>
                <form
                    action="<?php echo JRoute::_('index.php?option=com_tsmart&view=bookprivategroup&tsmart_price_id=' . $tsmart_price_id) ?>"
                    method="post"
                    id="bookprivategroup" name="bookprivategroup">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php echo VmHtml::input_passenger(array(), 'json_list_passenger', '', $this->product->min_age, $this->product->max_age, $privategrouptrip, $passenger_config) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <fieldset class="tour-border rooming">
                                <legend
                                    class="tour-border"><?php echo JText::_('Roomming') ?></legend>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="noice">
                                                <span class="icon glyphicon glyphicon-exclamation-sign "
                                                      title=""></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <?php echo VmHTML::list_radio_rooming('rooming', $this->rooming_select, null); ?>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="joint_group_note joint_group_note_1">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo JText::_('NOTE_SETTING_MY_ROOM_LIST_LARTER') ?>
                            </div>
                        </div>
                    </div>
                    <div class="joint_group_note joint_group_note_2">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo JText::_('NOTE_BUIL_MY_ROOM_LIST') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-build-room">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="build-your-room"><?php echo JText::_('Build your room') ?></h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo VmHtml::build_room(array(), "build_room", "", $privategrouptrip, $passenger_config,true) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php echo VmHtml::build_form_contact('contact_data') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="title special_requirement"><?php echo JText::_('Special requirement') ?></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php echo VmHTML::textarea('special_requirement', ''); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xxxs-12">
                            <div class="area-control">
                                <button type="submit" class="btn btn-primary btn-large control-next ">
                                    <span class="confirm"><?php echo JText::_('confirm and book') ?></span>
                                    <br>
                                    <span class="payment">(<?php echo JText::_('Payment required') ?>)</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xxxs-12">
                            <div class="area-control">
                                <button type="submit" class="btn btn-primary btn-large control-hold-my-space ">
                                    <span class="hold-my-space"><?php echo JText::_('Hold my space') ?></span>
                                    <br>
                                        <span class="payment">(<?php echo JText::_('hold for 48h hours, no payment') ?>
                                            )</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input name="option" value="com_tsmart" type="hidden">
                    <input name="controller" value="bookprivategroup" type="hidden">
                    <input name="tsmart_price_id" value="<?php echo $tsmart_price_id ?>" type="hidden">
                    <input name="booking_date" value="<?php echo $booking_date ?>" type="hidden">
                    <input name="task" value="go_to_booking_add_on_from" type="hidden">
                </form>
            </div>
            <div class="col-lg-3 hidden-xxxs">
                <div class="booking-summary-content">
                    <h1 class="book-and-go"><?php echo JText::_('Book and Go') ?></h1>
                    <div class="booking-summary-content-body">
                        <h3 class="booking-summary"><?php echo JText::_('Booking summary') ?></h3>
                        <div class="row"><span
                                class="detail pull-right"><?php echo JText::_('details') ?></span></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="trip"><span class="title"><?php echo JText::_('Trip') ?>
                                        :</span><span
                                        class="trip-name"><?php echo $this->product->product_name ?></span></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div><span
                                        class="glyphicon glyphicon-time"></span><?php echo JHtml::_('date', $privategrouptrip->departure_date) ?>
                                    ,<?php echo $des_start ?></div>
                            </div>
                            <div class="col-lg-6">
                                <?php
                                $start_date->modify("+$total_day day");
                                ?>
                                <div><span
                                        class="glyphicon glyphicon-time"></span><?php echo JHtml::_('date', $start_date) ?>
                                    ,<?php echo $des_finish ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                line
                            </div>
                        </div>
                        <div class="row">
                            <div style="text-align: center" class="col-lg-12">
                                <span class="glyphicon glyphicon-time"></span> <?php echo $total_day ?> days | duration
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php echo JText::_('Tour style') ?>: <?php echo JText::_('joint group') ?>
                            </div>
                            <div class="col-lg-6">
                                <?php echo JText::_('Service class') ?>: <?php echo JText::_('Stander') ?>
                            </div>
                        </div>
                        <div class="line-dotted"></div>
                        <h4><span class="glyphicon glyphicon-user"></span><span
                                class="passenger-number"><?php echo JText::_('Passenger number') ?>
                                : </span><span
                                class="total-passenger"> 7 </span> <?php echo JText::_('pers') ?>.</h4>
                        <ul class="list_passenger">
                            <li>1.per1</li>
                            <li>2.per2</li>
                            <li>3.per3</li>
                        </ul>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="passenger-service-fee pull-right">
                                    <?php echo JText::_('Service fee') ?> <span
                                        class="passenger-service-fee-total"
                                        data-a-sign="US$">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="line-dotted"></div>
                        <h3><?php echo JText::_('Room supplement fee') ?></h3>
                        <div class="list-room">
                            <ul class="list_passenger_room">
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="room-service-fee pull-right">
                                    <?php echo JText::_('Service fee') ?> <span class="room-service-fee-total"
                                                                                data-a-sign="US$"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sidr_booking_summary" class=" hidden-tablet-desktop">
                <span class="show-hidden-fee close glyphicon glyphicon-remove"></span>
                more total price
            </div>
            <div class="booking-summary-content-mobile hidden-tablet-desktop">
                <div class="pull-left"><?php echo JText::_('Total service fee') ?></div>
                <div class="pull-right"><span class="room-service-fee-total" data-a-sign="US$">200</span> (<a
                        class="show-hidden-fee more"
                        href="javascript:void(0)"><?php echo JText::_('More') ?></a>)
                </div>
            </div>
        </div>
    </div>
<?php
$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-bookprivategroup-default').view_bookprivategroup_default({
                passenger_config:<?php  echo json_encode($passenger_config) ?>,
                item:<?php  echo json_encode($privategrouptrip) ?>,
                tour_min_age:<?php echo (int)$this->product->min_age ?>,
                tour_max_age:<?php echo (int)$this->product->max_age ?>,
                total_senior_adult_teen:<?php echo (int)$total_passenger_from_12_years_old ?>,
                total_children_infant:<?php echo (int)$total_passenger_under_12_years_old ?>,
                list_group_size:<?php echo json_encode($this->product->list_group_size) ?>,
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);
?>