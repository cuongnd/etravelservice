<?php
$doc = JFactory::getDocument();
JHtml::_('jquery.ui');
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_tsmart/assets/js/view_bookprivategroupaddon_default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_tsmart/assets/less/view_bookprivategroupaddon_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/jquery-cookie-master/src/jquery.cookie.js');
$doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');
$app = JFactory::getApplication();
$input = $app->input;
$tsmart_price_id = $input->getInt('tsmart_price_id', 0);
$rooming = $input->getString('rooming', '');
$booking_date = $input->getString('booking_date', '');
$privategrouptrip = $this->privategrouptrip;
$session = JFactory::getSession();
$build_room = $session->get('build_room', '');
$json_list_passenger = $session->get('json_list_passenger', '');
$json_list_passenger = json_decode($json_list_passenger);
$list_passenger = array_merge($json_list_passenger->senior_adult_teen, $json_list_passenger->children_infant);
$build_room = json_decode($build_room);
$privategrouptrip->sale_price_senior = 400;
$privategrouptrip->sale_price_adult = 500;
$privategrouptrip->sale_price_teen = 400;
$privategrouptrip->sale_price_children1 = 300;
$privategrouptrip->sale_price_children2 = 200;
$privategrouptrip->sale_price_infant = 100;
$privategrouptrip->sale_price_private_room = 100;
$privategrouptrip->sale_price_extra_bed = 50;
$privategrouptrip->full_charge_children1 = 0;
$privategrouptrip->full_charge_children2 = 1;
/*$departure->sale_promotion_price_senior=100;
$departure->sale_promotion_price_adult=100;
$departure->sale_promotion_price_teen=100;
$departure->sale_promotion_price_children1=100;
$departure->sale_promotion_price_children2=100;
$departure->sale_promotion_price_infant=100;
$departure->sale_promotion_price_private_room=100;
$departure->sale_promotion_price_extra_bed=100;*/
$passenger_config = tsmConfig::get_passenger_config();
$transfer_config = tsmConfig::get_transfer_config_year_old();
$hotel_config = tsmConfig::get_hotel_config_year_old();
$list_destination = explode(';', $privategrouptrip->list_destination);
$des_start = reset($list_destination);
$des_finish = end($list_destination);
$total_day = $privategrouptrip->total_day - 1;
$start_date = JFactory::getDate($privategrouptrip->departure_date);
$total_day = $total_day ? $total_day : 0;
$end_date = clone $start_date;
$debug = TSMUtility::get_debug();
$end_date->modify("+$total_day day");
?>
    <div class="view-bookprivategroupaddon-default">
        <form
            action="<?php echo JRoute::_('index.php?option=com_tsmart&view=bookprivategroupaddonaddon&tsmart_price_id=' . $tsmart_price_id) ?>"
            method="post"
            id="bookprivategroupaddon" name="bookprivategroupaddon">
            <div class="row">
                <div class="col-lg-6 offset6">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <h3 class="passenger-details"><?php echo JText::_('Additional service') ?></h3>
                    <?php if ($privategrouptrip->tour_length > 1) { ?>
                        <?php if ($this->pre_transfer_min_price != null || $this->post_transfer_min_price != null) { ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="build-your-room"><span class="travel-icon"
                                                                      title="">n</span><?php echo JText::_('Airport transfer') ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="joint_group_note joint_group_note_2">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php echo JText::_('TRANSFER_NOTE') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                $column = 'col-lg-12';
                                if ($this->pre_transfer_min_price != null && $this->post_transfer_min_price != null) {
                                    $column = 'col-lg-6';
                                }
                                ?>
                                <?php if ($this->pre_transfer_min_price != null) { ?>
                                    <div class="<?php echo $column ?>">
                                        <div class="form_build_pre_transfer">
                                            <h4 class="build-your-room"><?php echo JText::_('Airport pickup pre transfer') ?></h4>
                                            <?php echo VmHtml::build_pickup_transfer("build_pre_transfer", array(), 0, $privategrouptrip, $transfer_config, $this->pre_transfer_item, 'pre_transfer', $this->pre_transfer_min_price, $debug) ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($this->post_transfer_min_price != null) { ?>
                                    <div class="<?php echo $column ?>">
                                        <div class="form_build_post_transfer">
                                            <h4 class="build-your-room"><?php echo JText::_('Airport pickup post transfer') ?></h4>
                                            <?php echo VmHtml::build_pickup_transfer("build_post_transfer", array(), 0, $privategrouptrip, $transfer_config, $this->post_transfer_item, 'post_transfer', $this->post_transfer_min_price, $debug) ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($this->pre_night_hotel_group_min_price != null || $this->post_night_hotel_group_min_price != null) { ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="build-your-room"><span class="travel-icon"
                                                                      title="">n</span><?php echo JText::_('Extra night hotel') ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="joint_group_note joint_group_note_1">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php echo JText::sprintf('EXTRA_NIGHT_HOTEL_NOTE', JHtml::_('date', $privategrouptrip->departure_date, tsmConfig::$date_format), JHtml::_('date', $end_date, tsmConfig::$date_format)) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="center-block list_check_rooming">
                                            <?php echo VmHTML::list_check_rooming('rooming', $this->rooming_select_type, 'select_pre_post_night', '', 'value', 'text', 2, $debug); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->pre_night_hotel_group_min_price != null) { ?>
                            <div class="form_extra_pre_night_hotel hide">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="build-your-room"><?php echo JText::_('Pre night hotel') ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php echo VmHtml::build_extra_night_hotel(array(), "extra_pre_night_hotel", "", $privategrouptrip, $hotel_config, 'pre_night', $this->pre_night_item, $debug) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->post_night_hotel_group_min_price != null) { ?>
                            <div class="form_extra_post_night_hotel hide">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="build-your-room"><?php echo JText::_('post night hotel') ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php echo VmHtml::build_extra_night_hotel(array(), "extra_post_night_hotel", "", $privategrouptrip, $hotel_config, 'post_night', $this->post_night_item, $debug) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="build-your-room"><span class="travel-icon"
                                                              title="">n</span><?php echo JText::_('Extra Activity') ?>
                            </h4>
                        </div>
                    </div>
                    <?php if (count($this->list_excursion_addon)) { ?>
                        <div class="joint_group_note joint_group_note_2">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php echo JText::_('NOTE_EXTRA_ACTIVITY') ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo VmHtml::build_excursion_addon("build_excursion_addon", "", $privategrouptrip, $passenger_config, $this->list_excursion_addon, $debug) ?>
                            </div>
                        </div>
                    <?php } ?>
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
                </div>
                <div class="col-lg-4">
                    <div class="booking-summary-content">
                        <h1 class="book-and-go"><?php echo JText::_('Book and Go') ?></h1>
                        <div class="booking-summary-content-body">
                            <h3 class="booking-summary"><?php echo JText::_('Booking summary') ?></h3>
                            <div class="row"><span
                                    class="detail pull-right"><?php echo JText::_('details') ?></span></div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="trip"><span class="title"><?php echo JText::_('Trip') ?> :</span><span
                                            class="trip-name"><?php echo $this->product->product_name ?></span></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div><span
                                            class="icon-clock"></span><?php echo JHtml::_('date', $privategrouptrip->departure_date, tsmConfig::$date_format) ?>
                                        ,<?php echo $des_start ?></div>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <span class="icon-clock"></span><?php echo JHtml::_('date', $end_date, tsmConfig::$date_format) ?>
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
                                    <span class="icon-clock"></span> <?php echo $total_day ?> days | duration
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
                            <h4><span class="icon-users"></span><span
                                    class="passenger-number"><?php echo JText::_('Passenger number') ?> : </span><span
                                    class="total-passenger"> 7 </span> <?php echo JText::_('pers') ?>.</h4>
                            <ul class="list_passenger">
                                <?php
                                $total_tour_cost = 0;
                                foreach ($list_passenger AS $passenger) {
                                    $full_name = TSMUtility::get_full_name($passenger);
                                    $tour_cost = $passenger->tour_cost;
                                    $total_tour_cost += $tour_cost;
                                    if ($debug) {
                                        $full_name .= " (<span class=\"price\">$tour_cost</span>)";
                                    }
                                    ?>
                                    <li><?php echo $full_name ?></li>
                                    <?php
                                    $passenger_oder++;
                                }
                                ?>
                            </ul>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="passenger-service-fee pull-right">
                                        <?php echo JText::_('Service fee') ?> <span class="passenger-service-fee-total"
                                                                                    data-a-sign="US$"><?php echo $total_tour_cost ?></span>
                                        <input type="hidden" name="total_tour_cost" value="<?php echo $total_tour_cost ?>">
                                    </div>
                                </div>
                            </div>
                            <?php if ($rooming == 'buid_room') { ?>
                                <div class="line-dotted"></div>
                                <h3><?php echo JText::_('Room supplement fee') ?></h3>
                                <div class="list-room-supplement">
                                    <ul class="list_passenger_room_supplement">
                                        <?php
                                        $passenger_oder = 1;
                                        $total_supplement_cost = 0;
                                        ?>
                                        <?php foreach ($build_room AS $room) { ?>
                                            <?php
                                            $tour_cost_and_room_price = $room->tour_cost_and_room_price;
                                            foreach ($tour_cost_and_room_price as $item_price) {
                                                $total_supplement_cost += $item_price->room_price + $item_price->extra_bed_price;
                                            }
                                            $passengers = $room->passengers;
                                            $tour_cost_and_room_price = JArrayHelper::pivot($tour_cost_and_room_price, 'passenger_index');
                                            if (count($passengers)) {
                                                foreach ($passengers AS $passenger_index) {
                                                    $passenger = $list_passenger[$passenger_index];
                                                    $room_price = $tour_cost_and_room_price[$passenger_index]->room_price;
                                                    $extra_bed_price = $tour_cost_and_room_price[$passenger_index]->extra_bed_price;
                                                    $room_price = $room_price + $extra_bed_price;
                                                    if ($room_price == 0) continue;
                                                    $full_name = TSMUtility::get_full_name($passenger, $debug);
                                                    if ($debug) {
                                                        $full_name .= " (<span class=\"price\">$room_price</span>)";
                                                    }
                                                    ?>
                                                    <li><?php echo $full_name ?></li>
                                                    <?php
                                                    $passenger_oder++;
                                                }
                                            }
                                            ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="passenger-service-fee pull-right">
                                            <?php echo JText::_('Service fee') ?>
                                            <span class="passenger-service-fee-total"
                                                  data-a-sign="US$"><?php echo $total_supplement_cost ?></span>
                                            <input type="hidden" name="total_supplement_cost" value="<?php echo $total_supplement_cost ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($this->pre_transfer_min_price != null) { ?>
                                <div class="area-show-price-pre-transfer " style="display: none">
                                    <div class="line-dotted"></div>
                                    <h3><?php echo JText::_('Fee pre transfer') ?></h3>
                                    <div class="list-pre-transfer">
                                        <ul class="list_passenger_transfer">
                                        </ul>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="pre-transfer-service-fee pull-right">
                                                <?php echo JText::_('Service fee') ?>
                                                <span class="pre-transfer-service-fee-total"
                                                      data-a-sign="US$"></span>
                                                <input type="hidden" name="total_pre_transfer_service_fee" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($this->post_transfer_min_price != null) { ?>
                                <div class="area-show-price-post-transfer" style="display: none">
                                    <div class="line-dotted"></div>
                                    <h3><?php echo JText::_('Fee post transfer') ?></h3>
                                    <div class="list-post-transfer">
                                        <ul class="list_passenger_transfer">
                                        </ul>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="post-transfer-service-fee pull-right">
                                                <?php echo JText::_('Service fee') ?>
                                                <span class="post-transfer-service-fee-total"
                                                      data-a-sign="US$"></span>
                                                <input type="hidden" name="total_post_transfer_service_fee" value="0">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($this->pre_night_hotel_group_min_price != null) { ?>
                                <div class="area-show-pre-night-hotel" style="display: none">
                                    <div class="line-dotted"></div>
                                    <h3><?php echo JText::_('Room extra pre night fee') ?></h3>
                                    <div class="list-room-extra-pre-night">
                                        <ul class="list_passenger_room_extra_night">
                                        </ul>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="passenger-service-fee pull-right">
                                                <?php echo JText::_('Service fee') ?>
                                                <span class="pre-extra-night-service-fee-total"
                                                      data-a-sign="US$"></span>
                                                <input type="hidden" name="total_pre_night_hotel_service_fee" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($this->post_night_hotel_group_min_price != null) { ?>
                                <div class="area-show-post-night-hotel " style="display: none">
                                    <div class="line-dotted"></div>
                                    <h3><?php echo JText::_('Room extra post night fee') ?></h3>
                                    <div class="list-room-extra-post-night">
                                        <ul class="list_passenger_room_extra_night">
                                        </ul>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="passenger-service-fee pull-right">
                                                <?php echo JText::_('Service fee') ?>
                                                <span class="post-extra-night-service-fee-total"
                                                      data-a-sign="US$"></span>
                                                <input type="hidden" name="total_post_night_hotel_service_fee" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (count($this->list_excursion_addon)) { ?>
                                <div class="area-show-excursion-addon " style="display: none">
                                    <div class="line-dotted"></div>
                                    <h3><?php echo JText::_('Excursion addon') ?></h3>
                                    <div class="excursion-addon">
                                        <ul class="list_passenger_excursion_addon">
                                        </ul>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="passenger-service-fee pull-right">
                                                <?php echo JText::_('Service fee') ?>
                                                <span class="excursion-service-fee-total"
                                                      data-a-sign="US$"></span>
                                                <input type="hidden" name="total_excursion_addon_service_fee" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="total-pay">
                                        <div class="row">
                                            <div class="col-lg-3"><span><?php echo JText::_('you pay') ?></span></div>
                                            <div class="col-lg-4">
                                                <b class="pull-right"><span data-a-sign="US$" class="total-cost-service price"><?php echo ($total_tour_cost+$total_supplement_cost) ?></span></b>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="pull-right"><span><?php echo JText::_('cost rule') ?></span>
                                                    <span class="glyphicon  glyphicon-circle-arrow-right"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input name="option" value="com_tsmart" type="hidden">
            <input name="controller" value="bookprivategroupaddon" type="hidden">
            <input type="hidden" value="bookprivategroupaddon" name="view">
            <input name="booking_date" value="<?php echo $booking_date ?>" type="hidden">
            <input name="tsmart_price_id" value="<?php echo $tsmart_price_id ?>" type="hidden">
            <input name="task" value="" type="hidden">
        </form>
    </div>
<?php
$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-bookprivategroupaddon-default').view_bookprivategroupaddon_default({
                passenger_config:<?php  echo json_encode($passenger_config) ?>,
                departure:<?php  echo json_encode($privategrouptrip) ?>,
                rooming:"<?php  echo $rooming ?>",
                debug:<?php  echo json_encode($debug) ?>,
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