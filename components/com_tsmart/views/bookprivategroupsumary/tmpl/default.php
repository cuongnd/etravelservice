<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_tsmart/assets/js/view_bookprivategroupsumary_default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_tsmart/assets/less/view_bookprivategroupsumary_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root() . '/administrator/components/com_tsmart/assets/js/plugin/jquery-cookie-master/src/jquery.cookie.js');
$doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');

$app = JFactory::getApplication();
$input = $app->input;
$tsmart_price_id = $input->getInt('tsmart_price_id', 0);
$privategrouptrip = $this->privategrouptrip;

$session = JFactory::getSession();
$build_room = $session->get('build_room', '');

$json_list_passenger = $session->get('json_list_passenger', '');

$json_list_passenger = json_decode($json_list_passenger);
$list_passenger = array_merge($json_list_passenger->senior_adult_teen, $json_list_passenger->children_infant);

$build_room = json_decode($build_room);
$build_pre_transfer = $session->get('build_pre_transfer', '');
$build_post_transfer = $session->get('build_post_transfer', '');
$extra_pre_night_hotel = $session->get('extra_pre_night_hotel', '');
$extra_post_night_hotel = $session->get('extra_post_night_hotel', '');
$booking=tsmHelper::getHepler('booking');
$contact_data = $session->get('contact_data', '');
$total_price=$booking->get_total_price();
$booking_date=$input->getString('booking_date','');
$privategrouptrip->sale_price_senior=400;
$privategrouptrip->sale_price_adult=500;
$privategrouptrip->sale_price_teen=400;
$privategrouptrip->sale_price_children1=300;
$privategrouptrip->sale_price_children2=200;
$privategrouptrip->sale_price_infant=100;
$privategrouptrip->sale_price_private_room=100;
$privategrouptrip->sale_price_extra_bed=50;


