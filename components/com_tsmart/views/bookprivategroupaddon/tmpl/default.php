<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.css');
$doc->addScript(JUri::root() . '/components/com_virtuemart/assets/js/view_bookprivategroupaddon_default.js');
$doc->addLessStyleSheet(JUri::root() . '/components/com_virtuemart/assets/less/view_bookprivategroupaddon_default.less');
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/plugin/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/plugin/jquery-cookie-master/src/jquery.cookie.js');
$doc->addScript(JUri::root() . '/media/system/js/tipso-master/src/tipso.js');

$app = JFactory::getApplication();
$input = $app->input;
$virtuemart_price_id = $input->getInt('virtuemart_price_id', 0);
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

?>
    <div class="view-bookprivategroupaddon-default">
        <form
            action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=bookprivategroupaddonaddon&virtuemart_price_id=' . $virtuemart_price_id) ?>"
            method="post"
            id="bookprivategroupaddon" name="bookprivategroupaddon">
            <div class="row-fluid">
                <div class="span6 offset6">

                </div>
            </div>
            <div class="row-fluid">
                <div class="span9">
                    <h3 class="passenger-details"><?php echo JText::_('Additional service') ?></h3>

                    <div class="row-fluid">
                        <div class="span12">
                            <h4 class="build-your-room"><span class="travel-icon"
                                                              title="">n</span><?php echo JText::_('Airport transfer') ?>
                            </h4>
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
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4 class="build-your-room"><?php echo JText::_('Airport pickup pre transfer') ?></h4>
                                </div>
                            </div>

                            <?php echo VmHtml::build_pickup_transfer("build_pre_transfer", array(), 0, $privategrouptrip, $passenger_config, $this->pre_transfer_item) ?>
                        </div>
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4 class="build-your-room"><?php echo JText::_('Airport pickup post transfer') ?></h4>
                                </div>
                            </div>
                            <?php
                            ?>
                            <?php echo VmHtml::build_pickup_transfer("build_post_transfer", array(), 0, $privategrouptrip, $passenger_config, $this->post_transfer_item) ?>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <h4 class="build-your-room"><span class="travel-icon"
                                                              title="">n</span><?php echo JText::_('Extra night hotel') ?>
                            </h4>
                        </div>
                    </div>

                    <div class="joint_group_note joint_group_note_1">
                        <div class="row-fluid">
                            <div class="span12">
                                <?php echo $this->lipsum->words(100) ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="pull-right">
                                    <?php echo VmHTML::list_checkbox('rooming', $this->rooming_select_type, 'select_pre_post_night'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <h4 class="build-your-room"><?php echo JText::_('Pre night hotel') ?></h4>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">

                            <?php echo VmHtml::build_extra_night_hotel(array(), "extra_pre_night_hotel", "", $privategrouptrip, $passenger_config, 'pre',$this->pre_night_item) ?>

                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <h4 class="build-your-room"><?php echo JText::_('post night hotel') ?></h4>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo VmHtml::build_extra_night_hotel(array(), "extra_post_night_hotel", "", $privategrouptrip, $passenger_config, 'post',$this->post_night_item) ?>

                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <h4 class="build-your-room"><span class="travel-icon"
                                                              title="">n</span><?php echo JText::_('Extra Activity') ?>
                            </h4>
                        </div>
                    </div>

                    <div class="joint_group_note joint_group_note_2">
                        <div class="row-fluid">
                            <div class="span12">
                                <?php echo $this->lipsum->words(50) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo VmHtml::build_excursion_addon(array(), "build_excursion_addon", "", $privategrouptrip, $passenger_config, $this->list_excursion_addon) ?>

                        </div>
                    </div>


                    <div class="row-fluid">
                        <div class="span12">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary btn-large control-skip "><span title=""
                                                                                                            class="icon-next "></span><?php echo JText::_('Skip') ?>
                                </button>
                                <button type="submit" class="btn btn-primary btn-large control-next "><span title=""
                                                                                                            class="icon-next "></span><?php echo JText::_('Next') ?>
                                </button>
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
                <div class="span3">
                    <div class="booking-summary-content">
                        <h1 class="book-and-go"><?php echo JText::_('Book and Go') ?></h1>
                        <div class="booking-summary-content-body">
                            <h3 class="booking-summary"><?php echo JText::_('Booking summary') ?></h3>
                            <div class="row-fluid"><span
                                    class="detail pull-right"><?php echo JText::_('details') ?></span></div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4 class="trip"><span class="title"><?php echo JText::_('Trip') ?> :</span><span
                                            class="trip-name"><?php echo $this->product->product_name ?></span></h4>
                                </div>
                            </div>

                            <div class="row-fluid">
                                <div class="span6">
                                    <div><span
                                            class="icon-clock"></span><?php echo JHtml::_('date', $privategrouptrip->departure_date) ?>
                                        ,<?php echo $des_start ?></div>
                                </div>
                                <div class="span6">
                                    <?php
                                    $start_date->modify("+$total_day day");

                                    ?>
                                    <div><span class="icon-clock"></span><?php echo JHtml::_('date', $start_date) ?>
                                        ,<?php echo $des_finish ?></div>
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
                            <h4><span class="icon-users"></span><span
                                    class="passenger-number"><?php echo JText::_('Passenger number') ?> : </span><span
                                    class="total-passenger"> 7 </span> <?php echo JText::_('pers') ?>.</h4>
                            <ul class="list_passenger">

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
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="passenger-service-fee pull-right">
                                        <?php echo JText::_('Service fee') ?> <span class="passenger-service-fee-total"
                                                                                    data-a-sign="US$"><?php echo $tour_total_price ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="line-dotted"></div>
                            <h3><?php echo JText::_('Room supplement fee') ?></h3>
                            <div class="list-room-supplement">
                                <ul class="list_passenger_room_supplement">
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
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="passenger-service-fee pull-right">
                                        <?php echo JText::_('Service fee') ?> <span class="passenger-service-fee-total"
                                                                                    data-a-sign="US$"><?php echo $tour_total_price ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="line-dotted"></div>
                            <h3><?php echo JText::_('Fee pre transfer') ?></h3>
                            <div class="list-pre-transfer">
                                <ul class="list_passenger_transfer">

                                </ul>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="pre-transfer-service-fee pull-right">
                                        <?php echo JText::_('Service fee') ?> <span class="pre-transfer-service-fee-total"
                                                                                    data-a-sign="US$"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="line-dotted"></div>
                            <h3><?php echo JText::_('Fee post transfer') ?></h3>
                            <div class="list-post-transfer">
                                <ul class="list_passenger_transfer">

                                </ul>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="post-transfer-service-fee pull-right">
                                        <?php echo JText::_('Service fee') ?> <span class="post-transfer-service-fee-total"
                                                                                    data-a-sign="US$"></span>
                                    </div>
                                </div>
                            </div>




                            <div class="line-dotted"></div>
                            <h3><?php echo JText::_('Room extra pre night fee') ?></h3>
                            <div class="list-room-extra-pre-night">
                                <ul class="list_passenger_room_extra_night">

                                </ul>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="passenger-service-fee pull-right">
                                        <?php echo JText::_('Service fee') ?> <span class="pre-extra-night-service-fee-total"
                                                                                    data-a-sign="US$"></span>
                                    </div>
                                </div>
                            </div>



                            <div class="line-dotted"></div>
                            <h3><?php echo JText::_('Room extra post night fee') ?></h3>
                            <div class="list-room-extra-post-night">
                                <ul class="list_passenger_room_extra_night">

                                </ul>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="passenger-service-fee pull-right">
                                        <?php echo JText::_('Service fee') ?> <span class="post-extra-night-service-fee-total"
                                                                                    data-a-sign="US$"></span>
                                    </div>
                                </div>
                            </div>





                        </div>
                    </div>
                </div>
            </div>

            <input name="option" value="com_virtuemart" type="hidden">
            <input name="controller" value="bookprivategroupaddon" type="hidden">
            <input type="hidden" value="bookprivategroupaddon" name="view">
            <input name="booking_date" value="<?php echo $booking_date ?>" type="hidden">
            <input name="virtuemart_price_id" value="<?php echo $virtuemart_price_id ?>" type="hidden">
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