$privategrouptrip->full_charge_children1=0;
$privategrouptrip->full_charge_children2=1;


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
<div class="view-bookprivategroupsumary-default">
    <form
        action="<?php echo JRoute::_('index.php?option=com_tsmart&view=bookprivategroupsumaryaddon&tsmart_price_id=' . $tsmart_price_id) ?>"
        method="post"
        id="bookprivategroupsumary" name="bookprivategroupsumary">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-6">

            </div>
        </div>
        <div class="row">
            <div class="col-lg-9">
                <h3 class="passenger-details"><?php echo JText::_('Service total-payment') ?></h3>


                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="pull-right build-your-room"><?php echo JText::_('Service total')?></h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <?php echo VmHtml::build_passenger_summary(array(),"passenger_summary","",$privategrouptrip,$passenger_config,$this->product) ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="coupon-discount">
                            <input type="text" placeholder="coupon number" name="coupon_number" class="coupon_number">
                            <button class="go get_coupon" type="button">GO</button>
                        </div>

                    </div>
                </div>
                <?php
                $percent_balance_of_day_1=$this->payment_rule->percent_balance_of_day_1;
                $deposit=$total_price*$percent_balance_of_day_1/100;
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="payment-overview">
                            <h4><?php echo JText::_('Tour fee summary')?></h4>
                            <div class="row">
                                <div class="col-lg-4"><?php echo JText::_('Total fee') ?> <span class="total_fee cost"><?php echo $total_price ?></span></div>
                                <div class="col-lg-4"><?php echo JText::_('Deposit') ?> <span class="cost"><?php echo $deposit ?></span></div>
                                <div class="col-lg-4"><?php echo JText::_('Balance') ?> <span class="cost"><?php echo $total_price-$deposit ?></span></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">

                        <?php echo VmHtml::build_payment_cardit_card('payment',$total_price,$deposit) ?>

                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary btn-large control-pay-now "><span title="" class="icon-next "></span><?php echo JText::_('Pay now') ?></button>
                        </div>
                    </div>
                </div>

            </div>
            <?php
            $list_destination = explode(';', $privategrouptrip->list_destination);
            $des_start = reset($list_destination);
            $des_finish = end($list_destination);
            $total_day = $privategrouptrip->total_day - 1;
            $start_date = JFactory::getDate($privategrouptrip->departure_date);

            $total_day = $total_day ? $total_day : 0;

            ?>
            <div class="col-lg-3">
                <div class="booking-summary-content">
                    <h1 class="book-and-go"><?php echo JText::_('Book and Go') ?></h1>
                    <div class="booking-summary-content-body">
                        <h3 class="booking-summary"><?php echo JText::_('Booking summary') ?></h3>
                        <div class="row" ><span class="detail pull-right"><?php echo JText::_('details') ?></span></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="trip"><span class="title"><?php echo JText::_('Trip') ?> :</span><span class="trip-name"><?php echo $this->product->product_name ?></span></h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div><span class="icon-clock"></span><?php echo  JHtml::_('date', $privategrouptrip->departure_date) ?>,<?php echo  $des_start ?></div>
                            </div>
                            <div class="col-lg-6">
                                <?php
                                $start_date->modify("+$total_day day");

                                ?>
                                <div><span class="icon-clock"></span><?php echo  JHtml::_('date', $start_date) ?>,<?php echo  $des_finish ?></div>
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
                        <h4><span class="icon-users"></span><span class="passenger-number"><?php echo JText::_('Passenger number') ?> : </span><span class="total-passenger"> 7 </span> <?php echo JText::_('pers') ?>.</h4>
                        <ul class="list_passenger">
                            <?php
                            $passenger_oder = 1;
                            $tour_total_price = 0;
                            ?>
                            <?php foreach ($build_room AS $room) { ?>
                                <?php
                                $tour_cost_and_room_price = $room->tour_cost_and_room_price;
                                foreach ($tour_cost_and_room_price as $item_price) {
                                    $tour_total_price += $item_price->tour_cost;
                                }
                                $passengers = $room->passengers;
                                foreach ($passengers AS $passenger_index) {
                                    $passenger = $list_passenger[$passenger_index];

                                    ?>
                                    <li><?php echo JText::_("$passenger->first_name $passenger->last_name") ?></li>
                                    <?php
                                    $passenger_oder++;
                                }
                                ?>
                            <?php } ?>
                        </ul>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="passenger-service-fee pull-right">
                                    <?php echo JText::_('Service fee') ?> <span class="passenger-service-fee-total" data-a-sign="US$">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="line-dotted"></div>
                        <h3><?php echo JText::_('Room supplement fee') ?></h3>
                        <div class="list-room">
                            <ul class="list_passenger_room">
                                <?php
                                $passenger_oder = 1;
                                $tour_total_price = 0;
                                ?>
                                <?php foreach ($build_room AS $room) { ?>
                                    <?php
                                    $tour_cost_and_room_price = $room->tour_cost_and_room_price;
                                    foreach ($tour_cost_and_room_price as $item_price) {
                                        $tour_total_price += $item_price->tour_cost;
                                    }
                                    $passengers = $room->passengers;
                                    foreach ($passengers AS $passenger_index) {
                                        $passenger = $list_passenger[$passenger_index];

                                        ?>
                                        <li><?php echo JText::_("$passenger->first_name $passenger->last_name") ?></li>
                                        <?php
                                        $passenger_oder++;
                                    }
                                    ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
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
        <input name="booking_date" value="<?php echo $booking_date ?>" type="hidden">
        <input name="tsmart_price_id" value="<?php echo $tsmart_price_id ?>" type="hidden">
        <input name="controller" value="bookprivategroupsumary" type="hidden">
        <input name="booking_summary" value="" type="hidden">
        <input type="hidden" value="bookprivategroupsumary" name="view">
        <input name="task" value="" type="hidden">
    </form>


</div>
<?php
$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-bookprivategroupsumary-default').view_bookprivategroupsumary_default({
                passenger_config:<?php  echo json_encode($passenger_config) ?>,
                departure:<?php  echo json_encode($privategrouptrip) ?>,
                tour_min_age:<?php echo $this->product->min_age ?>,
                tour:<?php echo json_encode($this->product) ?>,
                build_room:<?php echo json_encode($build_room) ?>,
                build_pre_transfer:<?php echo $build_pre_transfer ?>,
                build_post_transfer:<?php echo $build_post_transfer ?>,
                extra_pre_night_hotel:<?php echo $extra_pre_night_hotel ?>,
                extra_post_night_hotel:<?php echo $extra_post_night_hotel ?>,
                total_price:<?php echo $total_price ?>,
                contact_data:<?php echo $contact_data ?>,
                list_passenger:<?php echo json_encode($json_list_passenger) ?>

            });


        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = TSMUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>